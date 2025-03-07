<?php

namespace App\Console\Commands;

use App\Jobs\GetCategoriesAccounting;
use App\Jobs\InsertFirstTimeProductAccounting as JobsInsertFirstTimeProductAccounting;
use Illuminate\Console\Command;

class InsertFirstTimeProductAccounting extends Command
{

    protected $signature = 'app:insert_first_time_product_accounting';

    protected $description = 'Command description';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        GetCategoriesAccounting::dispatch()->onQueue('get_category_accounting');

        JobsInsertFirstTimeProductAccounting::dispatch()->onQueue('insert_first_time_product_accounting');
    }
}
