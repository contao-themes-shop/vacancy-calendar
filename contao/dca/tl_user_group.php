<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

// Palettes
PaletteManipulator::create()
    ->addLegend('vc_vacancy_calendar_legend', 'amg_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField(['vc_vacancy_calendar', 'vc_vacancy_calendar_permission'], 'vc_vacancy_calendar_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_user_group');

// Fields
$GLOBALS['TL_DCA']['tl_user_group']['fields']['vc_vacancy_calendar'] = [
    'inputType'  => 'checkbox',
    'foreignKey' => 'tl_cts_vacancy_calendar.title',
    'eval'       => ['multiple' => true],
    'sql'        => ['type' => 'blob', 'notnull' => false, 'default' => null],
    'relation'   => ['type' => 'hasMany', 'load' => 'lazy'],
];

$GLOBALS['TL_DCA']['tl_user_group']['fields']['vc_vacancy_calendar_permission'] = [
    'inputType' => 'checkbox',
    'options'   => ['create', 'delete'],
    'reference' => &$GLOBALS['TL_LANG']['MSC'],
    'eval'      => ['multiple' => true],
    'sql'       => ['type' => 'blob', 'notnull' => false, 'default' => null],
];
