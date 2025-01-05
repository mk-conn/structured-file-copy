<?php

declare(strict_types=1);

namespace MkConn\Sfc\Commands;

use Exception;
use MkConn\Sfc\Enums\FilterType;
use MkConn\Sfc\Factories\CopyOptionsFactory;
use MkConn\Sfc\Models\Filter;
use MkConn\Sfc\Services\CopyService;
use MkConn\Sfc\Services\FileService\FileTypes;
use MkConn\Sfc\Strategies\AvailableStrategies;
use MkConn\Sfc\Strategies\WithStrategyOptionsInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'copy', description: 'Copies files from a source folder to a target folder in a structured way')]
class CopyCommand extends Command {
    public function __construct(
        private readonly AvailableStrategies $availableStrategies,
        private readonly CopyOptionsFactory $copyOptionsFactory,
        private readonly CopyService $copyService
    ) {
        parent::__construct();
    }

    protected function configure(): void {
        $strategies = $this->availableStrategies->getStrategies();
        $sortOptions = $strategies->keys()->toArray();
        $fileTypes = FileTypes::getFileTypes();

        $this->addOption(
            'source',
            null,
            InputOption::VALUE_OPTIONAL,
            'The source folder (if not set, files are taken from the folder where the command is executed)'
        );
        $this->addOption('target', null, InputOption::VALUE_REQUIRED, 'The target folder where the files will be copied');
        $this->addOption(
            'sort',
            null,
            InputOption::VALUE_OPTIONAL,
            'Sort by strategies: <comment>[' . implode('|', $sortOptions) . ']</comment>. ' . PHP_EOL .
            'E.g., if you want to sort by date (year) and then by by letter, you can use: <comment>--sort-by=by:date:year,by:letter</comment>.' . PHP_EOL .
            'This will create a folder for each year and then a folder for each letter.' . PHP_EOL,
            'by:default'
        );
        $this->addOption(
            'include',
            'i',
            InputOption::VALUE_OPTIONAL,
            'Filter files by <comment>[ext,type]</comment>. E.g., <comment>--filter=ext:jpg,ext:png,ext:gif,ext:heic</comment> or <comment>--filter=type:image</comment>' . PHP_EOL .
            'Available types: <comment>[' . implode('|', $fileTypes) . ']</comment>'
        );
        $this->addOption(
            'exclude',
            'e',
            InputOption::VALUE_OPTIONAL, 'Exclude files by <comment>[ext,type]</comment>. E.g., <comment>--exclude=jpg,png,gif</comment> or <comment>--exclude=type:image</comment>' . PHP_EOL .
            'Available types: <comment>[' . implode('|', $fileTypes) . ']</comment>'
        );

        foreach ($sortOptions as $sortOption) {
            $strategy = $strategies->get($sortOption);

            if ($strategy instanceof WithStrategyOptionsInterface) {
                $availableOptions = $strategy->availableOptions();

                if ($availableOptions) {
                    foreach ($availableOptions as $availableOption => $info) {
                        $this->addOption(
                            $availableOption,
                            null,
                            InputOption::VALUE_OPTIONAL,
                            "When <comment>$sortOption</comment> is used: {$info['description']}",
                            $info['default'] ?? null
                        );
                    }
                }
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);
        $source = realpath($input->getOption('source') ?: getcwd());
        $target = $input->getOption('target');

        if (!$source) {
            $output->writeln('<error>Source folder does not exist</error>');

            return Command::FAILURE;
        }

        if (!file_exists(dirname($target))) {
            $output->writeln('<error>Target not writable</error>');

            return Command::FAILURE;
        }

        try {
            $io->title('Copying files from source to target');
            $io->block([
                'Source: ' . $source,
                'Target: ' . $target,
            ]);

            if ($io->confirm('Let\'s do this?', false)) {
                $included = $this->reduceIncludesOrExcludes($input->getOption('include') ?? '');
                $excluded = $this->reduceIncludesOrExcludes($input->getOption('exclude') ?? '');

                $sortBy = $input->getOption('sort');
                $sortBy = $sortBy ? explode(',', $sortBy) : [];
                $strategyOptions = $input->getOptions();

                $options = $this->copyOptionsFactory->create($source, $target, $sortBy, $strategyOptions, $included, $excluded);

                $journal = $this->copyService->copy($options, $output);

                $io->success([
                    'Copied files: ' . count($journal->copiedFiles()),
                    'Uncopied files: ' . count($journal->uncopiedFiles()),
                ]);

                return Command::SUCCESS;
            }
        } catch (Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
        }

        return Command::FAILURE;
    }

    /**
     * @return array<Filter>
     */
    private function reduceIncludesOrExcludes(string $inputData = ''): array {
        $inputData = $inputData ? explode(',', $inputData) : [];

        return array_reduce($inputData, function ($carry, $input) {
            $input = explode(':', $input);
            $filterType = FilterType::fromString($input[0]);
            $filter = new Filter($filterType, $input[1]);
            $carry[] = $filter;

            return $carry;
        }) ?? [];
    }
}
