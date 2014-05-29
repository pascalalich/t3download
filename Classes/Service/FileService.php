<?php
namespace TYPO3\T3download\Service;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Tomita Militaru <mail@tomitamilitaru.com>, T3Licious
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 ***************************************************************/
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
/**
 * File service
 *
 * Creates secured download URLs
 *
 * @author Tomita Militaru, T3Licious
 * @package T3download
 * @subpackage Service
 */

class FileService extends \TYPO3\CMS\Core\Service\AbstractService {
    
	protected $logger;
	
    /**
     * downloadConfigurationRepository
     * 
     * @var \TYPO3\T3download\Domain\Repository\DownloadConfigurationRepository
     */
    protected $downloadConfigurationRepository;
    
    /**
     * @var \TYPO3\CMS\Core\Resource\ResourceFactory
     * @inject
     */
    protected $resourceFactory;
    
    /**
     * objectManager
     * 
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;
    
    function __construct() {
    	$this->logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Log\\LogManager')->getLogger(__CLASS__);
    }
    
    /**
     * Create a download configuration
     * 
     * @param array   $fileReferences   File references
     * @param string  $folderReferences Folder references
     * @param int     $validDate        An UNIX valid date (optional)
     * @param string  $externalId       Extenal ID
     * 
     * @return string The secured download URL or FALSE if invalid file references are found
     */
    public function createDownloadConfiguration($files, $folderReferences = '', $validDate = 0, $externalId = '') {
    	
    	$this->logger->info("creating download configuration via service", array (
    			'files' => $files,
    			'directories' => $folderReferences,
    			'validTo' => $validDate,
    			'externalId' => $externalId
    	));
    	
        $downloadConfiguration = null;
        
        $this->downloadConfigurationRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\T3download\\Domain\\Repository\\DownloadConfigurationRepository');
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');
        
        if (count($files) > 0 /*&& $this->checkFileReferences($files)*/) {
            $downloadConfiguration = $this->objectManager->get('TYPO3\\T3download\\Domain\\Model\\DownloadConfiguration');
            foreach($files as $file) {
                //$newFileReference = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference');
                //$newFileReference->setOriginalResource($file);
            	$newFileReference = $this->createFileReferenceFromFalFileObject($file);
            	
                $downloadConfiguration->addFileReference($newFileReference);
                //$downloadConfiguration->setFolderReferences($folderReferences);
            }
            
            $hash = crypt($downloadConfiguration->getUid(), $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']);
            $downloadConfiguration->setHash($hash);            
            $downloadConfiguration->setValidDate($validDate);
                $downloadConfiguration->setExternalId($externalId);                                
            
        } else {
            //\Tx_ExtDebug::var_dump('Check failed!');
	    	$this->logger->error("invalid file references", array (
	    			'files' => $files
	    	));
            return false;
        }
        
        if ($downloadConfiguration !== null) {
            $this->downloadConfigurationRepository->add($downloadConfiguration);
        } else {
            //\Tx_ExtDebug::var_dump('Download configuration null');
            return false;
        }
        
        return true;
    }
    
    /**
     * @param File $file
     * @return \TYPO3\T3download\Domain\Model\FileReference
     */
    protected function createFileReferenceFromFalFileObject(File $file) {
    	$fileReference = $this->resourceFactory->createFileReferenceObject(
    			array(
    					'uid_local' => $file->getUid(),
    					'uid_foreign' => uniqid('NEW_'),
    					'uid' => uniqid('NEW_'),
    			)
    	);
    	return $this->createFileReferenceFromFalFileReferenceObject($fileReference);
    }
    
    /**
     * @param FileReference $fileReference
     * @return \TYPO3\T3download\Domain\Model\FileReference
     */
    protected function createFileReferenceFromFalFileReferenceObject(FileReference $fileReference) {
    	/** @var $fileReferenceModel \TYPO3\CMS\Extbase\Domain\Model\FileReference */
    	$fileReferenceModel = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference');
    	$fileReferenceModel->setOriginalResource($fileReference);
    
    	return $fileReferenceModel;
    }
    
}