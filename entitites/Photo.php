<?php

namespace WebCMS\RealEstateModule\Doctrine;

use Doctrine\ORM\Mapping as orm;

/**
 * Description of Photo
 * @orm\Entity
 * @orm\Table(name="realestateModulePhoto")
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class Photo extends \AdminModule\Doctrine\Entity{
	
	/**
	 * @orm\Column(type="text")
	 */
	private $title;
	
	/**
	 * @orm\ManyToOne(targetEntity="RealEstate")
	 * @orm\JoinColumn(name="realestate_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $realEstate;

	/**
	 * @orm\Column(type="text")
	 */
	private $path;
	
	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function getRealEstate() {
		return $this->realEstate;
	}

	public function setRealEstate($realEstate) {
		$this->realEstate = $realEstate;
	}

	public function getPath() {
		return $this->path;
	}

	public function setPath($path) {
		$this->path = $path;
	}
}