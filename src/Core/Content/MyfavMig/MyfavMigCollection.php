<?php declare(strict_types=1);

namespace Myfav\Mig\Core\Content\MyfavMig;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                   add(MyfavMigEntity $entity)
 * @method void                   set(string $key, MyfavMigEntity $entity)
 * @method MyfavMigEntity[]    getIterator()
 * @method MyfavMigEntity[]    getElements()
 * @method MyfavMigEntity|null get(string $key)
 * @method MyfavMigEntity|null first()
 * @method MyfavMigEntity|null last()
 */
class MyfavMigCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return MyfavMigEntity::class;
    }
}
