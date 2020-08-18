# Amazon MWS SDK

<a href="https://packagist.org/packages/huule/amazon-mws-sdk"><img src="https://poser.pugx.org/huule/amazon-mws-sdk/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/huule/amazon-mws-sdk"><img src="https://poser.pugx.org/huule/amazon-mws-sdk/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/huule/amazon-mws-sdk"><img src="https://poser.pugx.org/huule/amazon-mws-sdk/license.svg" alt="License"></a>

##### Available APIs:
- [x] Feeds
- [x] Reports
- [x] Orders
- [x] Products

##### Install SDK

- Run command

<code>composer require huule\\amazon-mws-sdk</code>

- Setup your configuration

###### Test SDK
- Create config.php file
```
$config = [
    // Amazon MWS configs
    // Application config
    'ApplicationName' => 'Amazon MWS SDK Demo - Products',
    'ApplicationVersion' => '1.0.0',
    // Authentication config
    'SellerID' => '<Your Seller ID>',
    'MarketplaceID' => '<Your Marketplace ID>',
    // Amazon MWS config
    'ServiceURL' => 'https://mws.amazonservices.com/Products/2011-10-01',
    'ProxyHost' => null,
    'ProxyPort' => -1,
    'MaxErrorRetry' => 3,
    // SDK custom config
    'DefaultTimezone' => 'UTC',
    'DefaultDateTimeFormat' => 'Y-m-d H:i:s',
];
```
- Create GetServiceStatus.php file with content
```
require_once __DIR__ . 'vendor/autoload.php';
require_once __DIR__ . 'config.php';

$client = new HuuLe\AmazonSDK\Client\MWSProductClient(
    '<Your access key ID>',
    '<Your secret key>',
    $config
);

$result = $client->getServiceStatus();
var_dump($result);
```

---
**!!! Important**

:warning: You must enable SSL for your host or localhost.

---
