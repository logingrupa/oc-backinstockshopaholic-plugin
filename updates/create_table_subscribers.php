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
    const TABLE = 'logingrupa_backinstockshopaholic_subscribers';

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
            $obTable->string('name')->index();
            $obTable->string('slug')->unique()->index();
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
