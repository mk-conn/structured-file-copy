<?php

declare(strict_types=1);

namespace Tests\Unit\Strategies;

use MkConn\Sfc\Strategies\Copy\CopyStrategyInterface;
use MkConn\Sfc\Strategies\StrategyPipeline;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Finder\Finder;
use Tests\SfcTestCase;

class StrategyPipelineTest extends SfcTestCase {
    /**
     * @throws Exception
     */
    public function testRun(): void {
        $yearStrategy = $this->createMock(CopyStrategyInterface::class);
        $monthStrategy = $this->createMock(CopyStrategyInterface::class);

        $yearStrategy->expects(self::once())
                     ->method('withNextStrategy')
                     ->with($monthStrategy)
                     ->willReturnSelf();

        $monthStrategy->expects(self::never())
                      ->method('withNextStrategy');

        $yearStrategy->expects(self::once())
                     ->method('collectFiles');

        $files = $this->createMock(Finder::class);

        $strategyPipeline = new StrategyPipeline();
        $strategyPipeline->run([$yearStrategy, $monthStrategy], $files, '/target');
    }
}
