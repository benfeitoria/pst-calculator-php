<?php

namespace PST;

/**
 * Class PST
 * @package PST
 */
class PST
{
    const PERCENTAGE = 0.02;

    const PST_TYPE_NO_INTEREST = 101;
    const PST_TYPE_MUST_PROVE_INTEREST = 102;
    const PST_TYPE_HAVE_INTEREST = 103;

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
    private function getTotalInvestedValue(): float
    {
        return array_sum($this->investedValues);
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        $totalInvested = $this->getTotalInvestedValue();

        $index = array_sum(
            array_filter($this->investedValues, function ($investedValue) {
                return !$this->isHigherThanPercentageOfTotal($investedValue);
            })
        ) / $totalInvested;

        return (int) $index * 100;
    }

    /**
     * @return int
     */
    public function calculateType(): int {
        $index = $this->getIndex();

        switch ($index){
            case $index >=  33:
                $type = self::PST_TYPE_HAVE_INTEREST;
                break;
            case $index >= 10:
                $type = self::PST_TYPE_MUST_PROVE_INTEREST;
                break;
            default:
                $type = self::PST_TYPE_NO_INTEREST;
                break;
        }

        return $type;
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
