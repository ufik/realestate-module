<?php

namespace AdminModule\RealestateModule;

/**
 * Description of RealEstatePresenter
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class BasePresenter extends \AdminModule\BasePresenter {
	
	private $repository;
	
	private $page;
	
	protected function startup() {
		parent::startup();
		
		$this->repository = $this->em->getRepository('WebCMS\RModule\Doctrine\RealEstate');
	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault(){
		$this->page = $this->repository->findOneBy(array(
			'page' => $this->actualPage
		));
	}
	
	public function renderDefault($idPage){
		$this->reloadContent();
		
		$this->template->page = $this->page;
		$this->template->idPage = $idPage;
	}
}