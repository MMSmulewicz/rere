<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'ReRe.' . $_EXTKEY, 'rerefrontend', array(
    'Pruefling' => 'show'
	),
	// non-cacheable actions
	array(
    'Pruefling' => 'show'
	)
);
