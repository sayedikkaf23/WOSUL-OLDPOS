<?php

namespace App\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use App\Models\SettingEmail as SettingEmailModel;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {   
        if (\Schema::hasTable('setting_mail')) {
            $mail = SettingEmailModel::select('*')->active()->first();
            if ($mail) //checking if table is not empty
            {  


        if((\Request::segment(3)!=null) && \Request::segment(3)=="forgot_password") {
            $config = array(
            'driver'     => 'smtp',
            'host'       => 'smtppro.zoho.com',
            'port'       => 587,
            'from'       => array('address' => 'noreply@wosul.sa', 'name' => 'Wosul'),
            'encryption' => 'tls',
            'username'   => 'noreply@wosul.sa',
            'password'   => 'BD5QMejwYm9U',
            );
        }else{

            $config = array(
            'driver'     => $mail->driver,
            'host'       => $mail->host,
            'port'       => $mail->port,
            'from'       => array('address' => $mail->from_email, 'name' => $mail->from_email_name),
            'encryption' => $mail->encryption,
            'username'   => $mail->username,
            'password'   => $mail->password,
            );

        }

                Config::set('mail', $config);
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}