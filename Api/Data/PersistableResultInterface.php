<?php

namespace ClawRock\AgeVerification\Api\Data;

use Magento\Customer\Model\Customer;

interface PersistableResultInterface extends ResultInterface
{
    const TOKEN_FIELD       = 'age_verification_token';
    const IS_VERIFIED_FIELD = 'is_age_verified';
    const METHOD_FIELD      = 'age_verification_method';

    /**
     * Persists this result in customer's data.
     *
     * @param Customer $customer
     * @param string $method
     */
    public function persistInCustomerData(Customer $customer, string $method);
}
