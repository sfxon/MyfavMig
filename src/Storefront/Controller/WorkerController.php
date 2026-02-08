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
class WorkerController extends StorefrontController
{
    public function __construct(
        private readonly MyfavAuthService $myfavAuthService,
        private readonly MyfavMigService $myfavMigService,
        private readonly RouterInterface $router
    ) {
    }

    #[Route(path: 'myfav/mig/worker', name: 'frontend.myfav.mig.worker', methods: ['GET'])]
    public function worker(Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $auth = $this->myfavAuthService->validateOrDie($request);
        $message = $request->get('message');

        // Load worker data.
        $myfavMig = $this->myfavMigService->loadById($salesChannelContext->getContext(), $request->get('myfavMigId'));

        if(null === $myfavMig) {
            die('No worker with given id found');
        }

        // Show menu.
        return $this->renderStorefront('@MyfavMig/storefront/page/myfav-mig-worker/index.html.twig', [
            'auth' => $auth,
            'message' => $message,
            'myfavMig' => $myfavMig
        ]);
    }
}