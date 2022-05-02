<?php

namespace noip\Commands;

use noip\Models\NoIpAccount;
use noip\Models\NoIpApi;
use noip\Models\NoIpConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NoIpCommand extends Command
{
    /**
     * @var NoIpAccount
     */
    private $model;

    /**
     * @var NoIpApi
     */
    private $apiModel;

    protected function configure(): void
    {
        $this
            ->setName('noip:update')
            ->setDescription('Update ip')
            ->addArgument(
                'force',
                InputArgument::OPTIONAL,
                'Who do you want to greet?',
                false
            )
            ->addOption(
                'file',
                'f',
                InputOption::VALUE_OPTIONAL,
                'The path to configuration file',
                '.env'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var bool $forceUpdate */
        $forceUpdate = !!$input->getArgument('force');

        $model = $this->getModel();
        $api = $this->getApiModel();

        if (empty($model)) {
            $configFile = $input->getOption('file');
            $configuration = NoIpConfiguration::parseDotEnvFile($configFile);
            $model = new NoIpAccount(
                $configuration->getUsername(),
                $configuration->getPassword(),
                $configuration->getHostname()
            );
            $this->setModel($model);
        }

        if (empty($api)) {
            $api = new NoIpApi($model);
            $this->setApiModel($api);
        }

        $ip = $api->getMyIp();

        $currentIp = $api->getCurrentAssignedIp();

        if (!$forceUpdate && $ip === $currentIp) {
            $output->writeln(sprintf('The same IP (%s) address. Skip.', $ip));
            return 0;
        }

        try {
            $output->writeln(sprintf('Updating IP address from "%s" to "%s"', $currentIp, $ip));
            $api->update($ip);
        } catch (\Exception $e) {
            if ($output instanceof ConsoleOutputInterface) {
                $output->getErrorOutput()->writeln($e->getMessage());
            } else {
                $output->writeln($e->getMessage());
            }
            return 1;
        }

        $output->writeln('SUCCESS');
        return 0;
    }

    /**
     * @return NoIpAccount
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param NoIpAccount $model
     */
    public function setModel(NoIpAccount $model)
    {
        $this->model = $model;
    }

    /**
     * @return NoIpApi
     */
    public function getApiModel()
    {
        return $this->apiModel;
    }

    /**
     * @param NoIpApi $apiModel
     */
    public function setApiModel(NoIpApi $apiModel)
    {
        $this->apiModel = $apiModel;
    }
}
