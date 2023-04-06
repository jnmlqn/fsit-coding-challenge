<?php

namespace App\Console\Commands;

use App\Services\CustomerService;
use Illuminate\Console\Command;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import 100 users from randomuser.me';

    /**
     * @var CustomerService
     */
    private CustomerService $customerService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CustomerService $customerService)
    {
        parent::__construct();
        $this->customerService = $customerService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->customerService->import()) {
            $this->info('100 users were successfully imported to database');

            return 0;
        }

        return 1;
    }
}
