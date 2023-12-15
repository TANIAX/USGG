<?php

namespace App\Controllers\API\V1;

use App\DTO\Response\Agenda\AgendaDateResponseDTO;
use DateTime;
use CodeIgniter\API\ResponseTrait;

class DatesController extends ApiController
{
    use ResponseTrait;

    public function __construct()
    {
        parent::__construct();
    }

    /*
     * Return JWT if user is authenticated
     * @author: Guillaume cornez
     * @return Object
     */
    public function getAgendaDates($year, $month)
    {
        $firstDayOfMonth = new DateTime("$year-$month-01");
        $firstDayOfMonthClone = clone $firstDayOfMonth;

        $lastDayOfMonth = new DateTime($firstDayOfMonth->format('Y-m-t'));
        $lastDayOfMonthClone = clone $lastDayOfMonth;
        
        // Find the first Monday of the month
        $startDay = $firstDayOfMonth->format('N') == 1 ? $firstDayOfMonth : $firstDayOfMonthClone->modify('-'. $firstDayOfMonthClone->format('N') - 1 .'day');
     
        // Find the last Sunday of the month
        $endDay = $lastDayOfMonth->format('N') == 7 ? $lastDayOfMonth : $lastDayOfMonthClone->modify('+' . 7 - $lastDayOfMonthClone->format('N')  . 'day');
     
        $currentDay = clone $startDay;
        $daysData = [];
     
        while ($currentDay <= $endDay) {
            $date = $currentDay->format('Y-m-d');
            $day = $currentDay->format('d');
            $isCurrentMonth = $currentDay->format('Y-m') === "$year-$month";
            $isToday = $date === date('Y-m-d');
     
            $daysData[] = new AgendaDateResponseDTO($date, $day, $isCurrentMonth, $isToday);
     
            $currentDay->modify('+1 day');
        }
     
        return $this->success($daysData);
    }
}
