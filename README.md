# AgeVerification

Module is responsible for mediating between frontend, controllers and specific verification methods.

## Problem

Adults only sites need to verify customers' age before letting them in. Each country MAY request different methods of
verification and/or services which provide such capabilities. Each service MAY vary on available methods,  required
fields, configuration and frontend display. Additionally each site MAY require single method or multiple, one display
for registration and checkout or two different.

## Solution

Common high-level api needs to be provided in order to instantiate proper authorization method. This method is fully
responsible for transforming request into internal format, requesting its underlying integration, parsing response from
it and returning authorization status.

If any "in-between" behaviors are required, it's also method's responsibility (validation, logging, preserving data).


## High-level API

```
try {
    $method = $methodFactory->create($request->getMethodName());

    $result = $method->authorize(
        $this->requestToDataObject($request)
    );

    if ($result->isAuthorized()) {
        // in case when customer is created later (checkout order placed), we can serialize result object and
        // unserialize it later to perform persist method
        // transaction start
        $customer = $this->createCustomer();
        $result->persistInCustomerData($customer);
        // transaction end
        return successResponse;
    }
} catch (\Exception $e) {
    logException($e);
}

return errorResponse;

```

## Development
1. Clone the repository
2. [Get Magento authentication keys](http://devdocs.magento.com/guides/v2.2/install-gde/prereq/connect-auth.html)
3. Create auth.json in root directory (or enter keys during composer install):
```
{
   "http-basic": {
     "repo.magento.com": {
       "username": "public_key",
       "password": "private_key"
     }
   }
}
```
4. composer install
5. vendor/phpunit/phpunit/phpunit -c .

## TODO

- Move registering methods to own xml schema
