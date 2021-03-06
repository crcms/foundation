<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-11-20 22:57
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Schedules;

use Illuminate\Console\Scheduling\Schedule;

/**
 * Interface ScheduleContract.
 */
interface ScheduleContract
{
    /**
     * @param Schedule $schedule
     * @return void
     */
    public function handle(Schedule $schedule): void;
}
