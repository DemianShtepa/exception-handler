analysis: phpcs phpstan
testing: migrate seed test

phpcs:
	./vendor/bin/phpcs --standard=PSR12 app/
phpstan:
	./vendor/bin/phpstan analyse -c phpstan.neon.dist
migrate:
	php artisan doctrine:migrations:refresh --env=testing
seed:
	php artisan db:seed --env=testing
test:
	php artisan test --env=testing
