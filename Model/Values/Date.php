<?php

namespace ClawRock\AgeVerification\Model\Values;

class Date
{
    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var int
     */
    private $day;

    /**
     * @var int
     */
    private $month;

    /**
     * @var int
     */
    private $year;

    public function __construct(\DateTime $date)
    {
        $this->date   = $date;
        $this->day   = (int) $date->format('d');
        $this->month = (int) $date->format('m');
        $this->year  = (int) $date->format('Y');
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getFullDate(string $format = 'Y-m-d'): string
    {
        return $this->date->format($format);
    }
}
