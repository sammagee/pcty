<?php

namespace App\Console\Commands;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;

class Seed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed {number=500 : Number of employees to seed} {--F|fresh : Reset database} {--U|with-user : Seed with a team/user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with a specified number of employees';

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
     * @return int
     */
    public function handle()
    {
        if ((bool) $this->option('fresh')) $this->call('migrate:fresh');

        $seeder = new DatabaseSeeder;
        $seeder->callWith(DatabaseSeeder::class, [(int) $this->argument('number'), (bool) $this->option('with-user')]);

        $this->call('cache:clear');

        return 0;
    }
}
