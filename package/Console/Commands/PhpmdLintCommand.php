<?php

namespace JakubSzajna\LintPack\Console\Commands;

class PhpmdLintCommand extends LintCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lint:phpmd';

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
        $config = config('lint.phpmd');

        return $config['bin'] .
        ' ' . implode(DIRECTORY_SEPARATOR . '*,', $config['locations']) .
        ' text' .
        ' ' . implode(',', $config['rulesets']) .
        ($config['extensions'] ? ' --suffixes=' . implode(',', $config['extensions']) : '') .
        ($config['ignores'] ? ' --exclude ' . implode(',', $config['ignores']) : '');
    }
}
