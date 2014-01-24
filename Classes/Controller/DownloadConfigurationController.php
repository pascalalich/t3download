<?php

namespace TYPO3\T3download\Controller;
require \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('t3download') . 'Resources/Private/ZipStreamPHP/zipstream.php';
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Tomita Militaru <mail@tomitamilitaru.com>
 *  Pascal Alich 
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 *
 *
 * @package t3download
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class DownloadConfigurationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * downloadConfigurationRepository
     *
     * @var \TYPO3\T3download\Domain\Repository\DownloadConfigurationRepository
     * @inject
     */
    protected $downloadConfigurationRepository;

    /**
     * action list
     *
     * @return void
     */
    public function listAction() {       

        // Get file from a track
        $uid = 1; // Track UID, insert here the UID of a track
        
        
        // How to get a full file from a track
        $fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
        $fileObjects = $fileRepository->findByRelation('tx_t3music_domain_model_track', 'full_file', $uid);
        
        // Check if our service is available
        if (is_object($serviceObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstanceService('fileService'))) {

            // This is how you create a download configuration
            //$serviceObj->createDownloadConfiguration($fileObjects, 'fileadmin/', time() + 31556926, '854bgfd');            

        }
        
        $downloadConfigurations = $this->downloadConfigurationRepository->findAll();        
        $this->view->assign('downloadConfigurations', $downloadConfigurations);
    }
    
    /**
     * Download files
     *  
     * @return void
     */
    
    public function downloadAction() {

        $securedUuid = $this->request->getArgument('download');
        
        if ($securedUuid == '') {
            exit;
        }
        
        $zip = new \ZipStream('example.zip');
        
        $downloadConfiguration = $this->downloadConfigurationRepository->findBySecuredUuid($securedUuid);
       
        if ($downloadConfiguration === null) {
            exit;
        } else {
            $fileReferences = $downloadConfiguration->getFileReferences();
            $folderReferences = $downloadConfiguration->getFolderReferences();
            $validDate = $downloadConfiguration->getValidDate();
            
            if ($validDate === null || time() > $validDate->getTimestamp()) {
                exit;
            }
            
            foreach($fileReferences as $fileReference) {
                $file = $fileReference->getOriginalResource()->getOriginalFile();
                $zip->add_file_from_path($file->getName(), PATH_site . 'fileadmin' . $file->getIdentifier());
            }
            
            if ($folderReferences !== '') {
                $directories = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $folderReferences);
                foreach($directories as $directory) {
                    $files = scandir(PATH_site . $directory);
                    foreach($files as $file) {
                        $zip->add_file_from_path($file, PATH_site . $directory . $file);
                    }
                }
            }
            $zip->finish();
        }
        
        $fileName = basename($file);

        if (is_file($file)) {

            $fileLen = filesize($file);
            $ext = strtolower(substr(strrchr($fileName, '.'), 1));

            switch ($ext) {
                case 'zip':
                    $cType = 'application/zip';
                    break;

                //forbidden filetypes
                case 'inc':
                case 'conf':
                case 'sql':
                case 'cgi':
                case 'htaccess':
                case 'php':
                case 'php3':
                case 'php4':
                case 'php5':
                    exit;

                default:
                    exit;
                    break;
            }

            $headers = array(
                'Pragma' => 'public',
                'Expires' => 0,
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Cache-Control' => 'public',
                'Content-Description' => 'File Transfer',
                'Content-Type' => $cType,
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Length' => $fileLen
            );

            foreach ($headers as $header => $data) {
                $this->response->setHeader($header, $data);
            }

            $this->response->sendHeaders();
            @readfile($file);
        }
        exit;
    }

}

?>