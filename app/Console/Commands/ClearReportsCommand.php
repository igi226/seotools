<?php

namespace App\Console\Commands;

use App\Models\Report;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ClearReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:clear-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the `reports` database table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Report::onlyTrashed()->whereDate('deleted_at', '<', Carbon::now()->subMonth()->endOfMonth())->forceDelete();

        return 0;
    }
}
