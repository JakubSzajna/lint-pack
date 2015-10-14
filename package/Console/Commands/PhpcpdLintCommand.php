<?php

namespace JakubSzajna\LintPack\Console\Commands;

class PhpcpdLintCommand extends LintCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lint:phpcpd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lint all files with phpcpd.';

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
        $config = config('lint.phpcpd');

        return $config['bin'] .
        ' --progress' .
        ($config['min_lines'] ? ' --min-lines=' . $config['min_lines'] : '') .
        ($config['min_tokens'] ? ' --min-tokens=' . $config['min_tokens'] : '') .
        ($config['extensions'] ? ' --names=\\*.' . implode(',\\*.', $config['extensions']) : '') .
        ($config['ignores'] ?
            ' --names-exclude=' . implode(',', $config['ignores']) .
            ' --exclude=' . implode(',', $config['ignores']) :
            ''
        ) .
        ' ' . implode(',', $config['locations']);
    }
}
