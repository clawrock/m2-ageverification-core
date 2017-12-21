<?php

namespace ClawRock\AgeVerification\Block;

use ClawRock\AgeVerification\Api\Data\MethodInterface;
use Magento\Directory\Block\Data;
use Magento\Framework\DataObject;

class Method extends Data
{
    /**
     * @var MethodInterface
     */
    protected $model;

    /**
     * @var DataObject
     */
    protected $formData;

    public function setModel(MethodInterface $model)
    {
        $this->model = $model;

        return $this;
    }

    public function setFormData(DataObject $data)
    {
        $this->formData = $data;

        return $this;
    }

    public function getFormData(): DataObject
    {
        return $this->formData;
    }
}
