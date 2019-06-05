<?php

namespace Medz\GeoHash;

use Exception;

class GeoHash
{
    /**
     * The minimum length of the geo hash.
     * @var int
     */
    const MIN_LENGTH = 1;

    /**
     * The maximum length of the geo hash.
     * @var int
     */
    const MAX_LENGTH = 24;

    /**
     * The interval of bits.
     * @var array
     */
    public static $bits = [16, 8, 4, 2, 1];

    /**
     * The array of chars in base 32.
     * @var array
     */
    public static $base32Chars = [
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'b', 'c', 'd', 'e', 'f', 'g',
        'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
    ];

    /**
     * The interval of latitudes in degrees.
     * @var array
     */
    public static $longitudeInterval = [-180.0, 180.0];

    /**
     * The interval of longitudes in degrees.
     * @var array
     */
    public static $latitudeInterval = [-90.0, 90.0];

    /**
     * Static call run encode and decode class.
     * @param string $method Call handler.
     * @param string $arguments Handler arguments.
     * @return \Medz\GeoHash\Encoder|\Medz\GeoHash\Decoder
     */
    public static function __callStatic($method, $arguments)
    {
        switch ($method) {
            case 'encode':
                return new Encoder(...$arguments);

            case 'decode':
                return new Decoder(...$arguments);
            
            default:
                throw new Exception('Only support encode and decode method.');
        }
    }
}
