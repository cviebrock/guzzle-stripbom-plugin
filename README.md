guzzle-stripbom-plugin
======================

Plug-in for [Guzzle](http://guzzlephp.org/) that strips BOMs from server responses.


## Usage

In your project's `composer.json` file:

```
	"require": {
		"cviebrock/guzzle-stripbom-plugin": "1.0.*",
		}
```

In your code:

```php
$client = new Guzzle\Http\Client('http://example.com');

$client->addSubscriber( new Cviebrock\Guzzle\Plugin\StripBomPlugin() );

$request = $client->get('some/request');

$response = $client->send($request);

$data = $response->json();
```


## Why?

Some API services (mostly .NET services) include a [BOM](http://en.wikipedia.org/wiki/Byte_order_mark) in their response body.
The BOM is 2-4 bytes that indicate what character encoding the response is in (e.g. UTF8).  The problem is that PHP's
`json_decode()` function and `SimpleXML` classes barf when trying to parse strings that include a BOM.
If you are getting a "Can't parse JSON" error when handling a request, but it looks like JSON to you, this is likely what's happening.

This plugin strips those bytes off if they exist, before any JSON/XML parsing.


## Kudos?  Questions?  Complaints?

Please use the [issue tracker](https://github.com/cviebrock/guzzle-stripbom-plugin/issues).