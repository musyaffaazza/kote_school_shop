<?php

namespace App\Console\Commands;

use App\Models\ContactMessage;
use Illuminate\Console\Command;

class FixMessageTimestamps extends Command
{
    protected $signature = 'messages:fix-timestamps';

    protected $description = 'Fix contact message timestamps that were stored in UTC before timezone change';

    public function handle(): void
    {
        // Only fix messages created today that were stored in UTC (before timezone was set to Asia/Jakarta)
        $count = ContactMessage::whereDate('created_at', now()->toDateString())
            ->where('created_at', '<', now()->subHours(6))
            ->update([
                'created_at' => \DB::raw('DATE_ADD(created_at, INTERVAL 7 HOUR)'),
                'updated_at' => \DB::raw('DATE_ADD(updated_at, INTERVAL 7 HOUR)'),
            ]);

        $this->info("Fixed {$count} message timestamp(s).");
    }
}
