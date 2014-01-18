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
     * The download configuration repository
     * 
     * @var TYPO3\T3download\Domain\Repository\DownloadConfigurationRepository;
     * @inject
     */
    protected $downloadConfigurationRepository;
    
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
        if (count($fileReferences) > 0 && $this->checkFileReferences($fileReferences) && $isDirectory === false) {
            $downloadConfiguration = TYPO3\CMS\Extbase\Object\ObjectManager::get('\TYPO3\T3download\Domain\Model\DownloadConfiguration');
            $downloadConfiguration->setFileReferences(serialize($fileReferences));
            $downloadConfiguration->setValidDate($validDate);            
        } else if ($isDirectory === true && $directoryPath !== '' && is_dir(PATH_site . $directoryPath)) {
            $downloadConfiguration = TYPO3\CMS\Extbase\Object\ObjectManager::get('\TYPO3\T3download\Domain\Model\DownloadConfiguration');
            $downloadConfiguration->setIsDirectory(true);
            $downloadConfiguration->setDirectoryPath($directoryPath);
        } else {
            return false;
        }
        
        if ($downloadConfiguration !== null) {
            $this->downloadConfigurationRepository->add($downloadConfiguration);
        } else {
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
            if ($fileReference instanceof \TYPO3\CMS\Extbase\Domain\Model\FileReference) {
                continue;
            }
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