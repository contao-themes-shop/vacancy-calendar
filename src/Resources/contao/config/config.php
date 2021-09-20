<?php

declare(strict_types=1);

/*
 * This file is part of contao-themes-shop/vacancy-calendar.
 *
 * (c) Christopher Boelter - Contao Themes Shop
 *
 */

if (!array_key_exists('content', $GLOBALS['BE_MOD'])) {
    $GLOBALS['BE_MOD']['content'] = [];
}

array_insert(
    $GLOBALS['BE_MOD']['content'],
    count($GLOBALS['BE_MOD']['content']),
    [
        'cts_vacancy_calendar' => [
            'tables' => ['tl_cts_vacancy_calendar', 'tl_cts_vacancy_calendar_reservation'],
        ],
    ]
);
