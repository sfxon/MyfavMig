<?php declare(strict_types=1);

namespace Myfav\Mig\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;

class ManufacturerService
{
    public function __construct(
        private readonly EntityRepository $manufacturerRepository,
    ) {
    }

    public function createManufacturer(Context $context, string $name) {
        $id = Uuid::randomHex();

        $this->manufacturerRepository->create([[
            'id' => $id,
            'name' => $name,
        ]], $context);

        return $id;
    }

    public function loadByName(Context $context, string $name): mixed
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));
        return $this->manufacturerRepository->search($criteria, $context)->first();
    }
}