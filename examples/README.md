# Integration tests

## How to run examples

Ensure that you have installed the package using `composer`.

Make a local copy of the `autoload.php` file:

```
cp examples/autoload.php.example examples/autoload.php
```

Update the file with your API credentials:

```
VzaarApi\Client::$client_id = '<your-client-id>';
VzaarApi\Client::$auth_token = '<your-auth-token>';
```

Run the tests from the root directory of the package:

```
php -f examples/<example-file-name>.php
```

## License

Released under the [MIT License](http://www.opensource.org/licenses/MIT).
