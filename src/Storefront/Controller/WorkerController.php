<?php declare(strict_types=1);

namespace Myfav\Mig\Storefront\Controller;

use Myfav\Mig\Service\MyfavAuthService;
use Myfav\Mig\Service\MyfavMigService;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    #[Route(path: 'myfav/mig/workerReset', name: 'frontend.myfav.mig.workerReset', methods: ['GET'])]
    public function workerReset(Request $request, SalesChannelContext $salesChannelContext): RedirectResponse
    {
        $auth = $this->myfavAuthService->validateOrDie($request);

        // Load worker data.
        $myfavMig = $this->myfavMigService->loadById($salesChannelContext->getContext(), $request->get('myfavMigId'));

        if(null === $myfavMig) {
            die('No worker with given id found');
        }

        $data =  [
            'id' => $myfavMig->getId(),
            'pos' => 0,
            'state' => 0
        ];
        $this->myfavMigService->update($salesChannelContext->getContext(), $data);

        $url = $this->router->generate('frontend.myfav.mig.work', [ 'message' => 'myfavMigReset', 'p' => $auth, 'myfavMigId' => $myfavMig->getId() ]);
        return new RedirectResponse($url);
    }

    #[Route(path: 'myfav/mig/work', name: 'frontend.myfav.mig.work', methods: ['GET'])]
    public function work(Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $auth = $this->myfavAuthService->validateOrDie($request);
        $serviceId = $request->get('controllerName');

        // Load worker data.
        $myfavMig = $this->myfavMigService->loadById($salesChannelContext->getContext(), $request->get('myfavMigId'));

        if(null === $myfavMig) {
            die('No worker with given id found');
        }

        if (!$this->container->has($myfavMig->getControllerName())) {
           die('Service with name ' . $myfavMig->getControllerName() . ' not found');
        }

        $service = $this->container->get($myfavMig->getControllerName());
        $result = $service->process($salesChannelContext->getContext(), $request, $myfavMig);

        return new JsonResponse($result);
    }

    #[Route(path: 'myfav/mig/workGetNextEntryToProcess', name: 'frontend.myfav.mig.workGetNextEntryToProcess', methods: ['GET'])]
    public function workGetNextEntryToProcess(Request $request, SalesChannelContext $salesChannelContext): Response
    {
        $auth = $this->myfavAuthService->validateOrDie($request);
        $serviceId = $request->get('controllerName');

        // Load worker data.
        $myfavMig = $this->myfavMigService->loadById($salesChannelContext->getContext(), $request->get('myfavMigId'));

        if(null === $myfavMig) {
            die('No worker with given id found');
        }

        if (!$this->container->has($myfavMig->getControllerName())) {
           die('Service with name ' . $myfavMig->getControllerName() . ' not found');
        }

        $service = $this->container->get($myfavMig->getControllerName());
        $result = $service->nextEntry($salesChannelContext->getContext(), $request, $myfavMig);

        return new JsonResponse($result);
    }
}