<?php

namespace Medz\GeoHash;

use RuntimeException;
use InvalidArgumentException;

class Decoder
{
    protected $latitudeInterval;
    protected $longitudeInterval;

    public function __construct(string $geohash)
    {
        if (!is_string($geohash)) {
            throw new InvalidArgumentException('The geo hash should be a string.');
        }
        if (strlen($geohash) < GeoHash::MIN_LENGTH || strlen($geohash) > GeoHash::MAX_LENGTH) {
            throw new InvalidArgumentException('The length of the geo hash should be between 1 and 12.');
        }

        $base32DecodeMap = $this->getBase32DecodeMap();
        $latitudeInterval  = GeoHash::$latitudeInterval;
        $longitudeInterval = GeoHash::$longitudeInterval;
        $isEvent = true;
        $bitsTotal = count(GeoHash::$bits);

        $geohashLength = strlen($geohash);
        for ($i = 0; $i < $geohashLength; $i++) {
            if (!isset($base32DecodeMap[$geohash[$i]])) {
                throw new RuntimeException('This geo hash is invalid.');
            }

            $currentChar = $base32DecodeMap[$geohash[$i]];
            for ($j = 0; $j < $bitsTotal; $j++) {
                $mask = GeoHash::$bits[$j];
                if ($isEvent) {
                    if (($currentChar & $mask) !== 0) {
                        $longitudeInterval[0] = ($longitudeInterval[0] + $longitudeInterval[1]) / 2;
                    } else {
                        $longitudeInterval[1] = ($longitudeInterval[0] + $longitudeInterval[1]) / 2;
                    }
                } else {
                    if (($currentChar & $mask) !== 0) {
                        $latitudeInterval[0] = ($latitudeInterval[0] + $latitudeInterval[1]) / 2;
                    } else {
                        $latitudeInterval[1] = ($latitudeInterval[0] + $latitudeInterval[1]) / 2;
                    }
                }

                $isEvent = !$isEvent;
            }
        }

        $this->latitudeInterval  = $latitudeInterval;
        $this->longitudeInterval = $longitudeInterval;
    }

    protected function getBase32DecodeMap(): array
    {
        $base32DecodeMap  = [];
        $base32CharsTotal = count(GeoHash::$base32Chars);
        for ($i = 0; $i < $base32CharsTotal; $i++) {
            $base32DecodeMap[GeoHash::$base32Chars[$i]] = $i;
        }

        return $base32DecodeMap;
    }

    public function toArray(): array
    {
        return [
            $this->longitude,
            $this->latitude,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
        ];
    }

    public function __get($name)
    {
        if ($name === 'longitude') {
            return ($this->longitudeInterval[0] + $this->longitudeInterval[1]) / 2;
        } elseif($name === 'latitude') {
            return ($this->latitudeInterval[0] + $this->latitudeInterval[1]) / 2;
        }

        throw new InvalidArgumentException('Please using longitude and latitude.');
    }
}
