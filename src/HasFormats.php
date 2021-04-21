<?php

namespace DarkGhostHunter\RutUtils;

trait HasFormats
{

    /**
     * Global Format for all Rut instances.
     *
     * @var string
     */
    protected static string $globalFormat = 'strict';

    /**
     * Should have thousand separator on string serialization.
     *
     * @var string|null
     */
    protected ?string $format;

    /**
     * Return the global string format
     *
     * @return string
     */
    public static function getGlobalFormat(): string
    {
        return self::$globalFormat;
    }

    /**
     * Changes the default format to strict for all Rut instances.
     *
     * @return void
     */
    public static function allFormatStrict(): void
    {
        static::$globalFormat = self::FORMAT_STRICT;
    }

    /**
     * Changes the default format to strict for all Rut instances.
     *
     * @return void
     */
    public static function allFormatBasic(): void
    {
        static::$globalFormat = self::FORMAT_BASIC;
    }

    /**
     * Changes the default format to raw for all Rut instances.
     *
     * @return void
     */
    public static function allFormatRaw(): void
    {
        static::$globalFormat = self::FORMAT_RAW;
    }

    /**
     * Return the current uppercase configuration for this Rut instance.
     *
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format ?? static::$globalFormat;
    }

    /**
     * Sets the format to use for the current Rut instance.
     *
     * @param  string $format
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    /**
     * Returns the RUT as an strictly formatted string
     *
     * @return string
     * @example 18.765.432-1
     */
    public function toStrictString(): string
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
    public function toBasicString(): string
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
    public function toRawString(): string
    {
        $vd = $this->shouldUppercase() ? strtoupper($this->rut['vd']) : strtolower($this->rut['vd']);

        return (int)$this->rut['num'] . $vd;
    }

    /**
     * Returns a formatted RUT string
     *
     * @return string
     */
    public function toFormattedString(): string
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