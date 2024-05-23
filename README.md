# Welcome to Package by doublezzero20

## Installation

```
composer require fgct-map/maps-api:dev-main
```

## Usage

```php
// Import the class namespaces first, before using it directly
use FGCTApi\GoogleMaps\Api\GoogleMapsApi;
use FGCTApi\Baidu\Api\BaiduApi;

$ggMap = new GoogleMapsApi("<your-key>");
$baidu = new BaiduApi("<your-key>");
```
