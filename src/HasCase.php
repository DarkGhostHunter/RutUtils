<?php

namespace DarkGhostHunter\RutUtils;

trait HasCase
{
    /**
     * Global Uppercase for all Rut instances.
     *
     * @var bool
     */
    protected static bool $globalUppercase = true;

    /**
     * If `K` should be treated as uppercase.
     *
     * @var bool|null
     */
    protected ?bool $uppercase;

    /**
     * Return the global string format
     *
     * @return bool
     */
    public static function getGlobalUppercase(): bool
    {
        return self::$globalUppercase;
    }

    /**
     * Set all RUT to use uppercase `K`
     *
     * @return void
     */
    public static function allUppercase(): void
    {
        self::$globalUppercase = true;
    }

    /**
     * Set all RUT to use lowercase `K`
     *
     * @return void
     */
    public static function allLowercase(): void
    {
        self::$globalUppercase = false;
    }

    /**
     * Return the current uppercase configuration for this Rut instance.
     *
     * @return bool
     */
    protected function shouldUppercase(): bool
    {
        return $this->uppercase ?? self::$globalUppercase;
    }

    /**
     * Case the verification digit to uppercase or lowercase
     *
     * @param  string $vd
     * @return string
     */
    protected function case(string $vd): string
    {
        return $this->shouldUppercase() ? strtoupper($vd) : strtolower($vd);
    }

    /**
     * Set all RUT to use lowercase `K`
     *
     * @return static
     */
    public function lowercase(): Rut
    {
        $this->uppercase = false;

        return $this;
    }

    /**
     * Set all RUT to use uppercase `K`
     *
     * @return static
     */
    public function uppercase(): Rut
    {
        $this->uppercase = true;

        return $this;
    }
}