<?php declare(strict_types=1);

namespace Myfav\Mig\Processor;

use Myfav\Mig\Core\Content\MyfavMig\MyfavMigEntity;
use Myfav\Mig\Service\ApiService;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class ProductProcessorOne
{
    public function __construct(
        private readonly ApiService $apiService,
        private readonly SystemConfigService $systemConfigService,
    ) {
    }

    /**
     * validateOrDie
     */
    public function process(Request $request, MyfavMigEntity $myfavMig): string
    {
        $path = '/api/articles';
        $response = $this->apiService->fetchData($path, $request);

        return $response;
    }
}