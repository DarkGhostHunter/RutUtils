<?php

namespace DarkGhostHunter\RutUtils;

use Closure;

trait HasCallbacks
{
    /**
     * Callbacks to execute after making many Ruts
     *
     * @var array
     */
    protected static $after = [];

    /**
     * Register a callback to be executed after making many Ruts
     *
     * @param  \Closure $callback
     */
    public static function after(Closure $callback)
    {
        static::$after[] = $callback;
    }

    /**
     * Returns all the Callbacks to be executed after making many Ruts
     *
     * @return array
     */
    public static function getAfterCallbacks()
    {
        return static::$after;
    }

    /**
     * Flushes all after and before callbacks
     *
     * @return void
     */
    public static function flushAfterCallbacks()
    {
        static::$after = [];
    }

    /**
     * Executes a closure without before and after callbacks
     *
     * @param  \Closure $closure
     */
    public static function withoutCallbacks(Closure $closure)
    {
        $after = static::$after;

        static::flushAfterCallbacks();

        $closure();

        static::$after = $after;
    }
}