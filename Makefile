.PHONY: test
test:
	docker-compose run --rm cli /app/vendor/bin/phpunit

.PHONY: build
build:
	docker-compose run --rm  cli php -dphar.readonly=0 ./vendor/bin/phing
