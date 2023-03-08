<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Services\ApiVersionService;

class DocsGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'docs:generate {api-version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate scribe versioned api docs.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $version = ucfirst($this->argument('api-version'));
        $placeholder = \App\Services\ApiVersionService::$placeholder;

        $filename = base_path('app/Services/ApiVersionService.php');
        $contents = file_get_contents($filename);

        // Replace the placeholder with the version of docs we want to generate and run the command.
        $tempContents = str_replace($placeholder, $version, $contents);
        file_put_contents($filename, $tempContents);

        sleep(1);

        passthru('php artisan route:clear');
        passthru('php artisan scribe:generate');

        file_put_contents($filename, $contents);
    }
}
