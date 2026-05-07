<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoImportVoters extends Command
{
    protected $signature = 'voters:auto-import';
    protected $description = 'Auto import voter CSV and PDF files';

    public function handle()
    {
        $this->info('Auto Import Started...');

        app()->call('App\Http\Controllers\ImportController@auto_import_files');

        $this->info('Auto Import Completed.');

        return 0;
    }
}