<?php

namespace ClawRock\AgeVerification\Model\Values;

class Dob extends Date
{
    /**
     * @var int
     */
    private $age;

    public function __construct(\DateTime $date)
    {
        parent::__construct($date);

        $this->age = (int) $date->diff(new \DateTime('now', $date->getTimezone()))->y;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function isOfAge(int $age): bool
    {
        return $this->getAge() >= $age;
    }
}
