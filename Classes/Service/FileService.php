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
    
    /**
     * downloadConfigurationRepository
     * 
     * @var \TYPO3\T3download\Domain\Repository\DownloadConfigurationRepository
     */
    protected $downloadConfigurationRepository;
    
    /**
     * objectManager
     * 
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;
    
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
    public function createDownloadConfiguration($fileReferences, $folderReferences = '', $validDate = 0, $externalId = '') {
        $downloadConfiguration = null;
        
        $this->downloadConfigurationRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\T3download\\Domain\\Repository\\DownloadConfigurationRepository');
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        
        if (count($fileReferences) > 0 && $this->checkFileReferences($fileReferences)) {
            $downloadConfiguration = $this->objectManager->get('TYPO3\\T3download\\Domain\\Model\\DownloadConfiguration');
            foreach($fileReferences as $fileReferences) {
                $newFileReference = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Domain\\Model\\FileReference');
                $newFileReference->setOriginalResource($fileReferences);
                
                $downloadConfiguration->addFileReference($newFileReference);
                $downloadConfiguration->setFolderReferences($folderReferences);
                $downloadConfiguration->setExternalId($externalId);
            }
            
            $downloadConfiguration->setValidDate($validDate);
        } else {
            \Tx_ExtDebug::var_dump('Check failed!');
            return false;
        }
        
        if ($downloadConfiguration !== null) {
            $this->downloadConfigurationRepository->add($downloadConfiguration);
        } else {
            \Tx_ExtDebug::var_dump('Download configuration null');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validates an array of file references
     * 
     * @param array $fileReferences The file references
     * 
     * @return boolean Returns TRUE if all references are valid, FALSE otherwise.
     */
    protected function checkFileReferences($fileReferences) {
        foreach ($fileReferences as $fileReference) {
            if ($fileReference instanceof \TYPO3\CMS\Core\Resource\FileReference) {
                continue;
            }
            return false;
        }
        
        return true;
    }   
    
}