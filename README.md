# Counting commissions

It is a console application which counts commissions by a given data

## Usage

At first install  all missing dependencies. You can do that with the following command. Just make sure you have globally installed composer tool before doing that.

`composer install`

You can run the program from the projects base directory with the following command:

`php bin/app.php commissions [path to CSV document]`

For example:

`php bin/app.php commissions docs/input.csv`

## Testing

Tests can be easily run with the following command from the base directory:

`phpunit`