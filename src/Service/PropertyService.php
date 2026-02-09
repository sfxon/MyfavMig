<?php declare(strict_types=1);

namespace Myfav\Mig\Service;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;

class PropertyService
{
    public function __construct(
        private readonly EntityRepository $productRepository,
        private readonly EntityRepository $propertyGroupRepository,
        private readonly EntityRepository $propertyRepository,
    ) {
    }

    public function upsertPropertyToProduct(Context $context, string $productId, string $propertyGroupName, string $propertyOptionName, $existingProperties): mixed
    {
        $propertyGroupId = $this->upsertPropertyGroup($context, $propertyGroupName);
        $propertyOptionId = $this->upsertPropertyGroupOption($context, $propertyGroupId, $propertyOptionName);
        $this->upsertProductProperty($context, $productId, $propertyOptionId, $existingProperties);
    }

    // Methode zum Abrufen oder Erstellen einer Eigenschaftsgruppe
    private function upsertPropertyGroup(Context $context, string $propertyGroupName): string
    {
        // Prüfen, ob die Eigenschaftsgruppe bereits existiert
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $propertyGroupName));

        $propertyGroup = $this->propertyGroupRepository->search($criteria, $context)->first();

        // Wenn die Eigenschaftsgruppe nicht existiert, erstellen wir sie
        if (null === $propertyGroup) {
            $id = Uuid::randomHex();

            $propertyData = [
                'id' => $id,
                'name' => $propertyGroupName
            ];

            // Speichern in der Datenbank
            $this->propertyGroupRepository->create([$propertyData], $context);
        } else {
            $id = $propertyGroup->getId();
        }

        return $id;
    }

    // Methode zum Abrufen oder Erstellen einer Eigenschaftsoption
    private function upsertPropertyGroupOption(Context $context, string $propertyGroupId, string $propertyOptionName): string
    {
        // Prüfen, ob die Eigenschaftsoption bereits existiert
        $criteria = new Criteria();
        $criteria->addAssociation('group');
        $criteria->addFilter(new EqualsFilter('name', $propertyOptionName));
        $criteria->addFilter(new EqualsFilter('group.id', $propertyGroupId));

        $propertyOption = $this->propertyRepository->search($criteria, $context)->first();

        // Wenn die Eigenschaftsoption nicht existiert, erstellen wir sie
        if (null === $propertyOption) {
            $id = Uuid::randomHex();

            $propertyOptionData = [
                'id' => $id,
                'name' => $propertyOptionName,
                'groupId' => $propertyGroupId
            ];

            // Speichern in der Datenbank
            $this->propertyRepository->create([$propertyOptionData], $context);
        } else {
            $id = $propertyOption->getId();
        }

        return $id;
    }

    // Methode zum Verknüpfen der Eigenschaftsoption mit einem Produkt
    private function upsertProductProperty(Context $context, string $productId, string $propertyOptionId, $existingProperties): void
    {
        // Aktuelle Eigenschaften des Produkts
        $properties = [];

        // Wenn die Eigenschaft schon existiert, fügen wir sie nicht erneut hinzu.
        foreach($existingProperties as $property) {
            if($property->getId() == $propertyOptionId) {
                return;
            }
        }

        // Neue Eigenschaft hinzufügen
        $properties[] = [
            'id' => $propertyOptionId,
        ];

        // Produkt aktualisieren
        //$product->setProperties($properties);
        $data = [
            'id' => $productId,
            'properties' => $properties
        ];

        // Speichern
        $this->productRepository->update([$data], $context);
    }
}