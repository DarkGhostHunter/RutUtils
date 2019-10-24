<?php

namespace DarkGhostHunter\RutUtils;

trait HasFormats
{
    /**
     * Global Uppercase for all Rut instances.
     *
     * @var bool
     */
    protected static $globalUppercase = true;

    /**
     * Global Format for all Rut instances.
     *
     * @var string
     */
    protected static $globalFormat = 'strict';

    /**
     * If `K` should be treated as uppercase.
     *
     * @var bool|null
     */
    protected $uppercase;

    /**
     * Should have thousand separator on string serialization.
     *
     * @var string|null
     */
    protected $format;

    /**
     * Return the global string format
     *
     * @return string
     */
    public static function getGlobalUppercase()
    {
        return self::$globalUppercase;
    }

    /**
     * Return the global string format
     *
     * @return string
     */
    public static function getGlobalFormat()
    {
        return self::$globalFormat;
    }

    /**
     * Set all RUT to use uppercase `K`
     *
     * @return void
     */
    public static function allUppercase()
    {
        self::$globalUppercase = true;
    }

    /**
     * Set all RUT to use lowercase `K`
     *
     * @return void
     */
    public static function allLowercase()
    {
        self::$globalUppercase = false;
    }

    /**
     * Changes the default format to strict for all Rut instances.
     *
     * @return void
     */
    public static function allFormatStrict()
    {
        static::$globalFormat = self::FORMAT_STRICT;
    }

    /**
     * Changes the default format to strict for all Rut instances.
     *
     * @return void
     */
    public static function allFormatBasic()
    {
        static::$globalFormat = self::FORMAT_BASIC;
    }

    /**
     * Changes the default format to raw for all Rut instances.
     *
     * @return void
     */
    public static function allFormatRaw()
    {
        static::$globalFormat = self::FORMAT_RAW;
    }

    /**
     * Return the current uppercase configuration for this Rut instance.
     *
     * @return bool
     */
    protected function shouldUppercase()
    {
        return $this->uppercase !== null ? $this->uppercase : self::$globalUppercase;
    }

    /**
     * Return the current uppercase configuration for this Rut instance.
     *
     * @return bool
     */
    public function getFormat()
    {
        return $this->format ?? static::$globalFormat;
    }

    /**
     * Set all RUT to use uppercase `K`
     *
     * @return \DarkGhostHunter\RutUtils\Rut
     */
    public function uppercase()
    {
        $this->uppercase = true;

        return $this;
    }

    /**
     * Set all RUT to use lowercase `K`
     *
     * @return \DarkGhostHunter\RutUtils\Rut
     */
    public function lowercase()
    {
        $this->uppercase = false;

        return $this;
    }

    /**
     * Sets the format to use for the current Rut instance.
     *
     * @param  string $format
     */
    public function setFormat(string $format)
    {
        $this->format = $format;
    }

    /**
     * Returns the RUT as an strictly formatted string
     *
     * @return string
     * @example 18.765.432-1
     */
    public function toStrictString()
    {
        return number_format((int)$this->rut['num'], 0, ',', '.') . '-' . $this->rut['vd'];
    }

    /**
     * Returns the RUT as a basic formatted string
     *
     * @return string
     * @example 18765432-1
     */
    public function toBasicString()
    {
        return (int)$this->rut['num'] . '-' . $this->rut['vd'];
    }

    /**
     * Returns the RUT as a raw formatted string
     *
     * @return string
     * @example 187654321
     */
    public function toRawString()
    {
        return (int)$this->rut['num'] . $this->rut['vd'];
    }

    /**
     * Returns a formatted RUT string
     *
     * @return string
     */
    public function toFormattedString()
    {
        switch ($this->format ?? static::$globalFormat) {
            case static::FORMAT_STRICT:
                return $this->toStrictString();
            case static::FORMAT_BASIC:
                return $this->toBasicString();
            case static::FORMAT_RAW:
            default:
                return $this->toRawString();
        }
    }

}