<?php namespace Logingrupa\BackInStockShopaholic\Updates;

use Schema;
use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableSubscribers
 * @package Logingrupa\BackInStockShopaholic\Classes\Console
 */
class CreateTableSubscribers extends Migration
{
    const TABLE = 'logingrupa_backinstock_subscribers';

    /**
     * Apply migration
     */
    public function up()
    {
        if (Schema::hasTable(self::TABLE)) {
            return;
        }

        Schema::create(self::TABLE, function (Blueprint $obTable)
        {
            $obTable->engine = 'InnoDB';
            $obTable->increments('id')->unsigned();
            $obTable->integer('user_id')->unsigned()->nullable()->index();
            $obTable->string('uuid')->unique()->index();
            $obTable->string('email')->nullable()->index();
            $obTable->string('name')->nullable();
            $obTable->string('locale')->nullable();
            $obTable->string('confirm_token', 64)->nullable()->unique();
            $obTable->string('unsub_token', 64)->nullable()->unique();
            $obTable->timestamp('confirmed_at')->nullable()->index();
            // One-email-per-day throttle
            $obTable->timestamp('last_notified_at')->nullable()->index();
            $obTable->timestamps();
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE);
    }
}
