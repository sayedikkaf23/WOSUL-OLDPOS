<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertExpresspaySmsTemplateInSettingApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setting_app', function (Blueprint $table) {
            $table->string('expresspay_sms_template')->nullable();
        });
        DB::table('setting_app')->where('id',1)->update([
            'expresspay_sms_template' => 'Thank you for shopping!  Invoice number: [INVOICE_NUMBER] Amount: [AMOUNT]SAR, Link to view your bill: [INVOICE_LINK] Payment link: [PAYMENT_LINK]',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_app', function (Blueprint $table) {
            $table->dropColumn('expresspay_sms_template');
        });
    }
}
