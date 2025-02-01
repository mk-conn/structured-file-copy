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

#[AsCommand(name: 'file-types', description: 'Show file types to file extensions mapping')]
class ShowFileTypesCommand extends Command {
    public function __construct(private readonly FileTypes $fileTypes) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $rows = $this->createTableRows($this->fileTypes->getTypeMap());

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

        foreach ($fileTypes as $fileType => $mimeTypes) {
            $extensions = $this->fileTypes->getFilesForType($fileType);
            $wrappedExtensions = wordwrap(implode(', ', $extensions), 80, "\n", true);
            $rows[] = [$fileType, $wrappedExtensions];
            $rows[] = new TableSeparator();
        }

        array_pop($rows);

        return $rows;
    }
}
