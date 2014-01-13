<?php
$classPath = t3lib_extMgm::extPath('t3download', 'Classes/');
$resourcesPath = t3lib_extMgm::extPath('t3download', 'Resources/');
$prefix = 'tx_t3download_';
return array(
    $prefix . 'service_fileservice' => $classPath . 'FileService',
    'ZipStream' => $resourcesPath . 'Private/ZipStreamPHP/zipstream'
);