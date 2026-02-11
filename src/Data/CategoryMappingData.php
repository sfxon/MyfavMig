<?php declare(strict_types=1);

namespace Myfav\Mig\Data;

class CategoryMappingData
{
    private array $entries;

    public function __construct() {
        // The index is the old category-id.
        $this->entries = [
            '221' => [ 'newId' => '0191a3af72e37326aa48dbe2b37e14df', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],

        ];

    }

    public function getEntryByOldCategoryId(string $oldCategoryId)
    {
        if(!isset($this->entries[$oldCategoryId])) {
            return null;
        }

        return $this->entries[$oldCategoryId];
    }
}