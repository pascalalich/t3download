<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'Download',
	array(
		'DownloadConfiguration' => 'list',
		
	),
	// non-cacheable actions
	array(
		'DownloadConfiguration' => 'list',
		
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService($_EXTKEY, 'fileService', 'tx_t3download_sv1', array(
    'title' => 'FileService',
    'description' => 'Provide a download (configuration) file / directory service',
    'subtype' => '',
    'available' => true,
    'priority' => 60,
    'quality' => 80,
    'className' => 'TYPO3\T3download\Service\FileService',
));

?>