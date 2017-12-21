<?php

namespace ClawRock\AgeVerification\Block;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;

class Methods extends Template
{
    const METHOD_BLOCK_PATTERN = 'clawrock_av.method.%s';

    /**
     * @var \ClawRock\AgeVerification\Api\MethodRepositoryInterface
     */
    protected $methodRepository;

    /**
     * @var \ClawRock\AgeVerification\Helper\Config
     */
    protected $config;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \ClawRock\AgeVerification\Api\MethodRepositoryInterface $methodRepository,
        \ClawRock\AgeVerification\Helper\Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->methodRepository = $methodRepository;
        $this->config = $config;
    }

    public function getAvailableMethods(): array
    {
        return $this->methodRepository->getAll();
    }

    /**
     * @param string $code
     * @return bool|\ClawRock\AgeVerification\Block\Method
     */
    public function getMethodBlock(string $code)
    {
        return $this->getChildBlock($code);
    }

    public function getFormData()
    {
        /** @var \Magento\Customer\Block\Form\Register $registerBlock */
        $registerBlock = $this->_layout->getBlock('customer_form_register');

        if (!$registerBlock) {
            return new DataObject();
        }

        return $registerBlock->getFormData();
    }

    public function isEnabled()
    {
        return $this->config->isEnabled();
    }
}
