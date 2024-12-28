<?php

declare(strict_types=1);

namespace Tests;

use DI\Container as DIContainer;
use DI\DependencyException;
use DI\NotFoundException;
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

    /**
     * @template T
     *
     * @param class-string<T> $key
     *
     * @return mixed|T
     */
    protected function fromContainer(string $key): mixed {
        try {
            return $this->container()->get($key);
        } catch (DependencyException|NotFoundException $e) {
            self::fail($e->getMessage());
        }
    }
}
