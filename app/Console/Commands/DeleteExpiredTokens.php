<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-expired-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete access and refresh tokens that are expired for more than 3 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threeDayAfterExpired  = Carbon::now()->subDays(3);
        $deleteExpiredToken = DB::table('personal_access_tokens')
            ->where('expires_at', '<', $threeDayAfterExpired)
            ->delete();

        $this->info("Deleted Access and Refresh Tokens: $deleteExpiredToken");
    }
}
