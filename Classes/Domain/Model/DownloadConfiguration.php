<?php
namespace TYPO3\T3download\Domain\Model;

/***************************************************************
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
 ***************************************************************/

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
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $fileReferences;

	/**
	 * validDate
	 *
	 * @var \DateTime
	 */
	protected $validDate;

	/**
	 * Is directory
	 *
	 * @var boolean
	 */
	protected $isDirectory = FALSE;

	/**
	 * Directory path
	 *
	 * @var \string
	 */
	protected $directoryPath;

	/**
	 * Returns the fileReferences
	 *
	 * @return \string $fileReferences
	 */
	public function getFileReferences() {
		return $this->fileReferences;
	}

	/**
	 * Sets the fileReferences
	 *
	 * @param \string $fileReferences
	 * @return void
	 */
	public function setFileReferences($fileReferences) {
		$this->fileReferences = $fileReferences;
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
	 * Returns the isDirectory
	 *
	 * @return boolean $isDirectory
	 */
	public function getIsDirectory() {
		return $this->isDirectory;
	}

	/**
	 * Sets the isDirectory
	 *
	 * @param boolean $isDirectory
	 * @return void
	 */
	public function setIsDirectory($isDirectory) {
		$this->isDirectory = $isDirectory;
	}

	/**
	 * Returns the boolean state of isDirectory
	 *
	 * @return boolean
	 */
	public function isIsDirectory() {
		return $this->getIsDirectory();
	}

	/**
	 * Returns the directoryPath
	 *
	 * @return \string $directoryPath
	 */
	public function getDirectoryPath() {
		return $this->directoryPath;
	}

	/**
	 * Sets the directoryPath
	 *
	 * @param \string $directoryPath
	 * @return void
	 */
	public function setDirectoryPath($directoryPath) {
		$this->directoryPath = $directoryPath;
	}

}
?>