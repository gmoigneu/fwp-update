<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Cz\Git\GitRepository;
use Illuminate\Support\Facades\File;

class InitProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'psh:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init project with a repo';

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
        // Repo 3.6 : https://github.com/platformsh/fwp-joomla-update.git
        $this->info('Clean the local repo');
        File::deleteDirectory('storage/git');

        $this->info('Cloning repo locally');

        $repo = GitRepository::cloneRepository('https://github.com/platformsh/fwp-joomla-update.git', 'storage/git/');
        $this->info('Adding platform remote');
        $repo->addRemote('platform', config('app.psh_project'));
        $this->info('Push to platform to init the project');
        $repo->push('platform', array('master', '-f'));
        $this->info('Complete!');

        $this->info('Clean the local repo');
        File::deleteDirectory('storage/git');
    }
}