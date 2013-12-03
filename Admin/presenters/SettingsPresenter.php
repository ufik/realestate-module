<?php

namespace AdminModule\RealestateModule;

/**
 * Description of SettingsPresenter
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class SettingsPresenter extends \AdminModule\BasePresenter {
	
	private $repository;
	
	private $page;
	
	protected function startup() {
		parent::startup();
		
	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault($idPage){

	}
	
	public function createComponentSettingsForm(){
		
		$settings = array();
		
		return $this->createSettingsForm($settings);
	}
	
	public function renderDefault($idPage){
		$this->reloadContent();
		
		$this->template->config = $this->settings->getSection('realEstateModule');
		$this->template->page = $this->page;
		$this->template->idPage = $idPage;
	}
	
	
}