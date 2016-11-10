<?php

namespace WonderWp\Plugin\Jeux;

//Must uses
use \Composer\Autoload\ClassLoader as AutoLoader; //Must use the autoloader
use Pimple\Container as PContainer;
use WonderWp\APlugin\AbstractManager;
use WonderWp\APlugin\AbstractPluginManager;
use WonderWp\DI\Container;
use WonderWp\Services\AbstractService;

/**
 * Class JeuxManager
 * @package WonderWp\Plugin\Jeux
 * The manager is the file that registers everything your plugin is going to use / need.
 * It's the most important file for your plugin, the one that bootstraps everything.
 * The manager registers itself with the DI container, so you can retrieve it somewhere else and use its config / controllers / services
 */
class JeuxManager extends AbstractPluginManager{

    /**
     * Register AutoLoad dependencies for this plugin.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     */
    public function autoLoad(AutoLoader $loader){

        $pluginDir = plugin_dir_path( dirname( __FILE__ ) );
        $loader->addPsr4('WonderWp\\Plugin\\Jeux\\',array(
            $pluginDir . 'includes'
        ));

        $loader->addClassMap(array(
            'WonderWp\\Plugin\\Jeux\\JeuxAdminController'=>$pluginDir.'admin'.DIRECTORY_SEPARATOR.'JeuxAdminController.php',
            //'WonderWp\\Plugin\\Jeux\\JeuxPublicController'=>$pluginDir.'public'.DIRECTORY_SEPARATOR.'JeuxPublicController.php', //Uncomment this if your plugin has a public controller
        ));

    }

    /**
     * Registers config, controllers, services etc usable by the plugin components
     * @param PContainer $container
     * @return $this
     */
    public function register(PContainer $container)
    {
        parent::register($container);

        //Register Config
        $this->setConfig('path.root',plugin_dir_path( dirname( __FILE__ ) ));
        $this->setConfig('path.base',dirname( dirname( plugin_basename( __FILE__ ) ) ));
        $this->setConfig('path.url',plugin_dir_url( dirname( __FILE__ ) ));
        $this->setConfig('entityName',JeuxEntity::class);
        $this->setConfig('textDomain',WWP_JEUX_TEXTDOMAIN);

        //Register Controllers
        $this->addController(AbstractManager::$ADMINCONTROLLERTYPE,function(){
            return new JeuxAdminController( $this );
        });
        //Uncomment this if your plugin has a public controller
        /*$this->addController(AbstractManager::$PUBLICCONTROLLERTYPE,function(){
            return $plugin_public = new JeuxPublicController($this);
        });*/

        //Register Services
        $this->addService(AbstractService::$HOOKSERVICENAME,$container->factory(function($c){
            //Hook service
            return new JeuxHookService();
        }));
        $this->addService(AbstractService::$MODELFORMSERVICENAME,$container->factory(function($c){
            //Model Form service
            return new JeuxForm();
        }));
        $this->addService(AbstractService::$LISTTABLESERVICENAME, function($container){
            //List Table service
            return new JeuxListTable();
        });
        /* //Uncomment this if your plugin has assets, then create the JeuxAssetService class in the include folder
        $this->addService(AbstractService::$ASSETSSERVICENAME,function(){
            //Asset service
            return new JeuxAssetService();
        });*/
        /* //Uncomment this if your plugin has particular routes, then create the JeuxRouteService class in the include folder
        $this->addService(AbstractService::$ROUTESERVICENAME,function(){
            //Route service
            return new JeuxRouteService();
        });*/
        /* //Uncomment this if your plugin has page settings, then create the JeuxPageSettingsService class in the include folder
        $this->addService(AbstractService::$PAGESETTINGSSERVICENAME,function(){
            //Page settings service
            return new JeuxPageSettingsService();
        });*/
        /* //Uncomment this if your plugin has an api, then create the JeuxApiService class in the include folder
        $this->addService(AbstractService::$APISERVICENAME,function(){
            //Api service
            return new JeuxApiService();
        });*/

        return $this;
    }

}