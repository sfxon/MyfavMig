<?php declare(strict_types=1);

namespace Myfav\Mig\Service;

use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\Request;

class MyfavAuthService
{
    public function __construct(
        private readonly SystemConfigService $systemConfigService,
    ) {
    }

    /**
     * validateOrDie
     */
    public function validateOrDie(Request $request): string
    {
        $config = $this->systemConfigService->get('MyfavMig.config.frontendPass');

        if($config === null) {
            die('Not authenticated');
        }

        $password = $request->get('p');

        if($password !== $config) {
            die('Not authenticated');
        }

        return $password;
    }
}