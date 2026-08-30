<?php

if (! function_exists('getTimeOptions')) {
    function getTimeOptions(): array
    {
        $times = [];
        $period = new DatePeriod(
            new DateTime('00:00'),
            new DateInterval('PT30M'),
            new DateTime('23:31')
        );

        foreach ($period as $dt) {
            $times[] = $dt->format('h:i A');
        }

        return $times;
    }
}
