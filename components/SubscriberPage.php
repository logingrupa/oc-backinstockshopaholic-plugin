<?php namespace Logingrupa\BackInStockShopaholic\Components;

use Lovata\Toolbox\Classes\Component\ElementPage;
use Logingrupa\BackInStockShopaholic\Models\Subscriber;
use Logingrupa\BackInStockShopaholic\Classes\Item\SubscriberItem;

/**
 * Class SubscriberPage
 * @package Logingrupa\BackInStockShopaholic\Components
 */
class SubscriberPage extends ElementPage
{
    /**
     * Component details
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'logingrupa.backinstockshopaholic::lang.component.subscriber_page_name',
            'description' => 'logingrupa.backinstockshopaholic::lang.component.subscriber_page_description',
        ];
    }

    /**
     * Get element object
     * @param string $sElementSlug
     * @return Subscriber
     */
    protected function getElementObject($sElementSlug)
    {
        if (empty($sElementSlug)) {
            return null;
        }

        $obElement = Subscriber::getBySlug($sElementSlug)->first();

        return $obElement;
    }

    /**
     * Make new element item
     * @param int $iElementID
     * @param Subscriber $obElement
     * @return SubscriberItem
     */
    protected function makeItem($iElementID, $obElement)
    {
        return SubscriberItem::make($iElementID, $obElement);
    }
}
