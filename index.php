<?php
function generateSchedule($year, $month, $numMonths = 1) {
    function getMonthName($month) {
        $months = [
            1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель', 5 => 'Май', 6 => 'Июнь',
            7 => 'Июль', 8 => 'Август', 9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'
        ];
        return $months[$month];
    }

    $currentYear = $year;
    $currentMonth = $month;

    for ($i = 0; $i < $numMonths; $i++) {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

        $firstDayOfMonth = strtotime("$currentYear-$currentMonth-01");

        $days = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $days[] = date("Y-m-d", strtotime("$currentYear-$currentMonth-$day"));
        }

        $schedule = [];
        $workDayInterval = 3; 
        $currentWorkDay = $firstDayOfMonth;

        foreach ($days as $day) {
            $currentDay = strtotime($day);

            if (date('N', $currentDay) == 6 || date('N', $currentDay) == 7) {
                $schedule[$day] = " ";
                continue;
            }

            if ($currentDay == $currentWorkDay) {
                // День является рабочим
                $schedule[$day] = "\033[32m+ \033[0m"; // Зелёный цвет для рабочего дня

                // Ищем следующий рабочий день, пропуская выходные
                $nextWorkDay = $currentWorkDay;
                for ($j = 0; $j < $workDayInterval; $j++) {
                    do {
                        $nextWorkDay = strtotime("+1 day", $nextWorkDay);
                    } while (date('N', $nextWorkDay) == 6 || date('N', $nextWorkDay) == 7);
                }

                $currentWorkDay = $nextWorkDay;
            } else {
                $schedule[$day] = " ";
            }
        }

        echo getMonthName($currentMonth) . " " . $currentYear . "\n";
        echo "Дата\tРабочий день\n";
        foreach ($days as $day) {
            echo date('d', strtotime($day)) . "\t" . $schedule[$day] . "\n";
        }

        if ($currentMonth == 12) {
            $currentMonth = 1;
            $currentYear++;
        } else {
            $currentMonth++;
        }
    }
}

generateSchedule(2024, 7, 1);
?>;