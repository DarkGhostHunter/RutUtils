<?php

namespace DarkGhostHunter\RutUtils;

class RutBuilder
{
    /**
     * Window of randomness
     *
     * @var bool
     */
    protected $person = true;

    /**
     * What to output
     *
     * @example 'object', 'string', 'raw'
     * @var string
     */
    protected $output = 'object';

    /**
     * Hoy many RUTs to generate
     *
     * @var int
     */
    protected $iterations = 1;

    /**
     * Check if all random RUTs must be unique
     *
     * @var bool
     */
    protected $unique = false;

    /**
     * Random RUT floor
     *
     * @var int
     */
    protected $min;

    /**
     * Random RUT ceiling
     *
     * @var int
     */
    protected $max;

    /**
     * Generates a new random Rut object
     *
     * @param int $iterations
     * @param bool $unwrapSingle
     * @return array
     * @throws Exceptions\InvalidRutException
     */
    public function generate(int $iterations = 1, bool $unwrapSingle = true)
    {
        list ($this->min, $this->max) = $this->prepareMinMax();

        $this->iterations = $iterations = max(1, $iterations);

        $array = $this->performGenerate($iterations);

        if ($this->unique) {
            $array = $this->fillNonUniqueIterations($array);
        }

        return $iterations === 1 && $unwrapSingle ? $array[0] : $array;
    }

    /**
     * Performs the random generation of RUTs
     *
     * @param int $iterations
     * @return array
     * @throws Exceptions\InvalidRutException
     */
    protected function performGenerate(int $iterations)
    {
        switch ($this->output) {
            case 'raw':
                $array = $this->generateRaws($iterations, $this->min, $this->max);
                break;
            case 'string':
                $array = $this->generateStrings($iterations, $this->min, $this->max);
                break;
            case 'object':
            default:
                $array = $this->generateObjects($iterations, $this->min, $this->max);
                break;
        }

        return $array;
    }

    /**
     * Prepare the Min and Max numbers to generate
     *
     * @return array
     */
    protected function prepareMinMax()
    {
        $min = 1000000;
        $max = RutHelper::COMPANY_RUT_BASE;

        if (!$this->person) {
            $min = $max;
            $max = 100000000;
        }

        return [$min, $max];
    }

    /**
     * Generate a given number of random RUTs objects
     *
     * @param int $iterations
     * @param int $min
     * @param int $max
     * @return array
     * @throws Exceptions\InvalidRutException
     */
    protected function generateObjects(int $iterations, int $min, int $max)
    {
        $array = [];

        for ($i = 0; $i < $iterations; ++$i) {
            $array[] = RutHelper::rectify(rand($min, $max));
        }

        return $array;
    }

    /**
     * Generate a given number of random RUTs strings
     *
     * @param int $iterations
     * @param int $min
     * @param int $max
     * @return array
     */
    protected function generateRaws(int $iterations, int $min, int $max)
    {
        $array = [];

        for ($i = 0; $i < $iterations; ++$i) {
            $array[] = ($rut = rand($min, $max)) . RutHelper::getVd($rut);
        }

        return $array;
    }

    /**
     * Generates a given number of random RUTs formatted strings
     *
     * @param int $iterations
     * @param int $min
     * @param int $max
     * @return array
     */
    protected function generateStrings(int $iterations, int $min, int $max)
    {
        $array = [];

        for ($i = 0; $i < $iterations; ++$i) {
            $rut = rand($min, $max);
            $array[] = number_format($rut, 0, ',', '.') . '-' . RutHelper::getVd($rut);
        }

        return $array;
    }

    /**
     * Remove non unique values and replaces them with new ones
     *
     * @param array $array
     * @return array
     * @throws Exceptions\InvalidRutException
     */
    protected function fillNonUniqueIterations(array &$array)
    {
        $array = array_unique($array, SORT_REGULAR);

        while ($this->iterations > ($total = count($array))) {

            array_push($array, ...$this->performGenerate($this->iterations - $total));

            $array = array_unique($array, SORT_REGULAR);

        }

        return array_values($array);
    }

    /**
     * Generate unique RUTs
     *
     * @return $this
     */
    public function unique()
    {
        $this->unique = true;

        return $this;
    }

    /**
     * Generate RUTs that may be repeated
     *
     * @return $this
     */
    public function notUnique()
    {
        $this->unique = false;

        return $this;
    }

    /**
     * Return Companies RUTs
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
     * Return RUTs as raw strings
     *
     * @example '22605071K'
     * @return $this
     */
    public function asRaw()
    {
        $this->output = 'raw';

        return $this;
    }

    /**
     * Return RUTs as formatted strings
     *
     * @example '22.605.071-K'
     * @return $this
     */
    public function asString()
    {
        $this->output = 'string';

        return $this;
    }

    /**
     * Return RUTs as Rut object
     *
     * @return $this
     */
    public function asObject()
    {
        $this->output = 'object';

        return $this;
    }

}