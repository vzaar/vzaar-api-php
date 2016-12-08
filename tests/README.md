# PHPUnit tests

## How to run tests

After the package [installation](../README.md) with Composer navigate to the library directory.
```
<your-project-directory>/vendor/vzaar/vzaar-api-php
```

Set system environment variables:

If you use bash shell:

```
export VZAAR_CLIENT_ID=<your-client-id>
export VZAAR_AUTH_TOKEN=<your-auth-token>
```

Run tests (CLI):
(NOTE: if credentials missing during tests run, please check if 'variables_order' variable in your php.ini file allows ENV super global to be registered)

```
phpunit
```

## License

Released under the [MIT License](http://www.opensource.org/licenses/MIT).
