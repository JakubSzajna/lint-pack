<?php

namespace JakubSzajna\LintPack\Console\Commands;

class PhpcsLintCommand extends LintCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lint:phpcs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->executeCommand($this->getCommand());
    }

    public function getCommand()
    {
        $config = config('lint.phpcs');

        return $config['bin'] .
        ' -p' .
        ($config['warnings'] ? '' : ' -n') .
        ($config['recursion'] ? '' : ' -l') .
        ($config['standard'] ? ' --standard=' . $config['standard'] : '') .
        ($config['extensions'] ? ' --extensions=' . implode(',', $config['extensions']) : '') .
        ($config['ignores'] ? ' --ignore=' . implode(',', $config['ignores']) : '') .
        ' ' . implode(' ', $config['locations']);
    }
}
