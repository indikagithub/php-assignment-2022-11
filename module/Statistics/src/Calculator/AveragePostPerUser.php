<?php

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

/**
 * Class AveragePostPerUser
 *
 * @package Statistics\Calculator
 */
class AveragePostPerUser extends AbstractCalculator
{

    protected const UNITS = 'posts';

    /**
     * @var int
     */
    public $totalUserCount = [];

    /**
     * @var int
     */
    public $postCount = 0;

    /**
     * @param SocialPostTo $postTo
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $this->postCount++;
        $this->totalUserCount[] = $postTo->getAuthorId();
    }

    /**
     * @return StatisticsTo
     */
    protected function doCalculate(): StatisticsTo
    {
        $value = count(array_unique($this->totalUserCount)) > 0
            ? $this->postCount / count(array_unique($this->totalUserCount))
            : 0;

        return (new StatisticsTo())->setValue(round($value, 2));
        //TODO: if need, round up or down to the nearest whole number
    }
}
