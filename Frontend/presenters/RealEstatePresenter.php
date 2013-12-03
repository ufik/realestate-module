<?php

namespace FrontendModule\RealEstateModule;

/**
 * Description of RealEstatePresenter
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class RealEstatePresenter extends \FrontendModule\BasePresenter{
	
	private $repository;
	
	private $page;
	
	protected function startup() {
		parent::startup();
	
		$this->repository = $this->em->getRepository('WebCMS\RealEstateModule\Doctrine\RealEstate');
	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault($id){
		
	}
	
	public function renderDefault($id){
		
		$this->template->id = $id;
	}
	
	public function textBox($context, $fromPage){
		$page = $context->em->getRepository('WebCMS\PageModule\Doctrine\Page')->findOneBy(array(
			'page' => $fromPage
		));
		
		$text = '<h1>' . $fromPage->getTitle() . '</h1>';
		$text .= $page->getText();
		
		return $text;
	}
}