<?php

namespace DarkGhostHunter\RutUtils;

trait HasHelpers
{
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
     * @param  mixed ...$ruts
     * @return bool
     */
    public function isEqualTo(...$ruts)
    {
        return RutHelper::isEqual(...RutHelper::unpack($ruts) + [$this]);
    }
}