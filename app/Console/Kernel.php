<?php

namespace Paralelo28\Console;

use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;
use Paralelo28\Book;
use Paralelo28\CronJob;
use Paralelo28\PersonalSurvey;
use Paralelo28\Sale;
use Paralelo28\User;
use Paralelo28\UserSetting;

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
     $schedule->call(function () {
            $cronJobs = CronJob::all();

            foreach ($cronJobs as $cronJob){
                $expireDate = date('Y-m-d h:i:s', strtotime($cronJob['created_at'] . '+ 14 days'));
                $now = date('Y-m-d h:i:s');

                if(strtotime($now) > strtotime($expireDate)){
                    $user = User::where('id', $cronJob['userId'])->first();
                    $shipmentData = Sale::where('shipmentCode', $cronJob['shipmentCode'])->get();

                    if($user && $shipmentData){
                        $userSettings = UserSetting::where('userId', $user['id'])->first();
                        $bookNames = [];

                        foreach ($shipmentData as $data){
                            $book = Book::where('id', $data['bookId'])->first();

                            if($book) array_push($bookNames, $book['title']);
                        }

                        try{
                            $surveyUrl = bin2hex(random_bytes(mt_rand(25, 50)));
                        }catch(Exception $e){
                            $surveyUrl = $this->generateRandomString(mt_rand(50, 100));
                        }

                        try{
                            $data = [
                                "email" => $user['email'],
                                "name" => $userSettings['name'],
                                "products" => $bookNames,
                                "surveyUrl" => $surveyUrl
                            ];

                            Mail::send('emails.surveyEmail', $data, function($message) use ($data) {
                                $message->to($data['email'], $data['name'])->subject('Encuesta de satisfacción');
                                $message->from('noreply@editorialparalelo28.com', 'EditorialParalelo28');
                            });

                            PersonalSurvey::create([
                                'userId' => $user['id'],
                                'shipmentCode' => $cronJob['shipmentCode'],
                                'surveyUrl' => $surveyUrl
                            ]);
                        }catch (Exception $e){
                            unset($e);
                        }
                    }
                    $cronJob->delete();
                }
            }
        })->daily();
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
