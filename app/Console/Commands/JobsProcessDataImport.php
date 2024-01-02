<?php

namespace App\Console\Commands;

use App\Http\Controllers\ImportsController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\VoipController;
use App\Models\Sms;
use Illuminate\Console\Command;
use App\Providers\UtilityServiceProvider as u;
use Illuminate\Http\Request;

class JobsProcessDataImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobsProcessDataImport:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'JobsProcessDataImport';

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
        $tool = new ImportsController();
        // for($i=0;$i<100;$i++){
        //     echo $i."/";
        //     $tool->convertDataApaxToImport();
        // }   

        // $import_id =1;
        // $tool->processCheckDuplicateDataApax($import_id);

        $tool->assignAPAX();

        u::query("INSERT INTO log_jobs (`action`, created_at) VALUES ('JobsProcessDataImport','".date('Y-m-d H:i:s')."')");
        return "ok";
    }
    
}
