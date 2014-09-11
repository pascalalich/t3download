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

	protected $logger;
	
    /**
     * downloadConfigurationRepository
     *
     * @var \TYPO3\T3download\Domain\Repository\DownloadConfigurationRepository
     * @inject
     */
    protected $downloadConfigurationRepository;

    function __construct() {
    	$this->logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Log\\LogManager')->getLogger(__CLASS__);
    }
    
    /**
     * action list
     *
     * @return void
     */
    public function listAction() {       
        $downloadConfigurations = $this->downloadConfigurationRepository->findAll();        
        $this->view->assign('downloadConfigurations', $downloadConfigurations);
    }
    
    /**
     * Make download active again, i. e. prolong valid date.
     *  
     * @return void
     */
    
    public function activateAction() {
    	$securedUuid = $this->request->getArgument('download');
    	
    	if ($securedUuid == '') {
    		exit;
    	}
    	
    	$downloadConfiguration = $this->downloadConfigurationRepository->findBySecuredUuid($securedUuid);
    	if ($downloadConfiguration !== NULL) {
	    	// 14 days
	    	$validDuration = 14 * 24 * 60 * 60;
	    	$downloadConfiguration->setValidDate(time() + $validDuration);
	    	$this->downloadConfigurationRepository->update($downloadConfiguration);
    	}
    	$this->forward('list');
    }

    /**
     * Make download active again, i. e. prolong valid date.
     *  
     * @return void
     */
    
    public function createZIPAction() {
    	$securedUuid = $this->request->getArgument('download');
    	
    	if ($securedUuid == '') {
    		exit;
    	}
    	
    	$downloadConfiguration = $this->downloadConfigurationRepository->findBySecuredUuid($securedUuid);
		if ($downloadConfiguration !== NULL) {
			$zipFilePath = $downloadConfiguration->getZipFilePath();
			$this->logger->info("about to create zip file", array (
					'file' => $zipFilePath
			));
    		$zip = new \ZipArchive();
    		$success = $zip->open($zipFilePath, \ZipArchive::CREATE);
    		
    		if ($success === TRUE) {
	    		$fileReferences = $downloadConfiguration->getFileReferences();
	    		foreach($fileReferences as $fileReference) {
	    			$file = $fileReference->getOriginalResource()->getOriginalFile();
	    			$filePath = PATH_site .'fileadmin' . $file->getIdentifier();
	    			if (file_exists($filePath)) {
		    			$fileName = substr($filePath, strrpos($filePath, '/') + 1);
		    			$this->logger->info("preparing file for zipping", array (
		    					'path' => $filePath,
		    					'name' => $fileName
		    			));
	    				$zip->addFile($filePath, $fileName);
	    			} else {
		    			$this->logger->error("file for zipping does not exist", array (
		    					'file' => $filePath
		    			));
	    			}
	    		}
	    		$success = $zip->close();
    		}
    		
    		if ($success === TRUE) {
	    		$this->flashMessageContainer->add('ZIP file created!');
    		} else {
	    		$this->flashMessageContainer->add('ZIP file could not be created');
    		}
    	}
    	$this->forward('list');
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
        
        $downloadConfiguration = $this->downloadConfigurationRepository->findBySecuredUuid($securedUuid);
        
        if ($downloadConfiguration === null) {
        	echo 'Kein Download gefunden.';
            exit;
        } else {
            $validDate = $downloadConfiguration->getValidDate();
            if ($validDate === null || time() > $validDate->getTimestamp()) {
            	echo 'Der Download darf nicht mehr heruntergeladen werden.';
                exit;
            }
            
            if ($downloadConfiguration->isZipFileExisting()) {
	        	// Disable all output buffers
	        	while (ob_get_level() > 0) {
	        		if (!ob_end_clean()) {
	            		echo 'Technischer Fehler beim Download (Code 1).';
	        			exit;
	        		}
	        	}
	            header('Content-Type: application/zip');
	            header('Content-disposition: attachment; filename='.$downloadConfiguration->getExternalId().'.zip');
	            header('Content-Length: ' . filesize($downloadConfiguration->getZipFilePath()));
	            readfile($downloadConfiguration->getZipFilePath());
            } else {
            	echo 'Technischer Fehler beim Download (Code 2).';
        		exit;
            }
        }
        exit;
    }
    
    /**
     * New action
     * 
     * @return void
     */
    public function newAction() {
        // Get file from a track
        $uid = 1; // Track UID, insert here the UID of a track
        
        
        $someFileIdentifier = 'music/01 Gospel Worship Airline (SongAusschnitt).mp3';          // image name in the storage repository (e.g. directly in fileadmin/ root, otherwise specify the subdirectory here, e.ge. 'templates/image123.png'
        $storageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\StorageRepository'); // create instance to storage repository
        $storage = $storageRepository->findByUid(1);    // get file storage with uid 1 (this should by default point to your fileadmin/ directory)
        $file = $storage->getFile($someFileIdentifier); // create file object for the image (the file will be indexed if necessary)
        
        $this->logger->info("found file", array (
        		'file' => $file->toArray()
        ));
        
        // How to get a full file from a track
        // $fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
        //$fileObjects = $fileRepository->findByRelation('tx_t3music_domain_model_track', 'full_file', $uid);
        $fileObjects = array($file);
        
        // Check if our service is available
        if (is_object($serviceObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstanceService('fileService'))) {

            // This is how you create a download configuration
            $serviceObj->createDownloadConfiguration($fileObjects, 'fileadmin/', time() + 31556926, '854bgfd');            
            $this->flashMessageContainer->add('New download configuration created!');
        }
        
        $this->redirect('list');
    }

}

?>