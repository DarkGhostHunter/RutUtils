<?php

namespace DarkGhostHunter\RutUtils;

trait HasHelpers
{
    /**
     * Returns if the present RUT is valid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return RutHelper::validate($this);
    }

    /**
     * Returns if the present RUT instance is invalid
     *
     * @return bool
     */
    public function isInvalid(): bool
    {
        return ! $this->isValid();
    }

    /**
     * Return if the RUT is for a Person
     *
     * @return bool
     */
    public function isPerson(): bool
    {
        return RutHelper::isPerson($this);
    }

    /**
     * Return if the RUT is for a Company
     *
     * @return bool
     */
    public function isCompany(): bool
    {
        return !$this->isPerson();
    }

    /**
     * Return if the RUT is equal to another RUT
     *
     * @param  mixed ...$ruts
     * @return bool
     */
    public function isEqual(...$ruts): bool
    {
        $ruts = array_filter(array_merge(RutHelper::unpack($ruts), [$this]));

        // Bail if after filtering RUTs we end up with only this instance.
        if (count($ruts) < 2) {
            return false;
        }

        return RutHelper::isEqual($ruts);
    }
}