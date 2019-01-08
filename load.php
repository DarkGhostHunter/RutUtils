<?php

spl_autoload_register(function ($class) {
    $class = lcfirst(substr(strrchr($class, '\\'), 1));

    if (!file_exists($file = __DIR__ . "/src/$class.php")) {
        include __DIR__ . "/src/Exceptions/$class.php";
    }

    include "$file";
});

include_once __DIR__ . '/helpers/helpers.php';