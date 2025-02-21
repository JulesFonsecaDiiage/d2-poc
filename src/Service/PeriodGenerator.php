<?php

namespace App\Service;

class PeriodGenerator
{
    static function generatePeriods(): array
    {
        $periods = [];
        $formatter = new \IntlDateFormatter('fr_FR', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE, null, null, 'MMMM');
        $currentDate = new \DateTime();

        for ($i = 0; $i < 120; $i++) {
            $monthName = ucfirst($formatter->format($currentDate));
            $year = $currentDate->format('Y');
            $periods["$year - $monthName"] = $currentDate->format('Y-m-01');
            $currentDate->modify('-1 month');
        }

        return $periods;
    }
}