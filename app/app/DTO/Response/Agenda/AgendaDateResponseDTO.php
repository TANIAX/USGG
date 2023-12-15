<?php

namespace App\DTO\Response\Agenda;

/**
 * Class AgendaDateResponseDTO
 * 
 * Represents the response data for a AgendaDateResponseDTO operation.
 * @author: Guillaume cornez
 */
class AgendaDateResponseDTO
{
    /**
     * @var string|null The date.
     */
    public string $date;

    /**
     * @var int The day.
     */
    public int $day;

    /**
     * @var bool Is current month.
     */
    public bool $isCurrentMonth;

    /**
     * @var bool Is today.
     */
    public bool $isToday;

    public function __construct(string $date, int $day, bool $isCurrentMonth, bool $isToday)
    {
        // Assign parameters to class properties
        $this->date = $date;
        $this->day = $day;
        $this->isCurrentMonth = $isCurrentMonth;
        $this->isToday = $isToday;
    }
}