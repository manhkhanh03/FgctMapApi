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


// Baidu
$baidu = new BaiduApi('<your ak>');

$response = $baidu->driving_route("31.25255, 121.45315", "31.30478, 120.57562", [
    'output' => 'json'
]);

$response = $baidu->geocoding("上海市", [
    'output' => 'json'
]);

// Goong
$goong = new Goong('<your key>');

$response = $goong->geocode("Hà Nội, vietnam");

$response = $goong->autocomplete("Hà Nội");

```
