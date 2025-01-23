<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckVpsUptime extends Command
{
    protected $signature = 'vps:check-uptime';
    protected $description = 'Memeriksa uptime VPS setiap jam';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $uptime = shell_exec("uptime -p");

        $this->info('Uptime VPS: ' . $uptime);

        Log::info('VPS uptime checked: ' . $uptime);
    }
}
