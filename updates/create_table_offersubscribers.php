<?php namespace Logingrupa\BackInStockShopaholic\Updates;

use Schema;
use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateTableOfferSubscribers
 * @package Logingrupa\BackInStockShopaholic\Classes\Console
 */
class CreateTableOfferSubscribers extends Migration
{
    const TABLE = 'logingrupa_backinstock_offersubscribers';

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
            $obTable->unsignedInteger('subscriber_id');

            $obTable->unsignedInteger('offer_id');
            $obTable->enum('status', ['pending', 'sent', 'unsubscribed'])->default('pending')->index();
            $obTable->timestamp('sent_at')->nullable()->index();
            $obTable->timestamp('opened_at')->nullable()->index();
            $obTable->timestamps();

            $obTable->foreign('subscriber_id')
                ->references('id')
                ->on('logingrupa_backinstock_subscribers')
                ->onDelete('cascade');

            $obTable->foreign('offer_id')
                ->references('id')
                ->on('lovata_shopaholic_offers')
                ->onDelete('cascade');
                
            $obTable->unique(['subscriber_id', 'offer_id'], 'lg_bis_unique_subscriber_offer');

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
