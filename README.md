# vzaar API

![vzaar Logo](https://raw.github.com/vzaar/vzaar-api-php/master/vzaar.png)

By [vzaar](http://vzaar.com)

vzaar's PHP client for interacting with version 2 of the [vzaar API](https://vzaar.readme.io/docs).

## Requirements

- Recommended: PHP 7.0+
- Minimum: PHP 5.6+

## Installation

Install the package using `composer`:

```
composer install
```

If `composer` is not installed, please follow [these instructions](https://getcomposer.org/doc/00-intro.md).

## Backwards compatability

Versions 1 and 2 of the vzaar API are incompatable with each other. The same applies to the API SDKs. Some functionality available in the version 1 SDK has been deprecated in version 2 and will not be implemented. The version 2 API also has a different authentication mechanism so your existing API key will not work with version 2 SDKs.

To ease migration to the version 2 SDK, it is possible to use both versions without any conflicts.

```
Vzaar       # <= this is version 1
VzaarApi    # <= this is version 2
```

## Usage

Configure your API credentials:

```

VzaarApi\Client::$client_id  = '<your-client-id>';
VzaarApi\Client::$auth_token = '<your-auth-token>';
```

Usage instructions and examples are available on [vzaar's documentation site](https://vzaar.readme.io).


## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request

## License

Released under the [MIT License](http://www.opensource.org/licenses/MIT).
