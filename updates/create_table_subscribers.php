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
            $obTable->unsignedInteger('user_id')->unique();
            $obTable->string('uuid')->unique()->index();
            $obTable->string('email')->unique()->nullable()->index();
            $obTable->string('name')->nullable();
            $obTable->string('locale')->nullable();
            $obTable->string('confirm_token', 64)->nullable()->unique();
            $obTable->string('unsub_token', 64)->nullable()->unique();
            $obTable->timestamp('confirmed_at')->nullable()->index();
            // One-email-per-day throttle
            $obTable->timestamp('last_notified_at')->nullable()->index();
            $obTable->timestamps();
            if (Schema::hasTable('users')) {
                $obTable->foreign('user_id', 'fk_bis_user_rl')
                ->references('id')->on('users')->onDelete('cascade');
            } elseif (Schema::hasTable('lovata_buddies_users')) {
                $obTable->foreign('user_id', 'fk_bis_user_buddies')
                ->references('id')->on('lovata_buddies_users')->onDelete('cascade');
            }

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
