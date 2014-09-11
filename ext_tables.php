<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE === 'BE') {
	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
			'TYPO3.'.$_EXTKEY,
			'web',	 // Make module a submodule of 'web'
			'download',	// Submodule key
			'',						// Position
			array(
					'DownloadConfiguration' => 'list,download,activate,createZip',
						
			),
			array(
					'access' => 'user,group',
					'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
					'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_backend.xlf',
			)
	);
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Download',
	'Download Plugin'
);

$pluginSignature = str_replace('_','',$_EXTKEY) . '_download';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_download.xml');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Download files');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_t3download_domain_model_downloadconfiguration', 'EXT:t3download/Resources/Private/Language/locallang_csh_tx_t3download_domain_model_downloadconfiguration.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_t3download_domain_model_downloadconfiguration');
$TCA['tx_t3download_domain_model_downloadconfiguration'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:t3download/Resources/Private/Language/locallang_db.xlf:tx_t3download_domain_model_downloadconfiguration',
		'label' => 'file_references',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'sortby' => 'sorting',
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'file_references,valid_date,folder_references,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/DownloadConfiguration.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_t3download_domain_model_downloadconfiguration.gif'
	),
);

?>