<?php declare(strict_types=1);

namespace Myfav\Mig\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;

class CategoryService
{
    public function __construct(
        private readonly EntityRepository $categoryRepository,
    ) {
    }

    public function getNewCategoriesIdArray(Context $context, array $oldCategories)
    {
        dd($oldCategories);
    }
}