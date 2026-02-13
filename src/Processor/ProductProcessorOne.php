<?php declare(strict_types=1);

namespace Myfav\Mig\Processor;

use Myfav\Mig\Core\Content\MyfavMig\MyfavMigEntity;
use Myfav\Mig\Data\CategoryMappingData;
use Myfav\Mig\Service\ApiService;
use Myfav\Mig\Service\CategoryService;
use Myfav\Mig\Service\ManufacturerService;
use Myfav\Mig\Service\MyfavMigService;
use Myfav\Mig\Service\ProductService;
use Myfav\Mig\Service\PropertyService;
use Shopware\Core\Framework\Context;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\Request;

class ProductProcessorOne
{
    public function __construct(
        private readonly ApiService $apiService,
        private readonly CategoryService $categoryService,
        private readonly ManufacturerService $manufacturerService,
        private readonly MyfavMigService $myfavMigService,
        private readonly ProductService $productService,
        private readonly PropertyService $propertyService,
        private readonly SystemConfigService $systemConfigService,
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

        // This is a product search, but not what we want to do right now, when we want to get a specific entry.
        // $query = '?filter[0][property]=mainDetail.number&filter[0][value]=' . urlencode($selectedEntries);
        $query = $selectedEntries . '?useNumberAsId=true';

        $response = $this->apiService->fetchData('/api/articles/', $query);
        $data = json_decode($response, true);



        if(!isset($data['data']) || !is_array($data['data'])) {
            // Artikel lokal deaktivieren.
            $updateData = [
                'id' => $product->getId(),
                'active' => false
            ];

            return [
                'status' => 'Product not found.',
                'detailMessage' => 'Product with product number ' . $product->getProductNumber() . ' not found on remote server.'
            ];
        }

        $data = $data['data'];

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

        // Stati
        $active = $data['active'];
        $isCloseout = $data['lastStock'];
        $minPurchase = $data['mainDetail']['minPurchase'];

        // Properties laden.
        // Lade zuerst die Option Groups, damit wir an die Namen der Optionen kommen.
        $optionGroupResponse = $this->apiService->fetchData('/api/propertyGroups', '');
        $optionGroupResponse = json_decode($optionGroupResponse, true);
        $optionGroups = $optionGroupResponse['data'];
        $saveProperties = [];

        foreach($data['propertyValues'] as $pvalue) {
            $optionId = $pvalue['optionId'];
            $optionValueName = $pvalue['value'];

            foreach($optionGroups as $optionGroup) {
                foreach($optionGroup['options'] as $option) {
                    if($option['id'] == $optionId) {
                        $saveProperties[] = [
                            'groupName' => $option['name'],
                            'valueName' => $optionValueName
                        ];
                    }
                }
            }
        }

        // Get categories.
        $categoryMappingData = new CategoryMappingData();
        $mappedCategoryData = [];

        foreach($data['categories'] as $oldCategory) {
            $query = '/' . urlencode(strval($oldCategory['id'])); // Single manufacturer details can be retrieved via the manufacturer ID: http://my-shop-url/api/manufacturers/id
            $category = $this->apiService->fetchData('/api/categories', $query);
            $category = json_decode($category, true);

            // This works with a mapping. For now, the data is hard coded in a helper class Data\CategoryMappingData.
            // If a category is unknown, the system should report an error and stop. Then the operator (you) has to add the entry to the mapping class by hand.
            // After that, processing can be continued. That way, also the sales channel and the correct new categories are assigned, without having to build complex logic or saving technology.
            // At this stage, a tool like this is only to be run at the beginning of a project, and after that it gets obsolete. So that's why I keep things simple here.
            $mappingData = $categoryMappingData->getEntryByOldCategoryId(strval($category['data']['id']));

            if($mappingData === null) {
                return [
                    'status' => 'category mapping not found',
                    'oldCategoryData' => $oldCategory
                ];
            }
            
            $mappedCategoryData[] = $mappingData;
        }

        // Write data.
        $this->updateProduct(
            $context,
            $product->getId(),
            $data['name'],
            $data['descriptionLong'],
            $manufacturerId,
            $customFields,
            $active,
            $isCloseout,
            $minPurchase
        );

        $this->updateProductProperties($context, $product->getId(), $saveProperties);
        $this->updateProductCategories($context, $product->getId(), $mappedCategoryData);
        $this->updateProductSalesChannels($context, $product->getId(), $mappedCategoryData);

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

    private function updateProduct(
        Context $context,
        string $productId,
        string $productName,
        string $productDescription,
        string $manufacturerId,
        array $customFields,
        bool $active,
        bool $isCloseout,
        int $minPurchase)
    {
        $data = [
            'id' => $productId,
            'name' => $productName,
            'manufacturerId' => $manufacturerId,
            'customFields' => $customFields,
            'description' => $productDescription,
            'active' => $active,
            'isCloseout' => $isCloseout,
            'minPurchase' => $minPurchase
        ];

        $this->productService->updateProduct($context, $data);
    }

    private function updateProductCategories($context, $productId, $mappedCategoryData)
    {
        // This will only add up new data. If you wanted to remove old assigned categories, you'd have to delete them first.
        // Read this for details about the topic: https://developer.shopware.com/docs/guides/plugins/plugins/framework/data-handling/replacing-associated-data.html
        $categoryIds = [];
        $alreadyAssignedCategories = [];

        foreach($mappedCategoryData as $cat) {
            if(!isset($alreadyAssignedCategories[$cat['newId']])) {
                $categoryIds[] = [
                    'id' => $cat['newId']
                ];
                $alreadyAssignedCategories[$cat['newId']] = $cat['newId'];
            }
        }

        $data = [
            'id' => $productId,
            'categories' => $categoryIds
        ];

        $this->productService->updateProduct($context, $data);
    }

    private function updateProductProperties($context, $productId, $saveProperties)
    {
        foreach($saveProperties as $prop) {
            $product = $this->productService->loadByProductId($context, $productId);
            $existingProperties = $product->getProperties();
            $this->propertyService->upsertPropertyToProduct($context, $productId, $prop['groupName'], $prop['valueName'], $existingProperties);
        }
    }

    private function updateProductSalesChannels($context, $productId, $mappedCategoryData)
    {
        // This will only add up new data. If you wanted to remove old assigned categories, you'd have to delete them first.
        // Read this for details about the topic: https://developer.shopware.com/docs/guides/plugins/plugins/framework/data-handling/replacing-associated-data.html
        $salesChannelIds = [];
        $alreadyAssignedSalesChannels = [];

        foreach($mappedCategoryData as $cat) {
            if(!isset($alreadyAssignedSalesChannels[$cat['salesChannelId']])) {
                $salesChannelIds[] = [
                    'id' => $cat['salesChannelId']
                ];

                $alreadyAssignedSalesChannels[$cat['salesChannelId']] = $cat['salesChannelId'];
            }
        }

        $data = [
            'id' => $productId,
            'salesChannels' => $salesChannelIds
        ];

        $this->productService->updateProduct($context, $data);
    }
}