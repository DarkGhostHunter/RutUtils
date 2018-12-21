<?php

namespace DarkGhostHunter\RutUtils;

trait HasHelperMethods
{
    /**
     * Separate a RUT string into
     *
     * @param string $rut
     * @return array
     * @throws Exceptions\InvalidRutException
     */
    protected function separateRut(string $rut)
    {
        return RutHelper::separateRut($rut, self::$uppercase);
    }

    /**
     * Returns if the present RUT is valid
     *
     * @return bool
     * @throws Exceptions\InvalidRutException
     */
    public function isValid()
    {
        return RutHelper::validate($this);
    }

    /**
     * Return if the RUT is for a Person
     *
     * @return bool
     * @throws Exceptions\InvalidRutException
     */
    public function person()
    {
        return RutHelper::isPerson($this);
    }

    /**
     * Return if the RUT is for a Company
     *
     * @return bool
     * @throws Exceptions\InvalidRutException
     */
    public function company()
    {
        return !$this->person();
    }

    /**
     * Return if the RUT is equal to another RUT
     *
     * @param string $rut
     * @return bool
     * @throws Exceptions\InvalidRutException
     */
    public function isEqualTo(string $rut)
    {
        return RutHelper::areEqual($this, $rut);
    }
}