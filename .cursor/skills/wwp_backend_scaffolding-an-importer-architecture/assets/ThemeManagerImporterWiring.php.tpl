<?php

namespace WonderWp\Plugin\[PluginName]\Child;

use WonderWp\Component\DependencyInjection\Container;
use WonderWp\Component\ImportFoundation\Persisters\WpPostPersister;
use WonderWp\Plugin\[PluginName]\[PluginName]Manager;
use WonderWp\Plugin\[PluginName]\Child\Importer\[Entity]Importer;
use WonderWp\Plugin\[PluginName]\Child\Repositories\[Entity]DestinationRepository;
use WonderWp\Plugin\[PluginName]\Child\Repositories\[Entity]SourceRepository;
use WonderWp\Plugin\[PluginName]\Child\Syncers\[Entity]Syncer;
use WonderWp\Plugin\[PluginName]\Child\Transformers\[Entity]DestinationTransformer;
use WonderWp\Plugin\[PluginName]\Child\Transformers\[Entity]SourceTransformer;

class [PluginName]ThemeManager extends [PluginName]Manager
{
    const [ENTITY]_IMPORTER_KEY = '[entity-slug]';
    const [ENTITY]_SOURCE_REPOSITORY = '[entity-slug].source.repository';
    const [ENTITY]_SOURCE_TRANSFORMER = '[entity-slug].source.transformer';
    const [ENTITY]_DEST_REPOSITORY = '[entity-slug].destination.repository';
    const [ENTITY]_DEST_TRANSFORMER = '[entity-slug].destination.transformer';
    const POST_PERSISTER = '[entity-slug].persister';
    const [ENTITY]_SYNCER = '[entity-slug].syncer';

    public function register(Container $container)
    {
        $this->setConfig('path.child.root', plugin_dir_path(dirname(__FILE__)));

        parent::register($container);

        $this->addService(self::[ENTITY]_SOURCE_REPOSITORY, function () {
            return new [Entity]SourceRepository();
        });

        $this->addService(self::[ENTITY]_SOURCE_TRANSFORMER, function () {
            return new [Entity]SourceTransformer();
        });

        $this->addService(self::[ENTITY]_DEST_REPOSITORY, function () {
            return new [Entity]DestinationRepository();
        });

        $this->addService(self::[ENTITY]_DEST_TRANSFORMER, function () {
            return new [Entity]DestinationTransformer();
        });

        $this->addService(self::POST_PERSISTER, function () {
            return new WpPostPersister();
        });

        $this->addService(self::[ENTITY]_SYNCER, function () {
            return new [Entity]Syncer(
                $this->getService(self::POST_PERSISTER)
            );
        });

        $this->addService(self::[ENTITY]_IMPORTER_KEY, function () {
            return new [Entity]Importer(
                $this->getService(self::[ENTITY]_SOURCE_REPOSITORY),
                $this->getService(self::[ENTITY]_SOURCE_TRANSFORMER),
                $this->getService(self::[ENTITY]_DEST_REPOSITORY),
                $this->getService(self::[ENTITY]_DEST_TRANSFORMER),
                $this->getService(self::[ENTITY]_SYNCER)
            );
        });

        return $this;
    }
}
