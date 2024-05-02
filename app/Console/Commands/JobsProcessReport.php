<?php

namespace App\Console\Commands;

use App\Http\Controllers\ToolsController;
use App\Http\Controllers\VoipController;
use App\Models\Sms;
use Illuminate\Console\Command;
use App\Providers\UtilityServiceProvider as u;
use Illuminate\Http\Request;

class JobsProcessReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobsProcessReport:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'jobsProcessReport';

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
    public function handle(Request $request)
    {
        // $date_time = date('Y-m-d',time()-3600);
        // $report_week = u::first("SELECT * FROM cms_report_week WHERE start_date <='$date_time' AND end_date>='$date_time'");
        // if($report_week){
        //     $tool = new ToolsController();
        //     $tool->processReportSaleHub($report_week->id);
        // }
        u::query("INSERT INTO log_jobs (`action`, created_at) VALUES ('jobsProcessReport','".date('Y-m-d H:i:s')."')");
        return "ok";
    }
    
}
