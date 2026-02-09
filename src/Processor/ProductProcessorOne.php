<?php declare(strict_types=1);

namespace Myfav\Mig\Processor;

use Myfav\Mig\Core\Content\MyfavMig\MyfavMigEntity;
use Myfav\Mig\Service\ApiService;
use Myfav\Mig\Service\ManufacturerService;
use Myfav\Mig\Service\ProductService;
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
        private readonly ManufacturerService $manufacturerService,
        private readonly ProductService $productService,
        private readonly SystemConfigService $systemConfigService,
    ) {
    }

    /**
     * validateOrDie
     */
    public function process(Context $context, Request $request, MyfavMigEntity $myfavMig): string
    {
        $selectedEntries = $request->get('selectedEntries');
        $query = '?filter[0][property]=mainDetail.number&filter[0][value]=' . urlencode($selectedEntries);
        $response = $this->apiService->fetchData('/api/articles', $query);
        $data = json_decode($response, true);

        if(!isset($data['data']) || !is_array($data['data'])) {
            echo 'illegal result:';
            dd($data);
        }

        $data = $data['data'][0];

        // Get local product.
        $product = $this->productService->loadByProductNumber($context, strval($data['mainDetail']['number']));

        if(null === $product) {
            die('Unknown product with product number ' . $data['mainDetailId']);
        }

        // Get manufacturer data.
        $query = '/' . urlencode(strval($data['supplierId'])); // Single manufacturer details can be retrieved via the manufacturer ID: http://my-shop-url/api/manufacturers/id
        $supplier = $this->apiService->fetchData('/api/manufacturers', $query);
        $supplier = json_decode($supplier, true);
        $supplierName = $supplier['data']['name'];

        // Create manufacturer, if no manufacturer with this name exists in Shopware 6.
        $manufacturerId = null;
        $manufacturer = $this->manufacturerService->loadByName($context, $supplierName);

        if($manufacturer !== null) {
            $manufacturerId = $manufacturer->getId();
        } else {
            if($supplierName === '' || $supplierName === null) {
                $supplierName = 'PS'; // Please change this to your default manufacturer name. We could make a setting for this later.
            }

            $manufacturerId = $this->manufacturerService->createManufacturer($context, $supplierName);
        }

        // Custom-Fields
        $customFields['ps_number_of_disks'] = strval($data['mainDetail']['attribute']['attr2']); // Anzahl Disks

        // Schreibe Artikel-Daten.
        $this->updateArticle(
            $context,
            $product->getId(),
            $manufacturerId,
            $customFields
        );

        return $response;
    }

    private function updateArticle(
        Context $context,
        string $productId,
        string $manufacturerId,
        array $customFields)
    {
        $data = [
            'id' => $productId,
            'manufacturerId' => $manufacturerId,
            'customFields' => $customFields
        ];

        $this->productService->updateProduct($context, $data);
    }
}