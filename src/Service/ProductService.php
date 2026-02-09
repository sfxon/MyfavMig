<?php declare(strict_types=1);

namespace Myfav\Mig\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;

class ProductService
{
    public function __construct(
        private readonly EntityRepository $productRepository,
    ) {
    }

    public function loadByProductNumber(Context $context, string $productNumber): mixed
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('productNumber', $productNumber));
        return $this->productRepository->search($criteria, $context)->first();
    }

    public function updateProduct($context, $data) {
        $this->productRepository->update([ $data ], $context);
    }
}