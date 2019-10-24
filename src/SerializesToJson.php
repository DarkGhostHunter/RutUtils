<?php

namespace DarkGhostHunter\RutUtils;

trait SerializesToJson
{
    /**
     * How to transform to an array
     *
     * @var boolean
     */
    protected static $globalJsonFromArray = true;

    /**
     * The format when transforming to an array for this Rut instance
     *
     * @var boolean|null
     */
    protected $jsonAsArray;

    /**
     * Sets the format for the array
     *
     * @return void
     */
    public static function allJsonAsArray()
    {
        static::$globalJsonFromArray = true;
    }

    /**
     * Sets the JSON format to an array
     *
     * @return void
     */
    public static function allJsonAsString()
    {
        static::$globalJsonFromArray = false;
    }

    /**
     * Forces this instance to
     *
     * @return $this
     */
    public function jsonAsArray()
    {
        $this->jsonAsArray = true;

        return $this;
    }

    /**
     * Forces this instance to
     *
     * @return $this
     */
    public function jsonAsString()
    {
        $this->jsonAsArray = false;

        return $this;
    }

    /**
     * If the instance should be casted as a single JSON string or multiple
     *
     * @return null|bool
     */
    protected function shouldArray()
    {
        return $this->jsonAsArray !== null ? $this->jsonAsArray : self::$globalJsonFromArray;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return string|array
     */
    public function jsonSerialize()
    {
        return $this->shouldArray() ? (string)$this : $this->toArray();
    }
}