<?php

declare(strict_types=1);

namespace MkConn\Sfc\Tests;

use DI\Container as DIContainer;
use MkConn\Sfc\DI\Container;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class SfcTestCase extends PHPUnitTestCase {
    private static DIContainer $container;

    public static function setUpBeforeClass(): void {
        parent::setUpBeforeClass();

        self::$container = Container::getInstance();
    }

    protected function container(): DIContainer {
        return self::$container;
    }
}
