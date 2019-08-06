<?php

namespace PST;

/**
 * Class PST
 * @package PST
 */
class PST
{
    const PERCENTAGE = 0.02;

    const PST_STATUS_NO_INTEREST = 101;
    const PST_STATUS_MUST_PROVE_INTEREST = 102;
    const PST_STATUS_HAVE_INTEREST = 103;

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

        if ($totalInvested == 0) {
            return 0;
        }

        $index = array_sum(
                array_filter($this->investedValues, function ($investedValue) use ($totalInvested) {
                    return !$this->isHigherThanPercentageOfTotal($investedValue, $totalInvested);
                })
            ) / $totalInvested;

        return (int)($index * 100);
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        $index = $this->getIndex();

        if ($index >= 33) {
            $type = self::PST_STATUS_HAVE_INTEREST;
        } elseif ($index >= 10) {
            $type = self::PST_STATUS_MUST_PROVE_INTEREST;
        } else {
            $type = self::PST_STATUS_NO_INTEREST;
        }

        return $type;
    }

    /**
     * @param $investedValue
     * @param null $totalInvested
     * @return bool
     */
    private function isHigherThanPercentageOfTotal($investedValue, $totalInvested = null): bool
    {
        if (is_null($totalInvested)) {
            $totalInvested = $this->getTotalInvestedValue();
        }

        $percentageOfTotal = $totalInvested * self::PERCENTAGE;

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
