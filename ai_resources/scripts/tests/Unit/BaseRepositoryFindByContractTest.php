<?php

declare(strict_types=1);

namespace WonderWp\Base\AiResources\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WonderWp\Plugin\Core\Framework\Repository\BaseRepository;

/**
 * Host contract: findBy limit/offset stay untyped for Doctrine EntityRepository compatibility.
 */
final class BaseRepositoryFindByContractTest extends TestCase
{
    public function testFindByLimitAndOffsetParametersRemainUntyped(): void
    {
        $method = new \ReflectionMethod(BaseRepository::class, 'findBy');
        $limit = $method->getParameters()[2] ?? null;
        $offset = $method->getParameters()[3] ?? null;

        $this->assertNotNull($limit);
        $this->assertNotNull($offset);
        $this->assertSame('limit', $limit->getName());
        $this->assertSame('offset', $offset->getName());
        $this->assertFalse($limit->hasType(), 'limit must remain untyped (Doctrine parent signature)');
        $this->assertFalse($offset->hasType(), 'offset must remain untyped (Doctrine parent signature)');
    }
}
