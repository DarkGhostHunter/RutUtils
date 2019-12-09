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
     * Creates a new Rut instance.
     *
     * @param  integer $num
     * @param  string $vd
     */
    public function __construct(int $num, string $vd)
    {
        $this->rut = [
            'num' => $num,
            'vd'  => $this->case($vd),
        ];
    }

    /**
     * Clones this Rut instance into a new instance
     *
     * @return \DarkGhostHunter\RutUtils\Rut
     */
    public function clone()
    {
        return clone $this;
    }

    /**
     * Returns the RUT as an array
     *
     * @return array
     */
    public function getRut()
    {
        return $this->rut;
    }

    /**
     * Makes one Rut instance, or null if its malformed. No validation is done.
     *
     * @param  mixed $rut
     * @param  null|int|string|callable $vd
     * @param  null|callable|mixed $default
     * @return null|mixed|\DarkGhostHunter\RutUtils\Rut
     */
    public static function make($rut, $vd = null, $default = null)
    {
        if ($rut instanceof static) {
            return $rut;
        }

        if (is_callable($vd) && $default === null) {
            $default = $vd;
            $vd = null;
        }

        if (is_array($rut)) {
            [$rut, $vd, $default] = array_pad($rut, 3, null);
        }
        elseif ($vd === null) {
            [$rut, $vd] = RutHelper::separateRut($rut);
        }

        // Create a new instance of a Rut if both parameters are correct.
        if ($rut && $vd !== null) {
            $rut = new static((int)$rut, $vd);

            if ($rut->isValid()) {
                return $rut;
            }
        }

        return is_callable($default) ? $default() : $default;
    }

    /**
     * Makes a single Rut instance, or throws an exception when its malformed or invalid
     *
     * @param  string|int $rut
     * @param  null $vd
     * @return \DarkGhostHunter\RutUtils\Rut
     * @throws \DarkGhostHunter\RutUtils\Exceptions\InvalidRutException
     */
    public static function makeOrThrow($rut, $vd = null)
    {
        if ($rut = self::make($rut, $vd)) {
            return $rut;
        }

        throw new InvalidRutException($rut);
    }

    /**
     * Makes many Rut instances from a given array, discarding malformed ones.
     *
     * @param  array $ruts
     * @return array
     */
    public static function many(...$ruts)
    {
        $ruts = RutHelper::unpack($ruts);

        foreach ($ruts as $key => $value) {
            $ruts[$key] = static::make($value);
        }

        $ruts = array_filter($ruts);

        foreach (static::$after as $callback) {
            $ruts = $callback($ruts);
        }

        return $ruts;
    }

    /**
     * Creates only valid RUTs, or throw an exception if at least one is malformed or invalid
     *
     * @param  array $ruts
     * @return array|mixed
     * @throws \DarkGhostHunter\RutUtils\Exceptions\InvalidRutException
     */
    public static function manyOrThrow(...$ruts)
    {
        $ruts = RutHelper::unpack($ruts);

        foreach ($ruts as $key => $value) {
            $ruts[$key] = static::makeOrThrow($value);
        }

        foreach (static::$after as $callback) {
            $ruts = $callback($ruts);
        }

        return $ruts;
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
            'vd'  => $vd,
        ];
    }
}