<?php

namespace DarkGhostHunter\RutUtils;

trait BuildsGenerator
{
    /**
     * Check if all random RUTs must be unique, or it can have duplicates
     *
     * @var bool
     */
    protected $unique = false;

    /**
     * Window of RUT randomness
     *
     * @var bool
     */
    protected $person = true;

    /**
     * What type to output
     *
     * @var string
     */
    protected $output = 'object';

    /**
     * Generate unique RUTs
     *
     * @return $this
     */
    public function withoutDuplicates()
    {
        $this->unique = true;

        return $this;
    }

    /**
     * Generate RUTs that may be duplicated
     *
     * @return $this
     */
    public function withDuplicates()
    {
        $this->unique = false;

        return $this;
    }

    /**
     * Return companies RUTs
     *
     * @return $this
     */
    public function asCompany()
    {
        $this->person = false;

        return $this;
    }

    /**
     * Return Persons RUTs
     *
     * @return $this
     */
    public function asPerson()
    {
        $this->person = true;

        return $this;
    }

    /**
     * Return RUTs as strict strings
     *
     * @example '22.605.071-K'
     * @return $this
     */
    public function asStrict()
    {
        $this->output = Rut::FORMAT_STRICT;

        return $this;
    }

    /**
     * Return RUTs as basic strings
     *
     * @example '22605071-K'
     * @return $this
     */
    public function asBasic()
    {
        $this->output = Rut::FORMAT_BASIC;

        return $this;
    }

    /**
     * Return RUTs as raw strings
     *
     * @example '22605071K'
     * @return $this
     */
    public function asRaw()
    {
        $this->output = Rut::FORMAT_RAW;

        return $this;
    }

    /**
     * Return RUTs as Rut object instances
     *
     * @return $this
     */
    public function asObject()
    {
        $this->output = 'object';

        return $this;
    }
}