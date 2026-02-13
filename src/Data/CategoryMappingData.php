<?php declare(strict_types=1);

namespace Myfav\Mig\Data;

class CategoryMappingData
{
    private array $entries;

    public function __construct() {
        // The index is the old category-id.
        $this->entries = [
            // P
            '111' => [ 'newId' => '0191a3af6f087123b3c9b5e9e5d4dc1e', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '115' => [ 'newId' => '0191a3af6ef6721793e47b902d1262fb', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '116' => [ 'newId' => '0191a3af6ef970989bfccd5aac55ec03', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '121' => [ 'newId' => '0191a3af6ef771a29eb6168b7b60b64a', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '122' => [ 'newId' => '0191a3af6f087123b3c9b5e9e8fef150', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '128' => [ 'newId' => '0191a3af6b4a72aa85236d1678eca715', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '132' => [ 'newId' => '0191a3af6b4b73869f9eb9843aaea727', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '186' => [ 'newId' => '0191a3af6ef870d0a9880d9abdd737c4', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '187' => [ 'newId' => '0191a3af6efa71ca9fec54044adfb492', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '188' => [ 'newId' => '0191a3af6b4f71deaa93a31f70834c7d', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '208' => [ 'newId' => '0191a3af6b4e703b90a4ab9848c2e965', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '209' => [ 'newId' => '0191a3af6f097134913398f0b95ec91d', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '211' => [ 'newId' => '019c4dd2e28f7a7b854d0db7c2d026f4', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '212' => [ 'newId' => '019c4e15aa4e761fa04f989706fbfc5d', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '213' => [ 'newId' => '019c5076fa7b74a38750cb71347ea30b', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '216' => [ 'newId' => '0191a3af6b4373da8e1209c0e76516fe', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '219' => [ 'newId' => '0191a3af72e37326aa48dbe2b37e14df', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '221' => [ 'newId' => '0191a3af72e37326aa48dbe2b37e14df', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '229' => [ 'newId' => '0191a3af6f0a7149a851fffcc7259a9d', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '233' => [ 'newId' => '0191a3af72f573b5a0c1e0375b9b6f0c', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '252' => [ 'newId' => '0191a3af72f37043a50de6ca8e6aecf4', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '312' => [ 'newId' => '0191a3af72ec7065b141b10de81db1d8', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '328' => [ 'newId' => '0191a3af6f077360ab2ca70456cc0dd1', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '331' => [ 'newId' => '0191a3af72e97051a39d8fea4ed34ebc', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '333' => [ 'newId' => '0191a3af72e27330911791e7f6023477', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '336' => [ 'newId' => '0191a3af6ef370b88d4f9bbed918b4cf', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '337' => [ 'newId' => '0191a3af72ef7119a4a17f45ef9e4ec7', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '338' => [ 'newId' => '0191a3af72ee7155b34884ec167188e0', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '343' => [ 'newId' => '0191a3af72fe70c1bf2619cdf43b9aed', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '344' => [ 'newId' => '019990a909dc7e70b589d7ae74f3caf6', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '345' => [ 'newId' => '019990a9230d7a68bf2b0a4b5a2b100b', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '346' => [ 'newId' => '019990a9405c7ad39b7f0acf30c60767', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '347' => [ 'newId' => '019990a965c279c4b5b6f9064181ca51', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '348' => [ 'newId' => '0191a3af730272f9abac9021410ac25a', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '349' => [ 'newId' => '0191a3af72f0704c9fcb4563c590ca74', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '358' => [ 'newId' => '019c4e30d086780eaf9674a922bdbe5d', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '359' => [ 'newId' => '019c4e2837a17f3ca0b617a83e0174a2', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '361' => [ 'newId' => '019c4dfda50c7294827a7214aa2269c4', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '362' => [ 'newId' => '019c4e2e76417c1b827d1cfa987aeea9', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '363' => [ 'newId' => '0191a3af73077258b29a89a4c68c3d0c', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '364' => [ 'newId' => '0191a3af6ef370b88d4f9bbedcbb0671', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '365' => [ 'newId' => '0191a3af6ef870d0a9880d9ab9fb71e0', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '369' => [ 'newId' => '0191a3af72fa71abab364eaf54a94f67', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '370' => [ 'newId' => '0191a3af72fb71ec9c4aba917b080c33', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '371' => [ 'newId' => '0191a3af72fc736a8c00f4f4b286ace5', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '373' => [ 'newId' => '0191a3af72df70d7801a4eb3e66d8067', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '376' => [ 'newId' => '0191a3af6b507170a75c32af509033c0', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '377' => [ 'newId' => '0191a3af72f170eca860083923f4f465', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '379' => [ 'newId' => '0191a3af72f873cd9ad382ae34f14952', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '380' => [ 'newId' => '0191a3af6efb7142adc55c4d5890b5d1', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '381' => [ 'newId' => '019c4ffa9e80705ab43e61ff8ce7d2be', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '382' => [ 'newId' => '0191a3af72ff73b99aa65af8ca13b1cb', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '384' => [ 'newId' => '019c4e201e937f5698a7073205425f0c', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '388' => [ 'newId' => '019c4e193aea73769fed28614e4c03a3', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '389' => [ 'newId' => '019c4e21534f7cab83d41cb21251977c', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '390' => [ 'newId' => '019c4e1c5f787cf69ca57b49a718efb6', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '391' => [ 'newId' => '019c4e1efb4b791bbb250dfdd368c466', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '392' => [ 'newId' => '019c4e1a75bd7b65acfd263c5f50fcc1', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            '393' => [ 'newId' => '019c4e1d69f67e809d2e83eddbd2f726', 'salesChannelId' => '0191a3af8087721b9f90c96bc7c3597f'],
            
            // V
            '48' => [ 'newId' => '0191a3af6b5e70ff81422cdc49b3056c', 'salesChannelId' => '0191a3af8058728094f4643c46697843'],
            '56' => [ 'newId' => '0191a3af6ed3737fa961f6843a52a901', 'salesChannelId' => '0191a3af8058728094f4643c46697843'],
            '355' => [ 'newId' => '0191a3af6ef870d0a9880d9ab9fb71e0', 'salesChannelId' => '0191a3af8058728094f4643c46697843'],
            '357' => [ 'newId' => '0191a3af6b667389acfeaac9062499da', 'salesChannelId' => '0191a3af8058728094f4643c46697843'],
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