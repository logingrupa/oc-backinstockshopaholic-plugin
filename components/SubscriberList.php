<?php namespace Logingrupa\BackInStockShopaholic\Components;

use Cms\Classes\ComponentBase;
use Logingrupa\BackInStockShopaholic\Classes\Collection\SubscriberCollection;

/**
 * Class SubscriberList
 * @package Logingrupa\BackInStockShopaholic\Components
 */
class SubscriberList extends ComponentBase
{
    /**
     * Component details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'logingrupa.backinstockshopaholic::lang.component.subscriber_list_name',
            'description' => 'logingrupa.backinstockshopaholic::lang.component.subscriber_list_description',
        ];
    }

    /**
     * Make element collection
     * @param array $arElementIDList
     * @return SubscriberCollection
     */
    public function make($arElementIDList = null)
    {
        return SubscriberCollection::make($arElementIDList);
    }

    /**
     * Method for ajax request with empty response
     * @return bool
     */
    public function onAjaxRequest()
    {
        return true;
    }
}
