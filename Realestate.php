<?php

namespace WebCMS\RealestateModule;

/**
 * Description of Real estate
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class Realestate extends \WebCMS\Module {
	
	protected $name = 'Realestate';
	
	protected $author = 'Tomáš Voslař';
	
	protected $presenters = array(
		array(
			'name' => 'Realestate',
			'frontend' => TRUE,
			'parameters' => TRUE
			),
		array(
			'name' => 'Categories',
			'frontend' => TRUE,
			'parameters' => TRUE
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