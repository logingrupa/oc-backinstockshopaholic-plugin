<?php namespace Logingrupa\BackInStockShopaholic\Components;

use Lovata\Toolbox\Classes\Component\ElementData;
use Logingrupa\BackInStockShopaholic\Classes\Item\SubscriberItem;

/**
 * Class SubscriberData
 * @package Logingrupa\BackInStockShopaholic\Components
 */
class SubscriberData extends ElementData
{
    /**
     * Component details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'logingrupa.backinstockshopaholic::lang.component.subscriber_data_name',
            'description' => 'logingrupa.backinstockshopaholic::lang.component.subscriber_data_description',
        ];
    }

    /**
     * Make new element item
     * @param int $iElementID
     * @return SubscriberItem
     */
    protected function makeItem($iElementID)
    {
        return SubscriberItem::make($iElementID);
    }
}
