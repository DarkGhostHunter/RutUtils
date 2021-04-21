<?php

namespace DarkGhostHunter\RutUtils;

trait SerializesToJson
{
    /**
     * How to transform to an array.
     *
     * @var boolean
     */
    protected static bool $globalJsonFromArray = false;

    /**
     * The format when transforming to an array for this Rut instance.
     *
     * @var boolean|null
     */
    protected ?bool $jsonAsArray;

    /**
     * Sets the format for the array.
     *
     * @return void
     */
    public static function allJsonAsArray(): void
    {
        static::$globalJsonFromArray = true;
    }

    /**
     * Sets the JSON format to an array.
     *
     * @return void
     */
    public static function allJsonAsString(): void
    {
        static::$globalJsonFromArray = false;
    }

    /**
     * Forces this instance to transform into a JSON array.
     *
     * @return $this
     */
    public function jsonAsArray(): Rut
    {
        $this->jsonAsArray = true;

        return $this;
    }

    /**
     * Forces this instance to transform as a JSON single string.
     *
     * @return $this
     */
    public function jsonAsString(): Rut
    {
        $this->jsonAsArray = false;

        return $this;
    }

    /**
     * If the instance should be casted as a single JSON string or multiple
     *
     * @return null|bool
     */
    public function shouldJsonAsArray(): ?bool
    {
        return $this->jsonAsArray ?? static::$globalJsonFromArray;
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