<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'TYPO3.' . $_EXTKEY,
	'Download',
	array(
		'DownloadConfiguration' => 'list, download, new',
		
	),
	// non-cacheable actions
	array(
		'DownloadConfiguration' => 'list, download, new',
		
	)
);

t3lib_extMgm::addService($_EXTKEY, 'fileService', 'tx_t3download_sv1',
                array(
                        'title' => 'Dowload Configuration service',
                        'description' => 'Used to create a download configuration',
                        'subtype' => '',
                        'available' => true,
                        'priority' => 60,
                        'quality' => 80,
                        'os' => '',
                        'exec' => '',
                        'className' => 'TYPO3\T3download\Service\FileService',
                )
        );

?>