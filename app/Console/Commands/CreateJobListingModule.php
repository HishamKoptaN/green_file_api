<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateJobListingModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:joblisting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create model, controller, migration, seeder, factory, and request for job listings.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // إنشاء الموديل مع المايغريشن
        $this->call('make:model', ['name' => 'JobListing', '-m' => true]);

        // إنشاء الكنترولر
        $this->call('make:controller', ['name' => 'JobListingController']);

        // إنشاء السييدر
        $this->call('make:seeder', ['name' => 'JobListingSeeder']);

        // إنشاء الفاكتوري
        $this->call('make:factory', ['name' => 'JobListingFactory', '--model' => 'JobListing']);

        // إنشاء الـ Request
        $this->call('make:request', ['name' => 'StoreJobListingRequest']);

        $this->info('JobListing module has been created successfully!');
    }
}
