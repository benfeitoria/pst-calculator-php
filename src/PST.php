<?php

namespace PST;

/**
 * Class PST
 * @package PST
 */
class PST
{
    const PERCENTAGE = 0.02;

    /**
     * @var array
     */
    private $investedValues;


    /**
     * PSP constructor.
     * @param array $investedValues
     */
    public function __construct(array $investedValues)
    {
        $this->investedValues = $investedValues;
    }

    /**
     * @return array
     */
    public function getInvestedValues(): array
    {
        return $this->investedValues;
    }

    /**
     * @param array $investedValues
     */
    public function setInvestedValues(array $investedValues): void
    {
        $this->investedValues = $investedValues;
    }

    /**
     * @return float
     */
    private function getTotalInvestedValue()
    {
        return array_sum($this->investedValues);
    }

    /**
     * @param float $percentage
     * @return float|int
     */
    public function getIndex(): int
    {
        $totalInvested = $this->getTotalInvestedValue();

        $index = array_sum(
            array_filter($this->investedValues, function ($investedValue) {
                return !$this->isHigherThanPercentageOfTotal($investedValue);
            })
        ) / $totalInvested;

        return $this->getCode($index);
    }

    /**
     * @param $index
     * @return bool|int
     */
    private function getCode($index): int
    {
        if ($index <= 0.10) {
            return 1;
        }

        if ($index > 0.10 && $index <= 0.33) {
            return 2;
        }

        if ($index > 0.33) {
            return 3;
        }

        return false;
    }

    /**
     * @param $investedValue
     * @return bool
     */
    private function isHigherThanPercentageOfTotal($investedValue): bool
    {
        $percentageOfTotal = $this->getTotalInvestedValue() * self::PERCENTAGE;

        return $investedValue > $percentageOfTotal;
    }

    /**
     * @param array $investedValues
     * @return PST
     */
    public static function make(array $investedValues): PST
    {
        return new self($investedValues);
    }
}
