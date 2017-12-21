<?php

namespace ClawRock\AgeVerification\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Method extends AbstractSource
{
    /**
     * @var \ClawRock\AgeVerification\Api\MethodRepositoryInterface
     */
    protected $methodRepository;

    /**
     * @var array
     */
    protected $options;

    public function __construct(
        \ClawRock\AgeVerification\Api\MethodRepositoryInterface $methodRepository
    ) {
        $this->methodRepository = $methodRepository;
    }

    public function getAllOptions()
    {
        if ($this->options === null) {
            $this->options = [];
            $methods = $this->methodRepository->getAll();
            foreach ($methods as $code => $method) {
                $this->options[] = [
                    'value' => $code,
                    'label' => $method->getTitle(),
                ];
            }
            array_unshift($this->options, [
                'label' => $this->getAttribute()->getIsRequired() ? '' : ' ',
                'value' => '',
            ]);
        }
        return $this->options;
    }

    public function getOptionArray()
    {
        $options = [];
        foreach ($this->getAllOptions() as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }
}
