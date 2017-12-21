<?php

namespace ClawRock\AgeVerification\Model\Transformers;

use ClawRock\AgeVerification\Exception\RequiredAgeException;
use ClawRock\AgeVerification\Model\Values\Dob as DobValue;
use Magento\Framework\DataObject;

class Dob
{
    const DOB_FIELD = 'dob';

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date
     */
    protected $dateFilter;

    /**
     * @var \ClawRock\AgeVerification\Helper\Config
     */
    protected $config;

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \ClawRock\AgeVerification\Helper\Config $config
    ) {
        $this->dateFilter = $dateFilter;
        $this->config = $config;
    }

    public function transform(DataObject $request): DobValue
    {
        $date = $this->dateFilter->filter($request->getData(self::DOB_FIELD));

        $dob = new DobValue(new \DateTime($date));

        if (!$dob->isOfAge($this->config->getRequiredAge())) {
            throw new RequiredAgeException();
        }

        return $dob;
    }
}
