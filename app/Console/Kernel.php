<?php

namespace App\Console;

use App\Components\UserAvaterManager;
use App\Components\UserManager;
use App\Components\Utils;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        ////////////////////////////////////////////////////////////////////////////////////////////////
        /// 头像处理任务，每分钟处理15个，将现有存量的头像配置为七牛
        ///
        /// By TerryQi
        ///
        /// !!!!!!!!!!2019-01-09 暂停头像上传任务
        ///
        $schedule->call(function () {
            Utils::processLog(__METHOD__, '', "处理头像任务 start at:" . time());

            $users = UserManager::getListByCon(['avatar_search_word' => 'thirdwx.qlogo.cn', 'page_size' => 30], true);
            foreach ($users as $user) {
                UserAvaterManager::setAvaterToQN($user->id);
            }
            $users = UserManager::getListByCon(['avatar_search_word' => 'wx.qlogo.cn', 'page_size' => 30], true);
            foreach ($users as $user) {
                UserAvaterManager::setAvaterToQN($user->id);
            }
            $users = UserManager::getListByCon(['avatar_search_word' => 'qzapp.qlogo.cn', 'page_size' => 30], true);
            foreach ($users as $user) {
                UserAvaterManager::setAvaterToQN($user->id);
            }

            Utils::processLog(__METHOD__, '', "处理头像任务 end at:" . time());

        })->everyMinute();

        //到访超期计划任务
        $schedule->call(function () {
            ScheduleManager::execBaobeiExceedSchedule();
            ScheduleManager::execDealExceedSchedult();
        })->dailyAt('1:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
