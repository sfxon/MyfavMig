<?php declare(strict_types=1);

namespace Myfav\Mig\Storefront\Controller;

use Myfav\Mig\Service\MyfavAuthService;
use Shopware\Core\Content\Cms\Exception\PageNotFoundException;
use Shopware\Core\Content\Cms\SalesChannel\SalesChannelCmsPageLoaderInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Storefront\Framework\Cache\Annotation\HttpCache;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Shopware\Storefront\Page\Navigation\NavigationPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class SetupController extends StorefrontController
{
    public function __construct(
        private readonly MyfavAuthService $myfavAuthService,
    ) {
    }

    #[Route(path: '/myfav/mig/setupList', name: 'frontend.myfav.mig.setupList', methods: ['GET'])]
    public function setupList(Request $request, SalesChannelContext $context): Response
    {
        $this->myfavAuthService->validateOrDie($request);

        return $this->renderStorefront('@MyfavMig/storefront/page/myfav-mig-setup/index.html.twig', [
            //'example' => 'Hello world'
        ]);
    }
}