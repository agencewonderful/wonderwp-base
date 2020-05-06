<?php
/**
 * Created by PhpStorm.
 * User: jeremydesvaux
 * Date: 26/08/2016
 * Time: 12:04
 */

namespace WonderWp\Theme\Child;

use WonderWp\Component\PluginSkeleton\AbstractManager;
use WonderWp\Component\DependencyInjection\Container;
use WonderWp\Component\Service\ServiceInterface;
use WonderWp\Theme\Child\Components\Card\CardComponent;
use WonderWp\Theme\Child\Components\Pagination\PaginationComponent;
use WonderWp\Theme\Child\Controller\ThemePublicController;
use WonderWp\Theme\Child\Service\ChildThemeHookService;
use WonderWp\Theme\Child\Service\ChildThemeShortcodeService;
use WonderWp\Theme\Child\Service\ThemeAssetService;
use WonderWp\Theme\Child\Service\ThemeRouteService;
use WonderWp\Theme\Core\ThemeManager;

class ChildThemeManager extends ThemeManager
{

    public function register(Container $container)
    {

        $this->setConfig('use_critical_css', true);

        parent::register($container);

        //Controllers
        $this->addController(AbstractManager::PUBLIC_CONTROLLER_TYPE, function () {
            return new ThemePublicController();
        });

        //Hooks
        $this->addService(ServiceInterface::HOOK_SERVICE_NAME, function () {
            return new ChildThemeHookService($this);
        });
        //Routes
        $this->addService(ServiceInterface::ROUTE_SERVICE_NAME, function () {
            return new ThemeRouteService($this);
        });
        //Assets
        $this->addService(ServiceInterface::ASSETS_SERVICE_NAME, function () {
            return new ThemeAssetService($this);
        });
        //Shortcodes
        $this->addService(ServiceInterface::SHORT_CODE_SERVICE_NAME, function () {
            return new ChildThemeShortcodeService($this);
        });

        $container['wwp.theme.component.card']       = $container->factory(function () {
            return new CardComponent();
        });
        $container['wwp.theme.component.pagination'] = function () {
            return new PaginationComponent();
        };

    }

}
