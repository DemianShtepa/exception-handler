analysis:
	phpcs phpstan

phpcs:
	./vendor/bin/phpcs --standard=PSR12 app/
phpstan:
	vendor/bin/phpstan analyse -l 6 app/Domain/ app/Infrastructure
