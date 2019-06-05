<?php

namespace Medz\GeoHash;

use InvalidArgumentException;

class Encoder
{
    protected $geohash = '';

    public function __construct(array $coordinates, int $length = GeoHash::MAX_LENGTH)
    {
        if ($length < GeoHash::MIN_LENGTH || $length > GeoHash::MAX_LENGTH) {
            throw new InvalidArgumentException('The length should be between 1 and 12.');
        }

        $latitudeInterval  = GeoHash::$latitudeInterval;
        $longitudeInterval = GeoHash::$longitudeInterval;
        $isEvent = true;
        $bit = 0;
        $charIndex = 0;

        while (strlen($this->geohash) < $length) {
            if ($isEvent) {
                $middle = ($longitudeInterval[0] + $longitudeInterval[1]) / 2;
                if (($coordinates[0] ?? $coordinates['longitude']) > $middle) {
                    $charIndex |= GeoHash::$bits[$bit];
                    $longitudeInterval[0] = $middle;
                } else {
                    $longitudeInterval[1] = $middle;
                }
            } else {
                $middle = ($latitudeInterval[0] + $latitudeInterval[1]) / 2;
                if (($coordinates[1] ?? $coordinates['latitude']) > $middle) {
                    $charIndex |= GeoHash::$bits[$bit];
                    $latitudeInterval[0] = $middle;
                } else {
                    $latitudeInterval[1] = $middle;
                }
            }

            if ($bit < 4) {
                $bit++;
            } else {
                $this->geohash .= GeoHash::$base32Chars[$charIndex];
                $bit = 0;
                $charIndex = 0;
            }

            $isEvent = !$isEvent;
        }
    }

    /**
     * To string.
     * @return string
     */
    public function toString(): string
    {
        return $this->geohash;
    }

    /**
     * E.g to string.
     */
    public function __toString()
    {
        return $this->__toString();
    }
}
