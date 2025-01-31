<?php

declare(strict_types=1);

namespace MkConn\Sfc\Commands;

use MkConn\Sfc\Services\FileService\FileTypes;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'file-types', description: 'Show the file types')]
class ShowFileTypesCommand extends Command {
    protected function configure(): void {
        $this->setHelp('This command shows the file types');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $rows = $this->createTableRows(FileTypes::$typeMap);

        $table = new Table($output);
        $table->setHeaders(['File Type', 'Extensions']);
        $table->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }

    /**
     * @param array<string, array<string>> $fileTypes
     *
     * @return array<array{string, string}|TableSeparator>
     */
    private function createTableRows(array $fileTypes): array {
        $rows = [];

        foreach ($fileTypes as $type => $extensions) {
            $wrappedExtensions = wordwrap(implode(', ', $extensions), 80, "\n", true);
            $rows[] = [$type, $wrappedExtensions];
            $rows[] = new TableSeparator();
        }

        array_pop($rows);

        return $rows;
    }
}
