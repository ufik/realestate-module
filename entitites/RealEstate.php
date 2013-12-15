<?php

namespace WebCMS\RealestateModule\Doctrine;

use Doctrine\orm\Mapping as orm;
use Gedmo\Mapping\Annotation as gedmo;

/**
 * Description of Real estate
 * @orm\Entity
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class RealEstate extends \AdminModule\Seo {
	/**
     * @orm\Column(length=64)
     */
    private $title;
	
    /**
     * @orm\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @gedmo\Slug(fields={"title"})
     * @orm\Column(length=64)
     */
    private $slug;
	
	/**
	 * @orm\OneToMany(targetEntity="Photo", mappedBy="realEstate")
	 */
	private $photos;
	
	/**
	 * @orm\ManyToMany(targetEntity="Category", inversedBy="realEstates", cascade={"persist"})
	 * @orm\JoinColumn(name="category_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $categories;
	
	/**
	 * @orm\ManyToOne(targetEntity="\AdminModule\Language")
	 * @orm\JoinColumn(name="language_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	private $language;
	
	/**
	 * @orm\Column(type="decimal", precision=12, scale=4, nullable=true)
	 */
	private $price;
	
	/**
	 * @orm\Column(type="boolean", nullable=true)
	 */
	private $discount;
	
	/**
	 * @orm\Column(type="boolean", nullable=true)
	 */
	private $reserved;
	
	/**
	 * @orm\Column(type="boolean", nullable=true)
	 */
	private $hide;
	
	/**
     * @orm\Column(length=64, nullable=true)
     */
    private $address;
	
	/**
     * @orm\Column(length=64, nullable=true)
     */
    private $mark;
	
	/**
     * @orm\Column(nullable=true)
     */
    private $latitude;
	
	/**
     * @orm\Column(nullable=true)
     */
    private $longtitude;
	
	private $link;
	
	/**
	 * @orm\Column(nullable=true)
	 */
	private $defaultPicture;
	
	public function __construct(){
		$this->categories = new \Doctrine\Common\Collections\ArrayCollection();
		$this->photos = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	public function addCategory($category){
		$this->categories->add($category);
	}
	
	public function getTitle() {
		return $this->title;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getSlug() {
		return $this->slug;
	}

	public function getPhotos() {
		return $this->photos;
	}

	public function getCategories() {
		return $this->categories;
	}

	public function getLanguage() {
		return $this->language;
	}

	public function getPrice() {
		return $this->price;
	}

	public function getDiscount() {
		return $this->discount;
	}

	public function getReserved() {
		return $this->reserved;
	}

	public function getHide() {
		return $this->hide;
	}

	public function getMark() {
		return $this->mark;
	}

	public function getLink() {
		return $this->link;
	}

	public function getDefaultPicture() {
		return $this->defaultPicture;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function setSlug($slug) {
		$this->slug = $slug;
	}

	public function setPhotos($photos) {
		$this->photos = $photos;
	}

	public function setCategories($categories) {
		$this->categories = $categories;
	}

	public function setLanguage($language) {
		$this->language = $language;
	}

	public function setPrice($price) {
		$this->price = $price;
	}

	public function setDiscount($discount) {
		$this->discount = $discount;
	}

	public function setReserved($reserved) {
		$this->reserved = $reserved;
	}

	public function setHide($hide) {
		$this->hide = $hide;
	}

	public function setMark($mark) {
		$this->mark = $mark;
	}

	public function setLink($link) {
		$this->link = $link;
	}

	public function setDefaultPicture($defaultPicture) {
		$this->defaultPicture = $defaultPicture;
	}
	
	public function getMainPhoto(){
		foreach($this->getPhotos() as $photo){
			if($photo->getDefault())
				return $photo;
		}
		
		return new Photo();
	}
	
	public function getAddress() {
		return $this->address;
	}

	public function getLatitude() {
		return $this->latitude;
	}

	public function getLongtitude() {
		return $this->longtitude;
	}

	public function setAddress($address) {
		$this->address = $address;
	}

	public function setLatitude($latitude) {
		$this->latitude = $latitude;
	}

	public function setLongtitude($longtitude) {
		$this->longtitude = $longtitude;
	}
}