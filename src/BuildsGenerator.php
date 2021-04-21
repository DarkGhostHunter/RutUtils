<?php

namespace DarkGhostHunter\RutUtils;

trait BuildsGenerator
{
    /**
     * Check if all random RUTs must be unique, or it can have duplicates
     *
     * @var bool
     */
    protected bool $unique = false;

    /**
     * Window of RUT randomness
     *
     * @var bool
     */
    protected bool $person = true;

    /**
     * What type to output
     *
     * @var string
     */
    protected string $output = 'object';

    /**
     * Generate unique RUTs
     *
     * @return static
     */
    public function withoutDuplicates(): RutGenerator
    {
        $this->unique = true;

        return $this;
    }

    /**
     * Generate RUTs that may be duplicated
     *
     * @return static
     */
    public function withDuplicates(): RutGenerator
    {
        $this->unique = false;

        return $this;
    }

    /**
     * Return companies RUTs
     *
     * @return static
     */
    public function asCompany(): RutGenerator
    {
        $this->person = false;

        return $this;
    }

    /**
     * Return Persons RUTs
     *
     * @return static
     */
    public function asPerson(): RutGenerator
    {
        $this->person = true;

        return $this;
    }

    /**
     * Return RUTs as strict strings
     *
     * @return static
     * @example '22.605.071-K'
     */
    public function asStrict(): RutGenerator
    {
        $this->output = Rut::FORMAT_STRICT;

        return $this;
    }

    /**
     * Return RUTs as basic strings
     *
     * @return static
     * @example '22605071-K'
     */
    public function asBasic(): RutGenerator
    {
        $this->output = Rut::FORMAT_BASIC;

        return $this;
    }

    /**
     * Return RUTs as raw strings
     *
     * @return static
     * @example '22605071K'
     */
    public function asRaw(): RutGenerator
    {
        $this->output = Rut::FORMAT_RAW;

        return $this;
    }

    /**
     * Return RUTs as Rut object instances
     *
     * @return static
     */
    public function asObject(): RutGenerator
    {
        $this->output = 'object';

        return $this;
    }
}