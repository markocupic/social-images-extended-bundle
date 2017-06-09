<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 09.06.2017
 * Time: 20:05
 */


/**
 * Extend tl_page palettes
 */
foreach ($GLOBALS['TL_DCA']['tl_page']['palettes'] as $k=>$v)
{
    $GLOBALS['TL_DCA']['tl_page']['palettes'][$k] = str_replace('socialImage', 'socialImage,socialDescription', $v);
}


/**
 * Add the field to tl_page
 */
if(!isset($GLOBALS['TL_LANG']['tl_page']['socialImage']))
{
    /** Is already defined in system/modules/social_images/dca/tl_page.php **/
    $GLOBALS['TL_DCA']['tl_page']['fields']['socialImage'] = array
    (
        'label' => &$GLOBALS['TL_LANG']['tl_page']['socialImage'],
        'exclude' => true,
        'inputType' => 'fileTree',
        'eval' => array('files' => true, 'filesOnly' => true, 'fieldType' => 'radio', 'extensions' => $GLOBALS['TL_CONFIG']['validImageTypes'], 'tl_class' => 'clr'),
        'sql' => "binary(16) NULL"
    );
}


/**
 * Add the field to tl_page
 */
$GLOBALS['TL_DCA']['tl_page']['fields']['socialDescription'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_page']['socialDescription'],
    'exclude'                 => true,
    'search'                  => true,
    'inputType'               => 'text',
    'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);