<?php

namespace AdminModule\RealestateModule;

/**
 * Description of RealEstatePresenter
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class BasePresenter extends \AdminModule\BasePresenter {
	
	protected $repository;
	
	private $page;
	
	protected function startup() {
		parent::startup();
		
		$this->repository = $this->em->getRepository('WebCMS\RealestateModule\Doctrine\RealEstate');
	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault($idPage){
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