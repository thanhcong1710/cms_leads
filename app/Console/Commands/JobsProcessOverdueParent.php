<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class JobsProcessOverdueParent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parents:process-overdue';

    protected $description = 'Process overdue parents array periodically';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        ini_set('memory_limit', '-1');
        
        $today = date('Y-m-d');
        // Insert trực tiếp bằng SQL (tránh memory limit error)
        \App\Providers\UtilityServiceProvider::query("
            INSERT INTO cms_report_overdue (parent_id, owner_id, branch_id, next_care_date, overdue_month, created_at, updated_at)
            SELECT p.id, p.owner_id, COALESCE(p.tmp_branch_id, 0), p.next_care_date, DATE_FORMAT(p.next_care_date, '%Y-%m'), NOW(), NOW()
            FROM cms_parents p
            LEFT JOIN cms_report_overdue o ON o.parent_id = p.id AND o.next_care_date = p.next_care_date
            WHERE p.next_care_date >= '$today 00:00:00' AND p.next_care_date <= '$today 23:59:59'
            AND (p.care_date < p.next_care_date OR p.care_date IS NULL)
            AND o.id IS NULL
        ");

        // Quét để cập nhật if they finally got cared
        \App\Providers\UtilityServiceProvider::query("
            UPDATE cms_report_overdue o
            INNER JOIN cms_parents p ON p.id = o.parent_id
            SET o.actual_care_date = p.care_date, o.updated_at = NOW()
            WHERE o.actual_care_date IS NULL AND p.care_date >= o.next_care_date
        ");

        return 0;
    }
}
