<?php declare(strict_types=1);

namespace Myfav\Mig\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Uuid;

class MyfavMigService
{
    public function __construct(
        private readonly EntityRepository $myfavMigRepository,
    ) {
    }

    /**
     * create
     */
    public function create(
        Context $context,
        string $name,
        int $pos,
        int $state,
        array $settings): string
    {
        $id = Uuid::randomHex();

        $this->myfavMigRepository->create([[
            'id' => $id,
            'name' => $name,
            'pos' => $pos,
            'state' => $state,
            'settings' => $settings
        ]], $context);

        return $id;
    }

    public function fetchAll(Context $context): mixed
    {
        return $this->myfavMigRepository->search(new Criteria(), $context);
    }

    public function loadById(Context $context, string $myfavMigId): mixed
    {
        return $this->myfavMigRepository->search(new Criteria([$myfavMigId]), $context)->first();
    }
}