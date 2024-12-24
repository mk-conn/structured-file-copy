<?php

declare(strict_types=1);

namespace MkConn\Sfc\Tests\Unit\Strategies;

use DI\DependencyException;
use DI\NotFoundException;
use MkConn\Sfc\Strategies\Copy\DateStrategy\YearStrategy;
use MkConn\Sfc\Strategies\StrategyPipeline;
use MkConn\Sfc\Tests\SfcTestCase;
use MkConn\Sfc\Tests\Unit\Strategies\Copy\MonthStrategyTest;

class StrategyPipelineTest extends SfcTestCase {
    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testPipeline(): void {
        $strategyPipeline = $this->container()->get(StrategyPipeline::class);

        $strategyPipeline->add(YearStrategy::class)->add(MonthStrategyTest::, $args);
    }
}
