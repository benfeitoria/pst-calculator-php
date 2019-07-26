<?php
/**
 * Created by PhpStorm.
 * User: j
 * Date: 26/07/19
 * Time: 17:12
 */

namespace PSP;


class PSP
{
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
    public function getTotalInvestedValue(){
        return array_sum($this->investedValues);
    }

    /**
     * @param float $percentage
     * @return float|int
     */
    public function getTotalInvestedValueDisconsideringHighestThanPercentageFromTotal(float $percentage = 0.02){
        $totalInvested = $this->getTotalInvestedValue();

        return array_sum(
            array_filter($this->investedValues,function($investedValue) use($totalInvested,$percentage){
                return !$this->isHigherThanPercentageForTotal($investedValue,$totalInvested,$percentage);
            })
        );
    }

    /**
     * @param $investedValue
     * @param $totalInvested
     * @param $percentage
     * @return bool
     */
    private function isHigherThanPercentageForTotal($investedValue, $totalInvested, $percentage){
        $percentageForTotal = $totalInvested * $percentage;
        return $investedValue > $percentageForTotal;
    }

    public static function make(array $investedValues){
        return new self($investedValues);
    }
}