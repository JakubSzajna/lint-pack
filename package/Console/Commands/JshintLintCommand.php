<?php

namespace JakubSzajna\LintPack\Console\Commands;

class JshintLintCommand extends LintCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lint:jshint';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lint all files with jshint.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->executeCommand($this->getCommand());
    }

    public function getCommand()
    {
        $config = config('lint.jshint', []);

        if (isset($config['jshintignore']) && is_string($config['jshintignore'])) {
            return $this->getCommandWithIgnore($config);
        }
        return $this->getCommandWithoutIgnore($config);
    }

    private function getCommandWithIgnore($config)
    {
        return trim(
            $config['bin'] .
            ((isset($config['jshintrc']) && $config['jshintrc']) ? ' --config ' . $config['jshintrc'] : '') .
            ' --exclude-path ' . $config['jshintignore'] .
            (!is_null($config['extensions']) ? ' --extra-ext ' . implode(',', $config['extensions']) : '') .
            (!is_null($config['locations']) ? ' ' . implode(' ', $config['locations']) : '')
        );
    }

    private function getCommandWithoutIgnore($config)
    {
        if (empty($config['extensions'])) {
            $extensions = '/\..[^.]+/';
        } else {
            $extensions = '/(?:\.' . implode('$)|(?:\.', $config['extensions']) . '$)/';
        }

        $files = $this->getFilesMatching($config['locations'], $config['ignores'], $extensions);

        return trim(
            $config['bin'] .
            ((isset($config['jshintrc']) && $config['jshintrc']) ? ' --config ' . $config['jshintrc'] : '') .
            ' ' . implode(' ', $files)
        );
    }
}
