<?php namespace Logingrupa\BackInStockShopaholic\Classes\Collection;

use Lovata\Toolbox\Classes\Collection\ElementCollection;
use Logingrupa\BackInStockShopaholic\Classes\Item\SubscriberItem;
use Logingrupa\BackInStockShopaholic\Classes\Store\SubscriberListStore;

/**
 * Class SubscriberCollection
 * @package Logingrupa\BackInStockShopaholic\Classes\Collection
 */
class SubscriberCollection extends ElementCollection
{
    const ITEM_CLASS = SubscriberItem::class;
}
