<?php

declare(strict_types=1);

namespace MkConn\Sfc\Commands;

use MkConn\Sfc\File\Find;
use MkConn\Sfc\Storage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function MkConn\Sfc\human_filesize;

#[AsCommand(name: 'copy')]
class CopyCommand extends Command
{
    protected function configure(): void
    {
        $this->setDescription('Copies files from a source folder to a target folder but in a sctructured way if you want.');
        $this->addOption(
            'source',
            null,
            InputOption::VALUE_OPTIONAL,
            'The from folder (if not set, files are taken from the folder where the command is running)'
        );
        $this->addOption('target', null, InputOption::VALUE_REQUIRED, 'Where to put the files');
        $this->addOption(
            'sort',
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Sort in folders by [date:day,date:month,date:year,alpha:name]'
        );
        $this->addOption(
            'name-letters',
            null,
            InputOption::VALUE_OPTIONAL,
            'By how many (first) letters should the name sorted'
        );
        $this->addOption(
            'exclude-ext',
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Exclude files with extensions'
        );
        $this->addOption(
            'file-type',
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Move only a file type [image, video, office, text, richtext, pdf]'
        );
        $this->addOption(
            'file-ext',
            null,
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Move only files with a specific extension'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        date_default_timezone_set('Europe/Berlin');
        $source = $input->getOption('source') ?: getcwd();
        $options['target'] = $input->getOption('target');

        $output->writeln("source: $source");
        $output->writeln("target: {$options['target']}");

        $types = $input->getOption('file-type');
        $exts = $input->getOption('file-ext');
        $exclude = $input->getOption('exclude-ext');
        $sort = $input->getOption('sort');

        $options['sort'] = $sort;
        $options['sort'][Storage::NAME_LETTERS] = $input->getOption('name-letters') ?: 1;

        $output->writeln(
            sprintf(
                '<info>Collecting files to copy: (types: %s) (exts: %s) (sort: %s)</info>',
                implode(', ', $types),
                implode(', ', $exts),
                implode(', ', $options['sort'])
            )
        );

        $findFiles = new Find($source, $types, $exts, $exclude);
        $files = $findFiles->files();

        $output->writeln("<info>Found {$files->count()} files to copy.</info>");

        $storage = new Storage(iterator_to_array($files), $options, $output);

        if ($storage->copy()) {
            $fileSize = human_filesize($storage->getTotalFileSize());
            $output->writeln("");
            $output->writeln("Copied {$storage->getTotalFiles()} of {$files->count()} files ({$fileSize})");
            $output->writeln('Here is the log:');

            foreach ($storage->getCopiedFiles() as $copiedFileName => $log) {
                $output->writeln("<info>{$copiedFileName}->{$log['to']}</info>");
                $output->writeln($log['result']);
            }

            return Command::SUCCESS;
        } else {
            if ($storage->hasErrors()) {
                $output->writeln(implode("\n", $storage->getErrors()));
            }

            return Command::FAILURE;
        }
    }

}
