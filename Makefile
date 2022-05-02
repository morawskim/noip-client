.PHONY: test
test:
	docker-compose run --rm cli /app/vendor/bin/phpunit

.PHONY: build
build:
	docker-compose run --rm  cli php -dphar.readonly=0 ./vendor/bin/phing
	chmod a+x ./build/noip.phar

.PHONY: docker
docker: build
	docker build -t morawskim/noip-client -fDockerfile .

.PHONY: publish
publish:
	docker push morawskim/noip-client
