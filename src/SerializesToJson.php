<?php

namespace DarkGhostHunter\RutUtils;

trait SerializesToJson
{
    /**
     * How to transform to an array
     *
     * @var boolean
     */
    protected static $globalJsonFromArray = false;

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
    public function shouldJsonAsArray()
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
        return $this->shouldJsonAsArray() ? $this->toArray() : (string)$this;
    }
}