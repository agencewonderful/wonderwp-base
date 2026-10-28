<?php

namespace WonderWp\Plugin\[PluginName]\Child\Commands;

use WonderWp\Component\DependencyInjection\Container;
use WonderWp\Component\ImportFoundation\Commands\AbstractImporterCommand;
use WonderWp\Component\ImportFoundation\Importers\ImporterInterface;
use WonderWp\Component\PluginSkeleton\AbstractPluginManager;

class Import[Entity]Command extends AbstractImporterCommand
{
    protected AbstractPluginManager $manager;

    public static function getName(): string
    {
        return '[plugin-slug]:import-[entity]';
    }

    protected static function getCommandDescription(): string
    {
        return 'Import [Entity] from external sources';
    }

    protected function bootstrap(array $args = [], array $assocArgs = []): static
    {
        parent::bootstrap($args, $assocArgs);
        $container = Container::getInstance();
        $this->manager = $container[[PLUGIN_CONSTANT] . '.Manager'];

        return $this;
    }

    protected function loadImporter(string $importerKey): ImporterInterface
    {
        return $this->manager->getService($importerKey);
    }
}
