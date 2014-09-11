<?php

namespace TYPO3\T3download\Domain\Model;

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
class DownloadConfiguration extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

    /**
     * File references
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $fileReferences;

    /**
     * validDate
     *
     * @var \DateTime
     */
    protected $validDate;

    /**
     * Directory references
     *
     * @var \string
     */
    protected $folderReferences;
    
    /**
     * External id
     * 
     * @var \string
     */
    protected $externalId;
    
    /**
     * Secure hash
     * 
     * @var \string
     */
    protected $hash;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->initStorageObjects();
    }

    /**
     * Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage properties.
     *
     * @return void
     */
    protected function initStorageObjects() {
        $this->fileReferences = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the fileReferences
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $fileReferences
     */
    public function getFileReferences() {
        return $this->fileReferences;
    }

    /**
     * Sets the fileReferences
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $fileReferences
     * @return void
     */
    public function setFileReferences($fileReferences) {
        $this->fileReferences = $fileReferences;
    }

    /**
     * Add file reference
     * 
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileReference
     * @return void
     */
    public function addFileReference($fileReference) {
        $this->fileReferences->attach($fileReference);
    }

    /**
     * Remove file reference
     * 
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileReference
     * @return void
     */
    public function removeFileReference($fileReference) {
        $this->fileReferences->remove($fileReference);
    }

    /**
     * Returns the validDate
     *
     * @return \DateTime $validDate
     */
    public function getValidDate() {
        return $this->validDate;
    }

    /**
     * Sets the validDate
     *
     * @param \DateTime $validDate
     * @return void
     */
    public function setValidDate($validDate) {
        $this->validDate = $validDate;
    }

    /**
     * Returns the folderReferences
     *
     * @return \string $folderReferences
     */
    public function getFolderReferences() {
        return $this->folderReferences;
    }

    /**
     * Sets the folderReferences
     *
     * @param \string $folderReferences
     * @return void
     */
    public function setFolderReferences($folderReferences) {
        $this->folderReferences = $folderReferences;
    }
    
    /**
     * Get external id
     * 
     * @return string 
     */
    public function getExternalId() {
        return $this->externalId;
    }

    /**
     * Set external id
     * 
     * @param \string $externalId The external id
     * 
     * @return void
     */
    public function setExternalId($externalId) {
        $this->externalId = $externalId;
    }
    
    /**
     * Get hash
     * 
     * @return string
     */
    public function getHash() {
        return $this->hash;
    }

    /**
     * Set hash
     * 
     * @param \string $hash The hash string
     * 
     * @return void
     */
    public function setHash($hash) {
        $this->hash = $hash;
    }

    /**
     * @return string the zip file path
     */
    public function getZipFilePath() {
    	return PATH_site . 'uploads/tx_t3download/' . str_replace('/', '-', $this->hash) . '.zip';
    }
    
	/**
	 * @return whether the zip file already exists
	 */
    public function isZipFileExisting() {
    	return file_exists($this->getZipFilePath());
    }
}

?>