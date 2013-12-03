<?php

namespace WebCMS\RealEstateModule;

/**
 * Description of Real estate
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class RealEstate extends \WebCMS\Module {
	
	protected $name = 'Real Estate';
	
	protected $author = 'Tomáš Voslař';
	
	protected $presenters = array(
		array(
			'name' => 'RealEstate',
			'frontend' => TRUE,
			'parameters' => FALSE
			),
		array(
			'name' => 'Settings',
			'frontend' => FALSE
			)
	);
	
	protected $params = array(
		
	);
	
	public function __construct(){
		//$this->addBox('Page box', 'Page', 'textBox');
	}
	
}