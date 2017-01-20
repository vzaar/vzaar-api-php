# PHPUnit tests

## How to run tests

Ensure that you have installed the package using `composer`.

Run the tests from the root directory of the package:

```
./vendor/bin/phpunit
```

## Running the tests using the Docker containers

From the root of the package, run the tests inside a docker container:

```
docker-compose run --rm php-7.1 vendor/bin/phpunit --testdox-text phpunit.log
```

This will run the tests using the `php-7.1` service. The test output is written to `phpunit.log`.

## License

Released under the [MIT License](http://www.opensource.org/licenses/MIT).
