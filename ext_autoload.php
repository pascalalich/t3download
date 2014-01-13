<?php
$classPath = t3lib_extMgm::extPath('t3download', 'Classes/');
$prefix = 'tx_t3download_';
return array(
    $prefix . 'service_fileservice' => $classPath . 'FileService'
);