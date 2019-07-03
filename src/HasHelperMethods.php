<?php

namespace DarkGhostHunter\RutUtils;

trait HasHelperMethods
{
    /**
     * Separate a RUT string into the RUT number and RUT verification digit
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
     */
    public function isEqualTo(string $rut)
    {
        return RutHelper::isEqual($this, $rut);
    }
}