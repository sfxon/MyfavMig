<?php declare(strict_types=1);

namespace Myfav\Mig\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

class ProductMediaService
{
    public function __construct(private readonly EntityRepository $productMediaRepository)
    {
    }

    public function removeMediaFromProduct(Context $context, string $productMediaId): void
    {
        $this->productMediaRepository->delete([['id' => $productMediaId]], $context);
    }
}