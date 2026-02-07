<?php declare(strict_types=1);

namespace Myfav\Mig\Storefront\Controller;

use Myfav\Mig\Service\MyfavAuthService;
use Myfav\Mig\Service\MyfavMigService;
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
use Symfony\Component\HttpFoundation\RedirectResponse;;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class SetupController extends StorefrontController
{
    public function __construct(
        private readonly MyfavAuthService $myfavAuthService,
        private readonly MyfavMigService $myfavMigService,
        private readonly RouterInterface $router
    ) {
    }

    #[Route(path: 'myfav/mig/setupCreate', name: 'frontend.myfav.mig.setupCreate', methods: ['POST'])]
    public function setupCreate(Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $auth = $this->myfavAuthService->validateOrDie($request);

        $name = $request->request->get('name');
        $pos = 0;
        $state = 0;
        $settings = [];

        $this->myfavMigService->create(
            $salesChannelContext->getContext(),
            $name,
            $pos,
            $state,
            $settings
        );

        $url = $this->router->generate('frontend.myfav.mig.setupList', [ 'message' => 'myfavMigCreated', 'p' => $auth ]);

        return new RedirectResponse($url);
    }

    #[Route(path: '/myfav/mig/setupList', name: 'frontend.myfav.mig.setupList', methods: ['GET'])]
    public function setupList(Request $request, SalesChannelContext $context): Response
    {
        $auth = $this->myfavAuthService->validateOrDie($request);
        $message = $request->get('message');

        return $this->renderStorefront('@MyfavMig/storefront/page/myfav-mig-setup/index.html.twig', [
            'auth' => $auth,
            'message' => $message
        ]);
    }

    #[Route(path: 'myfav/mig/setupNew', name: 'frontend.myfav.mig.setupNew', methods: ['GET'])]
    public function setupNew(Request $request, SalesChannelContext $context): Response
    {
        $auth = $this->myfavAuthService->validateOrDie($request);

        return $this->renderStorefront('@MyfavMig/storefront/page/myfav-mig-setup/new.html.twig', [
            'auth' => $auth
        ]);
    }
}