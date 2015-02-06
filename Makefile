# Install
install: install-composer

install-composer:
	composer install --dev --optimize-autoloader --prefer-dist

# code style
phpcs:
	php vendor/fabpot/php-cs-fixer/php-cs-fixer fix src --verbose
