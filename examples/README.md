# Integration tests

## How to run examples

After the package [installation](../README.md) with Composer navigate to a directory where the examples are stored:

```
<your-project-directory>/vendor/vzaar/vzaar-api-php/examples
```

Set system environment variables:

If you use bash shell:

```
export VZAAR_CLIENT_ID=<your-client-id>
export VZAAR_AUTH_TOKEN=<your-auth-token>
```

Run examples (CLI):
(NOTE: if credentials missing during examples run, please check if 'variables_order' variable in your php.ini file allows ENV super global to be registered)

```
php -f <example-file-name>.php
```

## License

Released under the [MIT License](http://www.opensource.org/licenses/MIT).
