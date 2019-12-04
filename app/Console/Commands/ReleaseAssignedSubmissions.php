<?php

namespace App\Console\Commands;

use App\Submission;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ReleaseAssignedSubmissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'submissions:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets assigned_to_user_id to null for submissions which were assigned more than 60min ago';

    private $minutes = 60;

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
        $removeBeforeDate = Carbon::now()->subMinutes($this->minutes);
        $submissions = Submission
            ::where('assigned_at', '<', $removeBeforeDate)
            ->where('status', 'assigned')
            ->update(['status' => 'open',
                      'assigned_at' => null,
                      'assigned_to_user_id' => null]);

        $this->info($submissions . " submissions before " . $removeBeforeDate . " released");
        return;
    }
}
