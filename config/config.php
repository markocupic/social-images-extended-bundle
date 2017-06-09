<?php


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['generatePage'][] = array('Markocupic\SocialImagesExtended', 'addSocialImages');
$GLOBALS['TL_HOOKS']['parseArticles'][] = array('Markocupic\SocialImagesExtended', 'collectNewsImages');
$GLOBALS['TL_HOOKS']['getPageLayout'][] = array('Markocupic\SocialImagesExtended', 'getPageImageCaption');
