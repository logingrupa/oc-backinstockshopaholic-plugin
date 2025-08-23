<?php namespace Logingrupa\BackInStockShopaholic\Classes\Event\Subscriber;

use Lovata\Toolbox\Classes\Event\ModelHandler;
use Logingrupa\BackInStockShopaholic\Models\Subscriber;
use Logingrupa\BackInStockShopaholic\Classes\Item\SubscriberItem;
use Logingrupa\BackInStockShopaholic\Classes\Store\SubscriberListStore;

/**
 * Class SubscriberModelHandler
 * @package Logingrupa\BackInStockShopaholic\Classes\Event\Subscriber
 */
class SubscriberModelHandler extends ModelHandler
{
    /** @var Subscriber */
    protected $obElement;

    /**
     * Get model class name
     * @return string
     */
    protected function getModelClass()
    {
        return Subscriber::class;
    }

    /**
     * Get item class name
     * @return string
     */
    protected function getItemClass()
    {
        return SubscriberItem::class;
    }
    /**
     * After create event handler
     */
    protected function afterCreate()
    {
        parent::afterCreate();
    }

    /**
     * After save event handler
     */
    protected function afterSave()
    {
        parent::afterSave();
    }

    /**
     * After delete event handler
     */
    protected function afterDelete()
    {
        parent::afterDelete();
    }
}
