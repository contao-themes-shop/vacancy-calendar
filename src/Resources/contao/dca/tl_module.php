<?php

declare(strict_types=1);

/*
 * This file is part of contao-themes-shop/vacancy-calendar.
 *
 * (c) Christopher Boelter - Contao Themes Shop
 *
 */

$GLOBALS['TL_DCA']['tl_module']['metapalettes']['vacancy_calendar'] = [
    'title' => ['name', 'type'],
    'config' => ['vc_calendar', 'vc_months', 'vc_short_day', 'vc_short_month', 'vc_add_style'],
    'protected' => ['protected'],
    'expert' => ['guests', 'cssID'],
];

$GLOBALS['TL_DCA']['tl_module']['metasubpalettes']['vc_add_style'] = [
    'vc_color_empty',
    'vc_color_vacant',
    'vc_color_full',
    'vc_color_option'
];

// Fields
$GLOBALS['TL_DCA']['tl_module']['fields']['vc_calendar'] = [
    'exclude' => true,
    'inputType' => 'select',
    'foreignKey' => 'tl_cts_vacancy_calendar.title',
    'eval' => ['mandatory' => true, 'tl_class' => 'w50'],
    'sql' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'default' => 0],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['vc_months'] = [
    'exclude' => true,
    'inputType' => 'text',
    'default' => 6,
    'eval' => ['mandatory' => true, 'maxlength' => 2, 'tl_class' => 'w50'],
    'sql' => ['type' => 'string', 'length' => 2, 'default' => '6'],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['vc_short_day'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50 m12'],
    'sql' => ['type' => 'boolean', 'default' => 0],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['vc_short_month'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50 m12'],
    'sql' => ['type' => 'boolean', 'default' => 0],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['vc_add_style'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50', 'submitOnChange' => true],
    'sql' => ['type' => 'boolean', 'default' => 0],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['vc_color_empty'] = [
    'inputType' => 'text',
    'eval' => [
        'maxlength' => 6,
        'multiple' => true,
        'size' => 2,
        'colorpicker' => true,
        'isHexColor' => true,
        'decodeEntities' => true,
        'tl_class' => 'w50 wizard',
    ],
    'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['vc_color_vacant'] = [
    'inputType' => 'text',
    'eval' => [
        'maxlength' => 6,
        'multiple' => true,
        'size' => 2,
        'colorpicker' => true,
        'isHexColor' => true,
        'decodeEntities' => true,
        'tl_class' => 'clr w50 wizard',
    ],
    'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['vc_color_full'] = [
    'inputType' => 'text',
    'eval' => [
        'maxlength' => 6,
        'multiple' => true,
        'size' => 2,
        'colorpicker' => true,
        'isHexColor' => true,
        'decodeEntities' => true,
        'tl_class' => 'w50 wizard',
    ],
    'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
];

$GLOBALS['TL_DCA']['tl_module']['fields']['vc_color_option'] = [
    'inputType' => 'text',
    'eval' => [
        'maxlength' => 6,
        'multiple' => true,
        'size' => 2,
        'colorpicker' => true,
        'isHexColor' => true,
        'decodeEntities' => true,
        'tl_class' => 'w50 wizard',
    ],
    'sql' => ['type' => 'string', 'length' => 64, 'default' => ''],
];
