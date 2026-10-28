// Merge into {Plugin}HookService when the class already exists.
// For greenfield, prefer DoctrinePluginHookService.php.template (includes customizeMenus).
// Placeholders: see doctrine-archetype.md ({Namespace}, {Plugin}, {PluginConst}, {PluginMenuTitle}).

use WonderWp\Component\PluginSkeleton\AbstractManager;
use WonderWp\Plugin\Core\Framework\AbstractPlugin\DoctrineHookServiceTrait;
use WonderWp\Plugin\Core\Framework\AbstractPlugin\Hooks\AbstractHookService;

class {Plugin}HookService extends AbstractHookService
{
    use DoctrineHookServiceTrait;

    public function register()
    {
        $this->addAction('admin_menu', [$this, 'customizeMenus']);
        $this->addAction('plugins_loaded', [$this, 'loadTextdomain']);

        $this->registerDoctrinePluginHooks();

        return $this;
    }

    public function customizeMenus()
    {
        $adminController = $this->manager->getController(AbstractManager::ADMIN_CONTROLLER_TYPE);
        $callable = [$adminController, 'route'];

        add_submenu_page(
            'wonderwp-modules',
            __('{PluginMenuTitle}', WWP_{PluginConst}_TEXTDOMAIN),
            __('{PluginMenuTitle}', WWP_{PluginConst}_TEXTDOMAIN),
            $this->manager->getConfig('plugin.capability'),
            WWP_PLUGIN_{PluginConst}_NAME,
            $callable
        );
    }
}
