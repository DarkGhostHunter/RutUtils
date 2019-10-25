<?php

namespace DarkGhostHunter\RutUtils;

use ArrayAccess;
use Serializable;
use JsonSerializable;
use DarkGhostHunter\RutUtils\Exceptions\InvalidRutException;

/**
 * Class Rut
 *
 * @package DarkGhostHunter\RutUtils
 *
 * @property-read null|int $num
 * @property-read null|int|string $vd
 *
 */
class Rut implements ArrayAccess, JsonSerializable, Serializable
{
    use SerializesToJson,
        HasHelpers,
        HasFormats,
        HasCase,
        HasCallbacks;

    public const FORMAT_STRICT = 'strict';
    public const FORMAT_BASIC = 'basic';
    public const FORMAT_RAW = 'raw';

    /**
     * Where to draw the line between person and company RUTs
     *
     * @const int
     */
    public const COMPANY_RUT_BASE = 50000000;

    /**
     * RUT Composition
     *
     * @var array
     */
    protected $rut;

    /**
     * Makes one Rut instance. No validation is done.
     *
     * @param $rut
     * @param  null $vd
     * @return \DarkGhostHunter\RutUtils\Rut
     */
    public static function make($rut, $vd = null)
    {
        return new static($rut, $vd);
    }

    /**
     * Makes many Rut instances from the given array
     *
     * @param  array $ruts
     * @return array
     */
    public static function makeMany(...$ruts)
    {
        $ruts = RutHelper::unpack($ruts);

        foreach ($ruts as $key => $value) {
            [$num, $vd] = is_array($value) ? array_pad($value, 2, null) : [$value, null];
            $ruts[$key] = static::make($num, $vd);
        }

        foreach (static::$after as $callback) {
            $ruts = $callback($ruts);
        }

        return $ruts;
    }

    /**
     * Makes only Valid Ruts, discarding the wrong ones.
     *
     * @param  array $ruts
     * @return array
     */
    public static function makeValid(...$ruts)
    {
        $ruts = RutHelper::unpack($ruts);

        foreach ($ruts as $key => $value) {
            [$num, $vd] = is_array($value) ? array_pad($value, 2, null) : [$value, null];
            $ruts[$key] = static::make($num, $vd);
        }

        $ruts = RutHelper::filter($ruts);

        foreach (static::$after as $callback) {
            $ruts = $callback($ruts);
        }

        return $ruts;
    }

    /**
     * Creates only valid RUTs, or throw an exception
     *
     * @param  array $ruts
     * @return array|mixed
     * @throws \DarkGhostHunter\RutUtils\Exceptions\InvalidRutException
     */
    public static function makeOrThrow(...$ruts)
    {
        $ruts = RutHelper::unpack($ruts);

        $expected = count($ruts);

        $ruts = RutHelper::filter(static::makeMany($ruts));

        if (($actual = count($ruts)) < $expected) {
            throw new InvalidRutException($ruts, $expected, $actual);
        }

        return $ruts;
    }

    /**
     * Creates a new Rut instance.
     *
     * @param integer $num
     * @param string $vd
     */
    public function __construct($num = null, string $vd = null)
    {
        if ($num) {
            $this->putRut($num, $vd);
        }
    }

    /**
     * Adds a RUT string to the instance, or replaces the one existing.
     *
     * @param string|int $num
     * @param  null $vd
     * @return $this
     */
    public function putRut($num, $vd = null)
    {
        [$this->rut['num'], $this->rut['vd']] = RutHelper::separateRut($num . $vd);

        return $this;
    }

    /**
     * Returns the RUT as an array
     *
     * @return array
     */
    public function getRut()
    {
        return $this->rut ?? $this->rut = ['num' => null, 'vd' => null];
    }

    /**
     * Dynamically manage getting the RUT attributes
     *
     * @param $name
     * @return string|int|null
     */
    public function __get($name)
    {
        if ($name === 'vd' && $this->rut['vd']) {
            return $this->shouldUppercase()
                ? strtoupper($this->rut['vd'])
                : strtolower($this->rut['vd']);
        }

        return $this->rut[$name] ?? null;
    }

    /**
     * Dynamically set an attribute
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        // Don't allow to set attributes
    }

    /**
     * Returns if an attribute is set
     *
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->rut[$name]);
    }

    /**
     * Unset a property or attribute
     *
     * @param $name
     */
    public function __unset($name)
    {
        // Dont' allow to unset attributes Ruts
    }

    /**
     * Whether a offset exists
     *
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    /**
     * Offset to retrieve
     *
     * @param $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * Offset to set
     *
     * @param $offset
     * @param $value
     */
    public function offsetSet($offset, $value)
    {
        // Dont' allow to set attributes
    }

    /**
     * Offset to unset
     *
     * @param $offset
     */
    public function offsetUnset($offset)
    {
        // Dont' allow to unset attributes Ruts
    }

    /**
     * Returns the RUT as a JSON string
     *
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->jsonSerialize());
    }

    /**
     * Return the RUT as a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toFormattedString();
    }

    /**
     * Return an array representation of the Rut instance
     *
     * @return array|string
     */
    public function toArray()
    {
        return $this->rut;
    }

    /**
     * String representation of object
     *
     * @return string
     */
    public function serialize()
    {
        return $this->toRawString();
    }

    /**
     * Constructs the object
     *
     * @param  string $serialized
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        [$num, $vd] = str_split($serialized, strlen($serialized) - 1);

        $this->rut = [
            'num' => (int)$num,
            'vd' => is_numeric($vd) ? (int)$vd : $vd,
        ];
    }
}