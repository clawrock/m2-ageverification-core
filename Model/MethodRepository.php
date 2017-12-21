<?php

namespace ClawRock\AgeVerification\Model;

use ClawRock\AgeVerification\Api\Data\MethodInterface;
use ClawRock\AgeVerification\Api\MethodRepositoryInterface;
use ClawRock\AgeVerification\Exception\MissingMethodException;

class MethodRepository implements MethodRepositoryInterface
{
    /**
     * @var \ClawRock\AgeVerification\Helper\Config
     */
    protected $config;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(
        \ClawRock\AgeVerification\Helper\Config $config,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->config        = $config;
        $this->objectManager = $objectManager;
    }

    public function getAll(): array
    {
        $result = [];

        foreach ($this->config->getMethods() as $vendor => $methods) {
            foreach ($methods as $code => $model) {
                if (!$this->config->isMethodActive($vendor, $code)) {
                    continue;
                }
                $method = $this->getModel($model);
                $result[$vendor . '_' . $code] = $method;
            }
        }

        return $result;
    }

    public function getByCode(string $code): MethodInterface
    {
        $method = $this->getAll()[$code] ?? false;

        if (!$method) {
            throw new MissingMethodException();
        }

        return $method;
    }

    /**
     * @param string $class
     * @return MethodInterface
     */
    protected function getModel(string $class)
    {
        return $this->objectManager->get($class);
    }
}
