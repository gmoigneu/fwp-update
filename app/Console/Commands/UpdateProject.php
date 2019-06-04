<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Cz\Git\GitRepository;
use Illuminate\Support\Facades\File;

class UpdateProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'psh:update';

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

        $this->info('Cloning psh repo locally');
        $initial = GitRepository::cloneRepository(config('app.psh_project'), 'storage/git');
        $this->info('Adding upstream remote');
        $initial->addRemote('update', 'https://github.com/platformsh/fwp-joomla-update.git');
        $this->info('Fetch/Merge upstream remote');
        $initial->fetch('update');
        $initial->merge('update/3.8');
        
        $this->info('Push to platform to update');
        $initial->push('origin');
        $this->info('Complete!');

        $this->info('Clean the local repo');
        File::deleteDirectory('storage/git');
    }
}