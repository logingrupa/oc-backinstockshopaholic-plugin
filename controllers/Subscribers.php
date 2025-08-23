<?php namespace Logingrupa\BackInStockShopaholic\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Class Subscribers
 * @package Logingrupa\BackInStockShopaholic\Controllers
 */
class Subscribers extends Controller
{
    /** @var array */
    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
    ];
    /** @var string */
    public $listConfig = 'config_list.yaml';
    /** @var string */
    public $formConfig = 'config_form.yaml';

    /**
     * Subscribers constructor.
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Logingrupa.BackInStockShopaholic', 'backinstockshopaholic-menu-main', 'backinstockshopaholic-menu-subscribers');
    }
}
