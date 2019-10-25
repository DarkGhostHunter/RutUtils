<?php

namespace DarkGhostHunter\RutUtils;

trait HasFormats
{

    /**
     * Global Format for all Rut instances.
     *
     * @var string
     */
    protected static $globalFormat = 'strict';

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
    public static function getGlobalFormat()
    {
        return self::$globalFormat;
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
    public function getFormat()
    {
        return $this->format ?? static::$globalFormat;
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
        $num = number_format((int)$this->rut['num'], 0, ',', '.');
        $vd = $this->shouldUppercase() ? strtoupper($this->rut['vd']) : strtolower($this->rut['vd']);

        return $num . '-' .$vd;
    }

    /**
     * Returns the RUT as a basic formatted string
     *
     * @return string
     * @example 18765432-1
     */
    public function toBasicString()
    {
        $vd = $this->shouldUppercase() ? strtoupper($this->rut['vd']) : strtolower($this->rut['vd']);

        return (int)$this->rut['num'] . '-' . $vd;
    }

    /**
     * Returns the RUT as a raw formatted string
     *
     * @return string
     * @example 187654321
     */
    public function toRawString()
    {
        $vd = $this->shouldUppercase() ? strtoupper($this->rut['vd']) : strtolower($this->rut['vd']);

        return (int)$this->rut['num'] . $vd;
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