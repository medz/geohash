# Geo Hash for PHP
Longitude and Latitude Geo Hash Library.

## Install

```
composer require medz/geohash
```

## Using

```php
use Medz\GeoHash\GeoHash;

$geohash = GeoHash::encode([116.389550, 39.928167], 6 /* Min: 1, Max: 24 */); // wx4g0e
$coordinates = GeoHash::decode('wx4g0e'); // ->$longitude, -> $latitude, Or ->toArray();

```

## LICENSE

MIT
