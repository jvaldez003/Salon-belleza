<?php

return [
    'hora_apertura' => env('SALON_HORA_APERTURA', '09:00'),
    'hora_cierre' => env('SALON_HORA_CIERRE', '18:00'),
    'intervalo_minutos' => (int) env('SALON_INTERVALO', 30),
    'dias_laborales' => [1, 2, 3, 4, 5, 6],
];
