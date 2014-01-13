<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_t3download_domain_model_downloadconfiguration'] = array(
	'ctrl' => $TCA['tx_t3download_domain_model_downloadconfiguration']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, file_references, valid_date, is_directory, directory_path',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, file_references, valid_date, is_directory, directory_path,--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_t3download_domain_model_downloadconfiguration',
				'foreign_table_where' => 'AND tx_t3download_domain_model_downloadconfiguration.pid=###CURRENT_PID### AND tx_t3download_domain_model_downloadconfiguration.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'file_references' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3download/Resources/Private/Language/locallang_db.xlf:tx_t3download_domain_model_downloadconfiguration.file_references',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file_reference',
				'uploadfolder' => 'uploads/tx_t3download',
				'allowed' => '*',
				'disallowed' => 'php',
				'size' => 5,
			),
		),
		'valid_date' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3download/Resources/Private/Language/locallang_db.xlf:tx_t3download_domain_model_downloadconfiguration.valid_date',
			'config' => array(
				'type' => 'input',
				'size' => 7,
				'eval' => 'date',
				'checkbox' => 1,
				'default' => time()
			),
		),
		'is_directory' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3download/Resources/Private/Language/locallang_db.xlf:tx_t3download_domain_model_downloadconfiguration.is_directory',
			'config' => array(
				'type' => 'check',
				'default' => 0
			),
		),
		'directory_path' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:t3download/Resources/Private/Language/locallang_db.xlf:tx_t3download_domain_model_downloadconfiguration.directory_path',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
	),
);

?>