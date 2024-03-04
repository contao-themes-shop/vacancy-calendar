<?php

declare(strict_types=1);

use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_cts_vacancy_calendar'] = [
    // Config
    'config'       => [
        'dataContainer'    => DC_Table::class,
        'switchToEdit'     => true,
        'enableVersioning' => true,
        'ctable'           => ['tl_cts_vacancy_calendar_reservation'],
        'sql'              => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],

    // List
    'list'         => [
        'sorting'           => [
            'mode'        => 1,
            'fields'      => ['title'],
            'panelLayout' => 'filter;search,limit',
            'flag'        => 1,
        ],
        'label'             => [
            'fields' => ['title'],
            'label'  => '%s',
        ],
        'global_operations' => [
            'all',
        ],
        'operations'        => [
            'edit',
            'children',
            'delete',
            'show',
        ],
    ],

    // Metapalettes
    'metapalettes' => [
        'default' => [
            'calendar' => ['title'],
        ],
    ],

    // Fields
    'fields'       => [
        'id'     => [
            'sql' => ['type' => 'integer', 'autoincrement' => true],
        ],
        'tstamp' => [
            'sql' => ['type' => 'integer', 'length' => 10, 'default' => '0'],
        ],
        'title'  => [
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
    ],
];
