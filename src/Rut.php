<?php

namespace DarkGhostHunter\RutUtils;

use ArrayAccess;
use BadMethodCallException;
use DarkGhostHunter\RutUtils\Exceptions\InvalidRutException;
use JsonSerializable;

/**
 * Class Rut
 *
 * @package DarkGhostHunter\RutUtils
 *
 * @property-read int $num
 * @property-read string|int $vd
 *
 * @method static string cleanRut(string $rut, bool $forceUppercase = true, bool $exception = true)
 * @method static bool validate(...$ruts)
 * @method static bool validateStrict(...$ruts)
 * @method static bool isEqual(...$ruts)
 * @method static array filter(...$ruts)
 * @method static Rut rectify(int $num)
 * @method static bool isPerson(string $rut)
 * @method static bool isCompany(string $rut)
 *
 * @method static array|Rut generate(int $iterations = 1, bool $unwrapSingle = true)
 * @method static RutBuilder unique()
 * @method static RutBuilder notUnique()
 * @method static RutBuilder asCompany()
 * @method static RutBuilder asPerson()
 * @method static RutBuilder asRaw()
 * @method static RutBuilder asString()
 * @method static RutBuilder asObject()
 */
class Rut implements ArrayAccess, JsonSerializable
{
    use HasHelperMethods;

    /**
     * If `K` should be treated as uppercase.
     *
     * @var bool
     */
    protected static $uppercase = true;

    /**
     * Should have thousand separator on string serialization
     *
     * @var string
     */
    protected static $format = 'full';

    /**
     * Attributes
     *
     * @var array
     */
    protected $attributes;

    /**
     * Rut constructor.
     *
     * @param string|null $rut
     * @param string|null $vd
     * @throws InvalidRutException
     */
    public function __construct(string $rut = null, $vd = null)
    {
        // First let's see if we have issued the RUT separately, meaning, the
        // first variable is numeric and the $vd is not null. If that's the
        // case, we will simply merge both as a string and add it parsed.
        if ($vd && is_numeric($rut)) {
            $rut .= $vd;
        }

        // Since we can instantiate this Rut class without arguments, like for
        // passing the method to the Rut Builder or Helpers, we need to check
        // if the $rut was passed to the constructor to add it inside here.
        if ($rut) $this->addRut($rut);
    }

    /**
     * Creates a new Rut instance
     *
     * @param mixed ...$ruts
     * @return Rut|array
     * @throws InvalidRutException
     */
    public static function make(...$ruts)
    {
        // Reset the array index to a sequential one.
        $ruts = array_values($ruts);

        // First check if the dev passed one single array instead multiple $ruts.
        // If that's the case, we will unwrap the array and use that. Otherwise,
        // we will use all the arguments as they come and transform each one.
        if (is_array($ruts[0]) && func_num_args() === 1) {
            $ruts = $ruts[0];
        }

        foreach ($ruts as &$rut) {
            $rut = new static($rut);
        }

        // If the resulting array is more than one, return all the array, otherwise
        // unpack the single value. This allows to match the arguments.
        return count($ruts) === 1 ? $ruts[0] : $ruts;
    }

    /**
     * Makes only Valid Ruts
     *
     * @param mixed ...$ruts
     * @return Rut|array
     * @throws InvalidRutException
     */
    public static function makeValid(...$ruts)
    {
        $ruts = self::make(...$ruts);

        $ruts = is_array($ruts) ? $ruts : [$ruts];

        foreach ($ruts as $rut) {
            if (!$rut->isValid()) {
                throw new InvalidRutException($rut);
            }
        }

        return count($ruts) === 1 ? $ruts[0] : $ruts;
    }

    /**
     * Adds a RUT to the instance
     *
     * @param string $rut
     * @return $this
     * @throws InvalidRutException
     */
    public function addRut(string $rut)
    {
        // Before adding a RUT to this instance, we need to separate the number
        // and the verification digit.
        [$this->attributes['num'], $this->attributes['vd']] = $this->separateRut($rut);

        return $this;
    }

    /**
     * Set all RUT to use uppercase `K`
     *
     * @return void
     */
    public static function allUppercase()
    {
        self::$uppercase = true;
    }

    /**
     * Set all RUT to use lowercase `K`
     *
     * @return void
     */
    public static function allLowercase()
    {
        self::$uppercase = false;
    }

    /**
     * Returns the raw string of the RUT
     *
     * @return string
     */
    public function toRawString()
    {
        return $this->num . $this->vd;
    }

    /**
     * Returns a formatted RUT string
     *
     * @return string
     */
    public function toFormattedString()
    {
        switch (self::$format) {
            case 'full':
                return number_format($this->num, 0, ',', '.') . '-' . $this->vd;
            case 'basic':
                return $this->num.'-'.$this->vd;
            case 'raw':
            default:
                return $this->num.$this->vd;
        }
    }

    /**
     * Return the object
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'num' => $this->num,
            'vd' => $this->vd,
        ];
    }

    /**
     * Forwards calls to the Helper or Builder
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (is_callable([RutHelper::class, $name])) {
            return RutHelper::{$name}(...$arguments);
        }

        if (is_callable([RutBuilder::class, $name])) {
            return (new RutBuilder)->{$name}(...$arguments);
        }

        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()', static::class, $name
        ));
    }

    /**
     * How to serialize as a string
     *
     * @example "full", "basic", "raw"
     * @param string $format
     */
    public static function setStringFormat(string $format)
    {
        switch ($format) {
            case 'raw':
                self::$format = 'raw';
                break;
            case 'basic':
                self::$format = 'basic';
                break;
            case 'full':
            default:
                self::$format = 'full';
                break;
        }
    }

    /**
     * How to format the RUT as a string
     *
     * @return string
     */
    public static function getStringFormat()
    {
        return self::$format;
    }



    /**
     * Returns a new static instance when calling static methods
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws InvalidRutException
     */
    public static function __callStatic($name, $arguments)
    {
        return (new static())->{$name}(...$arguments);
    }

    /**
     * Dynamically manage getting the RUT attributes
     *
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
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
     * Returns the RUT as a JSON string
     *
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
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
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Whether a offset exists
     *
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @param $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->attributes[$offset] ?? null;
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
}