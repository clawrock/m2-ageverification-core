<?php

namespace ClawRock\AgeVerification\Observer;

use ClawRock\AgeVerification\Api\Data\PersistableResultInterface;
use ClawRock\AgeVerification\Exception\CustomerAuthenticationException;
use ClawRock\AgeVerification\Exception\MissingMethodException;
use ClawRock\AgeVerification\Exception\RequiredAgeException;
use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

class AuthenticateCustomer implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \ClawRock\AgeVerification\Helper\Config
     */
    protected $config;

    /**
     * @var \ClawRock\AgeVerification\Api\MethodRepositoryInterface
     */
    protected $methodRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \ClawRock\AgeVerification\Helper\Config $config,
        \ClawRock\AgeVerification\Api\MethodRepositoryInterface $methodRepository,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->config = $config;
        $this->methodRepository = $methodRepository;
        $this->logger = $logger;
    }
    public function execute(Observer $observer)
    {
        if (!$this->config->isEnabled()) {
            return;
        }

        $methodCode = $this->request->getParam('av_method');
        if (!$methodCode) {
            throw new LocalizedException(new Phrase('Please select age verification method'));
        }

        try {
            $method = $this->methodRepository->getByCode($methodCode);
        } catch (MissingMethodException $e) {
            $this->logger->critical($e);
            throw new LocalizedException(new Phrase('Invalid age verification method selected'));
        }

        $customer = $observer->getCustomer();

        if ($method->isCustomerAuthenticated($customer)) {
            return;
        }

        try {
            $data = new DataObject($this->request->getParams());
            $result = $method->authenticate($data);

            if ($result instanceof PersistableResultInterface) {
                $result->persistInCustomerData($customer, $methodCode);
            }
        } catch (RequiredAgeException $e) {
            throw new LocalizedException(new Phrase('You must be at least %1 years old.', [
                $this->config->getRequiredAge()
            ]));
        } catch (CustomerAuthenticationException $e) {
            throw new LocalizedException(new Phrase('Age not verified, please check form data.'));
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new LocalizedException(new Phrase('Age verification failed.'));
        }
    }
}
