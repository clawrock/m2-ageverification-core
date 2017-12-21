<?php

namespace ClawRock\AgeVerification\Setup;

use ClawRock\AgeVerification\Api\Data\PersistableResultInterface;
use ClawRock\AgeVerification\Model\Attribute\Source\Method;
use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Customer\Setup\CustomerSetupFactory
     */
    protected $customerSetupFactory;

    public function __construct(
        \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Magento\Customer\Setup\CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(
            Customer::ENTITY,
            PersistableResultInterface::IS_VERIFIED_FIELD,
            [
                'type' => 'int',
                'label' => 'Age verified',
                'input' => 'boolean',
                'required' => false,
                'system' => false,
                'position' => 200,
            ]
        );
        $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, PersistableResultInterface::IS_VERIFIED_FIELD)
            ->setData('used_in_forms', ['adminhtml_customer'])
            ->save();

        $customerSetup->addAttribute(
            Customer::ENTITY,
            PersistableResultInterface::METHOD_FIELD,
            [
                'type' => 'varchar',
                'input' => 'select',
                'source' => Method::class,
                'label' => 'Age verification method',
                'required' => false,
                'system' => false,
                'position' => 201,
            ]
        );
        $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, PersistableResultInterface::METHOD_FIELD)
            ->setData('used_in_forms', ['adminhtml_customer'])
            ->save();

        $customerSetup->addAttribute(
            Customer::ENTITY,
            PersistableResultInterface::TOKEN_FIELD,
            [
                'type' => 'varchar',
                'label' => 'Age verification token',
                'input' => 'text',
                'required' => false,
                'system' => false,
                'position' => 202,
            ]
        );
        $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, PersistableResultInterface::TOKEN_FIELD)
            ->setData('used_in_forms', ['adminhtml_customer'])
            ->save();
    }
}
