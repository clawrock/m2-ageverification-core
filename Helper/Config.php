<?php

namespace ClawRock\AgeVerification\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const XPATH_MODELS        = 'clawrock_av/models';
    const XPATH_METHOD_ACTIVE = 'clawrock_av_methods/%s/%s';
    const XPATH_ENABLED       = 'clawrock_av/general/active';
    const XPATH_REQUIRED_AGE  = 'clawrock_av/general/required_age';

    public function isEnabled($store = null): bool
    {
        return (bool) $this->scopeConfig->getValue(self::XPATH_ENABLED, ScopeInterface::SCOPE_STORE, $store);
    }

    public function getRequiredAge($store = null): int
    {
        return (int) $this->scopeConfig->getValue(self::XPATH_REQUIRED_AGE, ScopeInterface::SCOPE_STORE, $store);
    }

    public function isMethodActive($vendor, $code, $store = null): bool
    {
        $path = sprintf(self::XPATH_METHOD_ACTIVE, $vendor, $code);

        return (bool) $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $store);
    }

    public function getMethods(): array
    {
        return $this->scopeConfig->getValue(
            self::XPATH_MODELS,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
    }
}
