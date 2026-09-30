<?php

namespace App\Console\Commands;

use App\Services\SecurityLoggerService;
use Illuminate\Console\Command;

class CleanSecurityLogs extends Command
{
    protected $signature = 'security:clean-logs {--days=30}';
    protected $description = 'حذف سجلات الأمان الأقدم من X يوم';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $deleted = SecurityLoggerService::cleanup($days);

        $this->info("✅ تم حذف {$deleted} سجل أقدم من {$days} يوم");

        return self::SUCCESS;
    }
}