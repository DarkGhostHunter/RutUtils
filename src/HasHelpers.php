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
    public function isEqual(...$ruts)
    {
        $ruts = array_filter(array_merge(RutHelper::unpack($ruts), [$this]));

        // Bail if after filtering RUTs we end up with only this instance.
        if (count($ruts) < 2) {
            return false;
        }

        return RutHelper::isEqual($ruts);
    }
}