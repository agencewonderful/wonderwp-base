// Merge into {Plugin}Manager::register() after parent::register($container).
// Use when Manager already exists (basic plugin). For greenfield, prefer
// DoctrinePluginManager.php.template instead.
// Placeholders: see doctrine-archetype.md ({Namespace}, {Plugin}, {PluginConst}, {Entity}).

use {Namespace}\Entity\{Entity};
use {Namespace}\Form\{Entity}Form;
use {Namespace}\ListTable\{Entity}ListTable;
use {Namespace}\Repository\{Entity}Repository;
use {Namespace}\Controller\{Entity}AdminController;
use {Namespace}\Service\{Plugin}Activator;
use {Namespace}\Service\{Plugin}HookService;
use {Namespace}\Service\{Plugin}DoctrineEMLoaderService;
use WonderWp\Plugin\Core\Framework\AbstractPlugin\AbstractDoctrinePluginManager;
use WonderWp\Plugin\Core\Framework\Doctrine\DoctrineEMLoaderServiceInterface;
use WonderWp\Component\PluginSkeleton\AbstractManager;
use WonderWp\Component\Service\ServiceInterface;
use WonderWp\Plugin\Core\Service\WwpAdminChangerService;

// Manager class must extend AbstractDoctrinePluginManager:
// class {Plugin}Manager extends AbstractDoctrinePluginManager

        // Config
        $this->setConfig('entityName', $this->getConfig('entityName', {Entity}::class));
        $this->setConfig('formName', $this->getConfig('formName', {Entity}Form::class));
        $this->setConfig('textDomain', WWP_{PluginConst}_TEXTDOMAIN);
        $this->setConfig('plugin.capability', $this->getConfig('plugin.capability', WwpAdminChangerService::$DEFAULTMODULECAP));
        $this->setConfig('items_per_page', $this->getConfig('items_per_page', 20));
        $this->setConfig('cache.types', $this->getConfig('cache.types', [
            $this->getConfig('entityName'),
            $this->getConfig('path.base'),
        ]));

        // Controllers
        $this->addController(AbstractManager::ADMIN_CONTROLLER_TYPE, function () {
            return new {Entity}AdminController($this);
        });

        // Services
        $this->addService(ServiceInterface::HOOK_SERVICE_NAME, function () {
            return new {Plugin}HookService($this);
        });

        $this->addService(ServiceInterface::REPOSITORY_SERVICE_NAME, function () {
            return new {Entity}Repository(null, null, $this->getConfig('entityName'));
        });

        $this->addService(ServiceInterface::ACTIVATOR_NAME, function () {
            return new {Plugin}Activator($this);
        });

        $this->addService(ServiceInterface::MODEL_FORM_SERVICE_NAME, $container->factory(function () {
            $formName = $this->getConfig('formName');

            return new $formName();
        }));

        $this->addService(ServiceInterface::LIST_TABLE_SERVICE_NAME, function () {
            return new {Entity}ListTable();
        });

        $this->addService(DoctrineEMLoaderServiceInterface::DOCTRINE_EM_LOADER_SERVICE_NAME, function () {
            return new {Plugin}DoctrineEMLoaderService();
        });
