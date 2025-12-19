<?php

declare(strict_types=1);

namespace Tests\Unit\Factories;

use MkConn\Sfc\Enums\FilterType;
use MkConn\Sfc\Factories\FileFinderFactory;
use MkConn\Sfc\Factories\FinderFactory;
use MkConn\Sfc\Models\Filter;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Mime\MimeTypesInterface;
use Tests\SfcTestCase;

final class FileFinderFactoryTest extends SfcTestCase {
    public function testCreatesFinderWithIncludeFilters(): void {
        $finder = $this->finder();
        $finder->expects(self::exactly(3))->method('name')->with(self::callback(function (mixed $input) {
            return match ($input) {
                '*.php', 'FileFinderFactory' => true,
                default => is_array($input) && array_all($input, fn ($regex) => str_starts_with($regex, '/\.') && str_ends_with($regex, '$/i')),
            };
        }));
        $finder->expects(self::once())->method('size')->with('>1');

        $mimeTypes = $this->createMock(MimeTypesInterface::class);
        $mimeTypes->method('getExtensions')->willReturnCallback(function ($mimeType) {
            return match ($mimeType) {
                'image/bmp'  => ['bmp'],
                'image/gif'  => ['gif'],
                'image/jpeg' => ['jpg', 'jpeg'],
                'image/png'  => ['png'],
                default      => [],
            };
        });

        $filters = [
            new Filter(FilterType::EXT, 'php'),
            new Filter(FilterType::NAME, 'FileFinderFactory'),
            new Filter(FilterType::FILE_TYPE, 'image'),
            new Filter(FilterType::SIZE, '>1'),
        ];

        $factory = new FileFinderFactory($mimeTypes, $this->finderFactory($finder));
        $factory->create(__DIR__, $filters);
    }

    public function testCreatesFinderWithExcludeFilters(): void {
        $finder = $this->finder();
        $finder->expects(self::exactly(3))->method('notName')->with(self::callback(function (mixed $input) {
            return match ($input) {
                '*.md', 'hotzenplotz.pdf' => true,
                default           => is_array($input) && array_all($input, fn ($regex) => str_starts_with($regex, '/\.') && str_ends_with($regex, '$/i')),
            };
        }));
        $finder->expects(self::once())->method('exclude')->with('notfound');

        $excludes = [
            new Filter(FilterType::EXT, 'md'),
            new Filter(FilterType::NAME, 'hotzenplotz.pdf'),
            new Filter(FilterType::DIRECTORY, 'notfound'),
            new Filter(FilterType::FILE_TYPE, 'image'),

        ];

        $factory = new FileFinderFactory(new MimeTypes(), $this->finderFactory($finder));
        $factory->create(__DIR__, [], $excludes);
    }

    public function testHandlesEmptyFilters(): void {
        $finder = $this->finder();
        $finder->expects(self::once())->method('in')->with(__DIR__);

        $factory = new FileFinderFactory(new MimeTypes(), $this->finderFactory($finder));
        $factory->create(__DIR__);
    }

    private function finder(): MockObject&Finder {
        $finder = $this->createMock(Finder::class);
        $finder->method('in')->willReturnSelf();
        $finder->method('name')->willReturnSelf();
        $finder->method('size')->willReturnSelf();
        $finder->method('notName')->willReturnSelf();
        $finder->method('exclude')->willReturnSelf();

        return $finder;
    }

    private function finderFactory(MockObject&Finder $finder): MockObject&FinderFactory {
        $finderFactory = $this->createMock(FinderFactory::class);
        $finderFactory->method('create')->willReturn($finder);

        return $finderFactory;
    }
}
