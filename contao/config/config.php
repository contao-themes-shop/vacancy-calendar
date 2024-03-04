<?php

declare(strict_types=1);

use Contao\ArrayUtil;

if (! array_key_exists('content', $GLOBALS['BE_MOD'])) {
    $GLOBALS['BE_MOD']['content'] = [];
}

ArrayUtil::arrayInsert(
    $GLOBALS['BE_MOD']['content'],
    count($GLOBALS['BE_MOD']['content']),
    [
        'cts_vacancy_calendar' => [
            'tables' => ['tl_cts_vacancy_calendar', 'tl_cts_vacancy_calendar_reservation'],
        ],
    ]
);
