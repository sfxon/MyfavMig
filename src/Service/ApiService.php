<?php declare(strict_types=1);

namespace Myfav\Mig\Service;

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

class ApiService
{
    public function __construct(
        private readonly SystemConfigService $systemConfigService,
    ) {
    }

    /**
     * fetchData
     */
    public function fetchData(string $path, string $query): string
    {
        $config = $this->systemConfigService->get('MyfavMig.config');
        $endpointUrl = $this->buildUrl($config, $path, $query);

        // cURL initialisieren
        $ch = curl_init($endpointUrl);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERPWD        => $this->buildUserPwd($config),
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_HTTPHEADER     => $this->buildHttpHeader(),
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($response === false) {
            die('cURL Fehler: ' . curl_error($ch));
        }

        unset($ch);

        return $response;
    }

    private function buildUrl($config, $path, $query) {
        return $config['srcShopApiUrl'] . $path . $query;
    }

    private function buildUserPwd($config) {
        return $config['srcShopApiUsername'] . ':' . $config['srcShopApiKey'];
    }

    private function buildHttpHeader() {
        return [
            'Accept: application/json',
            'Content-Type: application/json'
        ];
    }
}