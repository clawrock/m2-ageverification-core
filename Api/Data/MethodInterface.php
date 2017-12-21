<?php

namespace ClawRock\AgeVerification\Api\Data;

use Magento\Customer\Model\Customer;
use Magento\Framework\DataObject;

interface MethodInterface
{
    /**
     * Determines if method is valid.
     *
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Performs authentication request.
     *
     * @param DataObject $request
     *
     * @return ResultInterface
     */
    public function authenticate(DataObject $request): ResultInterface;

    /**
     * Determines if customer is authenticated with this method.
     *
     * @param Customer $customer
     *
     * @return bool
     */
    public function isCustomerAuthenticated(Customer $customer): bool;

    /**
     * Get method title.
     *
     * @return string
     */
    public function getTitle(): string;
}
