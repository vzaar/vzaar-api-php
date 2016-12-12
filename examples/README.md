# Integration tests

## How to run examples

Ensure that you have installed the package using `composer`.

Set system environment variables:

```
export VZAAR_CLIENT_ID=<your-client-id>
export VZAAR_AUTH_TOKEN=<your-auth-token>
```

Run the example files from the `examples` directory:

```
cd examples
php -f <example-file-name>.php
```

### Error: credentials missing
If your environment varialbes are set but not being recognised correctly,
please check that the `variables_order` variable in your `php.ini` file allows
ENV super global to be registered.

The default value is _probably_ this:

```
variables_order = "GPCS"
```

Change it to this:

```
variables_order = "EGPCS"
```

## License

Released under the [MIT License](http://www.opensource.org/licenses/MIT).
