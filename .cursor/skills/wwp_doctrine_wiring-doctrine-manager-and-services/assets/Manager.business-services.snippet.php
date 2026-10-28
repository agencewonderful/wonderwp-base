<?php
// Optional merge into {Plugin}Manager when adding domain services beyond CRUD shell.
// Placeholders: see doctrine-archetype.md ({Namespace}, {Plugin}, {Entity}).

use WonderWp\Component\Service\ServiceInterface;
use WonderWp\Plugin\Core\Framework\ServiceResolver\DoctrineRepositoryServiceResolver;
use WonderWp\Plugin\Core\Framework\ServiceResolver\ManagerServiceResolver;
use {Namespace}\Service\{Entity}PersisterService;

// On the Manager class:
// public const ENTITY_PERSISTER_SERVICE = 'entityPersisterService';
// public const MY_DOMAIN_SERVICE = 'myDomainService';

        $this->addService(self::ENTITY_PERSISTER_SERVICE, function () {
            return new {Entity}PersisterService($this);
        });

        // Inject resolvers + persister — never pass a repository instance resolved at wiring time.
        $this->addService(self::MY_DOMAIN_SERVICE, function () {
            return new MyDomainService(
                new DoctrineRepositoryServiceResolver($this, ServiceInterface::REPOSITORY_SERVICE_NAME),
                new ManagerServiceResolver($this, self::ENTITY_PERSISTER_SERVICE)
                // or: $this->getService(self::ENTITY_PERSISTER_SERVICE) when the persister is EM-agnostic
            );
        });
