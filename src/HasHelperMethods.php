<?php

namespace DarkGhostHunter\RutUtils;

trait HasHelperMethods
{
    /**
     * Separate a RUT string into an array with the number and verification digit
     *
     * @param string $rut
     * @return array
     */
    protected function separateRut(string $rut)
    {
        return RutHelper::separateRut($rut, static::$globalUppercase);
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
     * Returns if the present RUT instance is invalid
     *
     * @return bool
     */
    public function isInvalid()
    {
        return ! $this->isValid();
    }

    /**
     * Return if the RUT is for a Person
     *
     * @return bool
     */
    public function isPerson()
    {
        return RutHelper::isPerson($this);
    }

    /**
     * Return if the RUT is for a Company
     *
     * @return bool
     */
    public function isCompany()
    {
        return !$this->isPerson();
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