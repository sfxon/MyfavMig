<?php declare(strict_types=1);

namespace Myfav\Mig\Service;

use Shopware\Core\System\SystemConfig\SystemConfigService;

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