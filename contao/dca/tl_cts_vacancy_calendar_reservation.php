<?php

declare(strict_types=1);

use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_cts_vacancy_calendar_reservation'] = [
    // Config
    'config' => [
        'dataContainer'    => DC_Table::class,
        'switchToEdit'     => true,
        'enableVersioning' => true,
        'ptable'           => 'tl_cts_vacancy_calendar',
        'sql'              => [
            'keys' => [
                'id'  => 'primary',
                'pid' => 'key',
            ],
        ],
    ],

    // List
    'list'   => [
        'sorting'           => [
            'mode'         => 4,
            'fields'       => ['begin'],
            'flag'         => 7,
            'headerFields' => ['title'],
        ],
        'label'             => [
            'fields' => ['title'],
        ],
        'global_operations' => [
            'all',
        ],
        'operations'        => [
            'edit',
            'delete',
            'show',
        ],
    ],

    'metapalettes' => [
        'default' => [
            'reservation' => ['title', 'isOption'],
            'date'        => ['begin', 'end'],
            'note'        => ['note'],
        ],
    ],

    // Fields
    'fields'       => [
        'id'       => [
            'sql' => ['type' => 'integer', 'autoincrement' => true],
        ],
        'pid'      => [
            'sql' => ['type' => 'integer', 'length' => 10, 'default' => '0'],
        ],
        'tstamp'   => [
            'sql' => ['type' => 'integer', 'length' => 10, 'default' => '0'],
        ],
        'title'    => [
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'begin'    => [
            'exclude'   => true,
            'inputType' => 'text',
            'flag'      => 7,
            'eval'      => ['mandatory' => true, 'rgxp' => 'date', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => ['type' => 'integer', 'length' => 10, 'default' => '0'],
        ],
        'end'      => [
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'rgxp' => 'date', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => ['type' => 'integer', 'length' => 10, 'default' => '0'],
        ],
        'isOption' => [
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'checkbox',
            'eval'      => [
                'tl_class' => 'w50 m12',
            ],
            'sql'       => ['type' => 'boolean', 'default' => 0],
        ],
        'note'     => [
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'textarea',
            'eval'      => [
                'maxlength' => 500,
                'tl_class'  => 'clr long',
                'style'     => 'min-height: 50px;',
            ],
            'sql'       => ['type' => 'string', 'length' => 500, 'default' => ''],
        ],
    ],
];
