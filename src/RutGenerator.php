<?php

namespace DarkGhostHunter\RutUtils;

class RutGenerator
{
    use BuildsGenerator;

    /**
     * Minimum constraint to generate RUTs
     *
     * @const int
     */
    public const MINIMUM_NUMBER = 1000000;

    /**
     * Maximum constraint to generate RUTs
     *
     * @const int
     */
    public const MAXIMUM_NUMBER = 100000000;

    /**
     * Static array for handling static generation
     *
     * @var array
     */
    protected static $static = [];

    /**
     * How many RUTs to generate.
     *
     * @var int
     */
    protected $iterations = 1;

    /**
     * Random RUT floor.
     *
     * @var int
     */
    protected $min;

    /**
     * Random RUT ceiling.
     *
     * @var int
     */
    protected $max;

    /**
     * Generates a new random Rut object or an array of them.
     *
     * @param int $iterations
     * @param bool $unwrapSingle
     * @return array|Rut
     */
    public function generate(int $iterations = 1, bool $unwrapSingle = true)
    {
        [$this->min, $this->max] = $this->prepareMinMax();

        $this->iterations = $iterations = max(1, $iterations);

        $array = $this->performGenerate($iterations);

        if ($this->unique) {
            $array = $this->fillNonUniqueIterations($array);
        }

        return $iterations === 1 && $unwrapSingle ? $array[0] : $array;
    }

    /**
     * Generates one unique result by checking an internal static array
     *
     * @return array|\DarkGhostHunter\RutUtils\Rut
     */
    public function generateStatic()
    {
        do {
            $result = $this->generate();
        } while (in_array($result, static::$static, true));

        static::$static[] = $result;

        return $result;
    }

    /**
     * Flushes the static Ruts saved for generation
     *
     * @return $this
     */
    public function flushStatic()
    {
        static::$static = [];

        return $this;
    }

    /**
     * Performs the random generation of RUTs by the given iterations.
     *
     * @param int $iterations
     * @return array
     */
    protected function performGenerate(int $iterations)
    {
        switch ($this->output) {
            case Rut::FORMAT_RAW:
                $array = $this->generateRaw($iterations, $this->min, $this->max);
                break;
            case Rut::FORMAT_BASIC:
                $array = $this->generateBasic($iterations, $this->min, $this->max);
                break;
            case Rut::FORMAT_STRICT:
                $array = $this->generateStrict($iterations, $this->min, $this->max);
                break;
            case 'object':
            default:
                $array = $this->generateObjects($iterations, $this->min, $this->max);
                break;
        }

        return $array;
    }

    /**
     * Prepare the minimum and maximum RUT numbers to generate.
     *
     * @return array
     */
    protected function prepareMinMax()
    {
        return $this->person
            ? [ static::MINIMUM_NUMBER, Rut::COMPANY_RUT_BASE ]
            : [ Rut::COMPANY_RUT_BASE, static::MAXIMUM_NUMBER];
    }

    /**
     * Generates a given number of random RUTs as strictly formatted strings.
     *
     * @param int $iterations
     * @param int $min
     * @param int $max
     * @return array
     */
    protected function generateStrict(int $iterations, int $min, int $max)
    {
        $array = [];

        for ($i = 0; $i < $iterations; ++$i) {
            $rut = rand($min, $max);
            $array[] = number_format($rut, 0, ',', '.') . '-' . RutHelper::getVd($rut);
        }

        return $array;
    }

    /**
     * Generates a given number of random RUTs as basic formatted strings.
     *
     * @param int $iterations
     * @param int $min
     * @param int $max
     * @return array
     */
    protected function generateBasic(int $iterations, int $min, int $max)
    {
        $array = [];

        for ($i = 0; $i < $iterations; ++$i) {
            $rut = rand($min, $max);
            $array[] = $rut . '-' . RutHelper::getVd($rut);
        }

        return $array;
    }

    /**
     * Generate a given number of random RUTs as raw strings.
     *
     * @param int $iterations
     * @param int $min
     * @param int $max
     * @return array
     */
    protected function generateRaw(int $iterations, int $min, int $max)
    {
        $array = [];

        for ($i = 0; $i < $iterations; ++$i) {
            $array[] = ($rut = rand($min, $max)) . RutHelper::getVd($rut);
        }

        return $array;
    }

    /**
     * Generate a given number of random RUTs as Rut instances.
     *
     * @param int $iterations
     * @param int $min
     * @param int $max
     * @return array
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
     * Remove non unique values and replaces them with new ones
     *
     * We use this method because it's less resource-heavy to make 100 RUTs,
     * filter the ones repeated, count how many are left to reach the quota,
     * generate the remaining, and repeat until there is nothing to generate,
     * instead of checking each one if it's repeated on the resulting array.
     *
     * @param array $array
     * @return array
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
     * Creates a new Rut Generator instance
     *
     * @return static
     */
    public static function make()
    {
        return new static;
    }
}