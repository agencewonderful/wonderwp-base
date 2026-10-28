<?php

namespace WonderWp\Plugin\[PluginName]\Child\Repositories;

use WonderWp\Component\ImportFoundation\Repositories\RepositoryInterface;

class [Entity]SourceRepository implements RepositoryInterface
{
    public function find($id) { /* TODO */ }

    public function findAll()
    {
        return $this->findBy([]);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return [];
    }

    public function findOneBy(array $criteria) { /* TODO */ }

    public function getClassName() { /* TODO */ }
}
