install:
	composer install
lint:
	composer run-script phpcs -- --standard=PSR2 app routes tests
test:
	composer run-script test
run:
	php -S localhost:8000 -t public