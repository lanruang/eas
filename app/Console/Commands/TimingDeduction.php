<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class TimingDeduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TimingDeduction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时划扣金额';

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
        DB::table('test')->insert([
            'test' => str_random(2),
        ]);
    }

}
