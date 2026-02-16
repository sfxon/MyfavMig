<?php declare(strict_types=1);

namespace Myfav\Mig\Processor;

use Myfav\Mig\Core\Content\MyfavMig\MyfavMigEntity;
use Myfav\Mig\Service\MyfavMigService;
use Myfav\Mig\Service\ProductMediaService;
use Myfav\Mig\Service\ProductService;
use Shopware\Core\Framework\Context;
use Symfony\Component\HttpFoundation\Request;

class CleanupImages
{
    public function __construct(
        private readonly MyfavMigService $myfavMigService,
        private readonly ProductMediaService $productMediaService,
        private readonly ProductService $productService,
    ) {
    }

    /**
     * process
     */
    public function process(Context $context, Request $request, MyfavMigEntity $myfavMig): array
    {
        $selectedEntries = $request->get('selectedEntries');

        // Get local product.
        $product = $this->productService->loadByProductNumber($context, strval($selectedEntries));

        if(null === $product) {
            die('Unknown product with product number ' . $selectedEntries);
        }

        $media = $product->getMedia();

        if(count($media) !== 2) {
            return [
                'status' => 'success'
            ];
        }

        $cover = $product->getCover();

        // Get the media, that is not the cover.
        $nonCoverMedia = null;

        foreach($media as $med) {
            if($med->getId() !== $cover->getId()) {
                $nonCoverMedia = $med;
                break;
            }
        }

        $filename = $nonCoverMedia->getMedia()->getFileName();

        if (!str_ends_with($filename, 'g')) {
            return [
                'status' => 'success'
            ];
        }

        // Remove image from product.
        $this->productMediaService->removeMediaFromProduct($context, $cover->getId());

        $data = [
            'id' => $product->getId(),
            'coverId' => $nonCoverMedia->getId()
        ];
        $this->productService->updateProduct($context, $data);

        return [
            'status' => 'success'
        ];
    }

    /**
     * nextEntry
     */
    public function nextEntry(Context $context, Request $request, MyfavMigEntity $myfavMig): array
    {
        $pos = intval($request->get('pos'));
        $this->myfavMigService->update($context, [ 'id' => $myfavMig->getId(), 'pos' => $pos ]);

        $pos = $myfavMig->getPos();
        $product = $this->productService->getNextProduct($context, $pos);
        $retval = [];

        if(null === $product) {
            $retval = [
                'status' => 'end',
                'productNumber' => ''
            ];
        } else {
            $retval = [
                'status' => 'success',
                'productNumber' => $product->getProductNumber()
            ];
        }

        return $retval;
    }
}