<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Cz\Git\GitRepository;
use Illuminate\Support\Facades\File;

class ReplaceProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'psh:replace';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replace the project with a newer version';

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
        // Repo update: https://github.com/platformsh/fwp-joomla-update.git

        $this->info('Clean the local repo');
        File::deleteDirectory('storage/git');

        $this->info('Cloning repo locally');

        $repo = GitRepository::cloneRepository('https://github.com/platformsh/fwp-joomla-update.git', 'storage/git/', ['-b', '3.8']);
        $this->info('Adding platform remote');
        $repo->addRemote('platform', config('app.psh_project'));
        $this->info('Push to platform to init the project');
        $repo->push('platform', array('3.8:master', '-f'));
        $this->info('Complete!');

        $this->info('Clean the local repo');
        File::deleteDirectory('storage/git');
    }
}