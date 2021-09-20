<?php

declare(strict_types=1);

/*
 * This file is part of contao-themes-shop/vacancy-calendar.
 *
 * (c) Christopher Boelter - Contao Themes Shop
 *
 */

$GLOBALS['TL_DCA']['tl_cts_vacancy_calendar_reservation'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'switchToEdit' => true,
        'enableVersioning' => true,
        'ptable' => 'tl_cts_vacancy_calendar',
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'key',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode' => 4,
            'fields' => ['begin'],
            'flag' => 7,
            'headerFields' => ['title'],
        ],
        'label' => [
            'fields' => ['title'],
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'edit' => [
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm']
                    .'\'))return false;Backend.getScrollOffset()"',
            ],
        ],
    ],

    'metapalettes' => [
        'default' => [
            'reservation' => ['title'],
            'date' => ['begin', 'end'],
            'note' => ['note'],
        ],
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => ['type' => 'integer', 'autoincrement' => true],
        ],
        'pid' => [
            'sql' => ['type' => 'integer', 'length' => 10, 'default' => '0'],
        ],
        'tstamp' => [
            'sql' => ['type' => 'integer', 'length' => 10, 'default' => '0'],
        ],
        'title' => [
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'begin' => [
            'exclude' => true,
            'inputType' => 'text',
            'flag' => 7,
            'eval' => ['mandatory' => true, 'rgxp' => 'date', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => ['type' => 'integer', 'length' => 10, 'default' => '0'],
        ],
        'end' => [
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'rgxp' => 'date', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => ['type' => 'integer', 'length' => 10, 'default' => '0'],
        ],
        'note' => [
            'exclude' => true,
            'search' => true,
            'inputType' => 'textarea',
            'eval' => [
                'maxlength' => 500,
                'tl_class' => 'clr long',
                'style' => 'min-height: 50px;',
            ],
            'sql' => ['type' => 'string', 'length' => 500, 'default' => ''],
        ],
    ],
];
