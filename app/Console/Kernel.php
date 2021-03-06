<?php
namespace App\Console;
use Log;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\User;
use App\Models\Sysconfig;
use App\Libraries\OKTOOL;
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
        $interval=config('okcoin.interval');
        switch ($interval) {
        case '1min':
            $schedule->call(function()
            {
                $tradetype=Sysconfig::where('name','tradetype')->first()->value;
                $this->autotrade('dotrade','btc_cny',$tradetype);
                $this->autotrade('dotrade','ltc_cny',$tradetype);
            })->everyMinute();
            break;
        case '5min':
            $schedule->call(function()
            {
                $tradetype=Sysconfig::where('name','tradetype')->first()->value;
                $this->autotrade('dotrade','btc_cny',$tradetype);
                $this->autotrade('dotrade','ltc_cny',$tradetype);
            })->everyFiveMinutes();
            break;
        case '10min':
            $schedule->call(function()
            {
                $tradetype=Sysconfig::where('name','tradetype')->first()->value;
                $this->autotrade('dotrade','btc_cny',$tradetype);
                $this->autotrade('dotrade','ltc_cny',$tradetype);
            })->everyTenMinutes();
            break;
        case '30min':
            $schedule->call(function()
            {
                $tradetype=Sysconfig::where('name','tradetype')->first()->value;
                $this->autotrade('dotrade','btc_cny',$tradetype);
                $this->autotrade('dotrade','ltc_cny',$tradetype);
            })->everyThirtyMinutes();
            break;
        case '1h':
            $schedule->call(function()
            {
                $tradetype=Sysconfig::where('name','tradetype')->first()->value;
                $this->autotrade('dotrade','btc_cny',$tradetype);
                $this->autotrade('dotrade','ltc_cny',$tradetype);
            })->hourly();
            break;
        default:
            break;
        }
        $schedule->call(function()
        {
                $tradetype=Sysconfig::where('name','tradetype')->first()->value;
            for ($i = 0; $i < 8; $i++) {
                // code...
                $this->autotrade('update','btc_cny',$tradetype);
                sleep(3);
            }
        })->everyMinute();
    }
    /**
     * @access 
     * @author kaleo <kaleo1990@hotmail.com>
     * @param operate   update  ????????????
     *                  totrade ????????????  
     * @param symbol    btc_cny 
     *                  ltc_cny
     * @param tradetype 1   ?????????
     *                  2   ?????????
     * @return
     */
    public function autotrade($operate,$symbol,$tradetype){
        switch ($operate) {
        case 'update':
            $users=User::all();
            //????????????
            if (count($users)<1) {
                //                Log::info('????????????????????????');
            }
            else
            {
                foreach ($users as $user) {
                    try{
                        if ($user->api_key!=null&&$user->secret_key!=null)
                        {
                            $OKTOOL=new OKTOOL($user);
                            $res=$OKTOOL->update_data_database();
                            $newuserinfo=$OKTOOL->get_new_info('userinfo',$symbol);
                        }
                        else
                        {
                            Log::info('name: '.$user->name.' ???????????????api_key???secret_key!');
                        }
                    }
                    catch(exception $e){
                        //????????????
                        $user->btc_autotrade=false;
                        $user->ltc_autotrade=false;
                        $user->save();
                    }
                }
            }
            break;
        case 'dotrade':
            switch ($symbol) {
            case 'btc_cny':
                $users=User::where('btc_autotrade',true)->get();
                break;
            case 'ltc_cny':
                $users=User::where('ltc_autotrade',true)->get();
                break;
            default:
                $users=User::where('btc_autotrade',true)->get();
                break;
            }
            //????????????
            if (count($users)<1) {
                //               Log::info($symbol.'-????????????????????????');
            }
            else
            {


                foreach ($users as $user) {
                    try{
                        if ($user->api_key!=null&&$user->secret_key!=null)
                        {
                            $OKTOOL=new OKTOOL($user);
                            $res=$OKTOOL->update_data_database();
                            switch ($tradetype) {
                            case 1:
                                $res=$OKTOOL->autotrade($symbol);
                                break;
                            case 2:
                                $res=$OKTOOL->autotrade2($symbol);
                                break;
                            default:
                                $res=$OKTOOL->autotrade($symbol);
                                break;
                            }
                            $newuserinfo=$OKTOOL->get_new_info('userinfo',$symbol);
                            Log::info('tradetype:'.$tradetype.'-'.$symbol.'-:'.$user->name.'-cost:'.$user->cost.'-asset_net:'.$newuserinfo->asset_net.'-asset_total: '.$newuserinfo->asset_total);
                        }
                        else
                        {
                            Log::info('name: '.$user->name.' ???????????????api_key???secret_key!');
                        }
                    }
                    catch(exception $e){
                        //????????????
                        $user->btc_autotrade=false;
                        $user->ltc_autotrade=false;
                        $user->save();
                    }
                }


            }
            break;
        default:
            break;
        }
    }
    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
