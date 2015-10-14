<?php

namespace JakubSzajna\LintPack\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class LintCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lint:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lint all files with all linters.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tasks = array('lint:jshint', 'lint:phpcpd', 'lint:phpcs', 'lint:phpmd');

        $config = config('lint.all', ['enabled' => false]);

        if (!$config['enabled'] || env('APP_ENV') != $config['environment']) {
            return $this->handleDisabledTask();
        }

        $returnCodes = array();

        foreach ($tasks as $task) {
            $returnCodes[] = $this->launchTask($task, $this->isTaskEnabled($task));
        }

        return max($returnCodes);
    }

    protected function executeCommand($command)
    {
        if (!$this->isTaskEnabled()) {
            return $this->handleDisabledTask();
        }
        $process = new Process($command);

        $this->info($command);
        $returnValue = $process->run();
        $this->output->write($process->getOutput());

        $this->displayResult($returnValue);
        return $returnValue;
    }

    protected function displayResult($returnValue)
    {
        if ($returnValue === 0) {
            $this->info("Done, without errors.\n");
        } else {
            $this->error("Command failed.\n");
        }
    }

    protected function launchTask($task, $addNoise = true)
    {
        if ($addNoise) {
            $this->comment("Invoking {$task}.");
        }

        $returnValue = $this->call($task);

        return $returnValue;
    }

    protected function getFilesMatching($locations, $ignoresRegexpes, $extensionsRegexp)
    {
        $files = array();

        foreach ($locations as $location) {
            $ignoredFiles = $this->findIgnoredFiles($location, $ignoresRegexpes);
            $files = array_merge($files, $this->findFilesWhichAreNotIgnored($location, $ignoredFiles));
        }

        return $this->filterFilesMatchingExtensionsRegexp($files, $extensionsRegexp);
    }

    protected function findIgnoredFiles($location, $ignoresRegexpes)
    {
        $ignoredFiles = array();
        $allFiles = $this->getAllFiles($location);

        foreach ($ignoresRegexpes as $ignoresRegexp) {
            $ignoredFiles = array_merge_recursive(
                $ignoredFiles,
                $this->convertIteratorToArray(new RegexIterator($allFiles, $ignoresRegexp))
            );
        }

        return $ignoredFiles;
    }

    protected function findFilesWhichAreNotIgnored($location, $ignoredFiles)
    {
        $notIgnoredFiles = array();
        $iteratedFiles = $this->getAllFiles($location);

        foreach ($iteratedFiles as $iteratedFile) {
            if (!in_array($iteratedFile, $ignoredFiles)) {
                $notIgnoredFiles[] = (string)$iteratedFile;
            }
        }

        return $notIgnoredFiles;
    }

    protected function filterFilesMatchingExtensionsRegexp($files, $extensionsRegexp)
    {
        $matchedFiles = array();

        foreach ($files as $file) {
            if (preg_match($extensionsRegexp, $file)) {
                $matchedFiles[] = $file;
            }
        }

        return $matchedFiles;
    }

    protected function getAllFiles($location)
    {
        if (!isset($this->allFiles[$location])) {
            $this->allFiles[$location] = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($location),
                RecursiveIteratorIterator::SELF_FIRST
            );
        }
        return $this->allFiles[$location];
    }

    protected function convertIteratorToArray($iterator)
    {
        $result = array();
        foreach ($iterator as $i) {
            $result[] = (string)$i;
        }

        return $result;
    }

    protected function isTaskEnabled($name = null)
    {
        return !!config('lint.' . str_replace('lint:', '', ($name ? $name : $this->signature)), false);
    }

    protected function handleDisabledTask()
    {
        $this->comment('Command has been disabled.');
        return 0;
    }
}
