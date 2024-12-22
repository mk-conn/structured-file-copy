<?php

declare(strict_types=1);

namespace MkConn\Sfc\DI;

class Container {
    private static \DI\Container $container;

    public static function getInstance(): \DI\Container {
        if (!isset(self::$container)) {
            self::$container = require_once __DIR__ . '/../../bootstrap/container.php';
        }

        return self::$container;
    }
}
