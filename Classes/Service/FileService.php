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
     * @param array   $fileReferences File / folder references
     * @param int     $validDate      An UNIX valid date (optional)
     * @param boolean $isDirectory    Is directory?
     * @param string  $directoryPath  The directory path (eg. fileadmin/path/album/)
     * 
     * @return string The secured download URL or FALSE if invalid file references are found
     */
    public function createDownloadConfiguration($fileReferences, $validDate = 0, $isDirectory = false, $directoryPath = '') {
        $downloadConfiguration = null;
        
        $this->downloadConfigurationRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\T3download\\Domain\\Repository\\DownloadConfigurationRepository');
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        
        if (count($fileReferences) > 0 && $this->checkFileReferences($fileReferences) && $isDirectory === false) {
            $downloadConfiguration = $this->objectManager->get('TYPO3\\T3download\\Domain\\Model\\DownloadConfiguration');
            foreach($fileReferences as $fileReferences) {
                // @TODO: convert to \TYPO3\CMS\Extbase\Domain\Model\FileReference
                $newFileReference = $this->objectManager->get('\TYPO3\CMS\Extbase\Domain\Model\FileReference');
                
                $downloadConfiguration->addFileReference($fileReferences);
            }
            $downloadConfiguration->setValidDate($validDate);            
        } else if ($isDirectory === true && $directoryPath !== '' && is_dir(PATH_site . $directoryPath)) {
            $downloadConfiguration = $this->objectManager->get('\TYPO3\T3download\Domain\Model\DownloadConfiguration');
            $downloadConfiguration->setIsDirectory(true);
            $downloadConfiguration->setDirectoryPath($directoryPath);
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
            \Tx_ExtDebug::var_dump(get_class($fileReference));
            return false;
        }
        
        return true;
    }
    
    /**
     * Download files
     * 
     * @param string $securedString A secured string
     * 
     * @return void
     */
    public function downloadAction($securedString) {
        
    }
}