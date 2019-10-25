<?php

namespace DarkGhostHunter\RutUtils;

trait HasCase
{
    /**
     * Global Uppercase for all Rut instances.
     *
     * @var bool
     */
    protected static $globalUppercase = true;

    /**
     * If `K` should be treated as uppercase.
     *
     * @var bool|null
     */
    protected $uppercase;

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
     * Return the current uppercase configuration for this Rut instance.
     *
     * @return bool
     */
    protected function shouldUppercase()
    {
        return $this->uppercase !== null ? $this->uppercase : self::$globalUppercase;
    }

    /**
     * Case the verification digit to uppercase or lowercase
     *
     * @param  string $vd
     * @return string
     */
    protected function case(string $vd)
    {
        return $this->shouldUppercase() ? strtoupper($vd) : strtolower($vd);
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
     * Set all RUT to use uppercase `K`
     *
     * @return \DarkGhostHunter\RutUtils\Rut
     */
    public function uppercase()
    {
        $this->uppercase = true;

        return $this;
    }
}