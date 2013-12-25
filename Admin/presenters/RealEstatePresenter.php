<?php

namespace AdminModule\RealestateModule;

use Nette\Application\UI;

/**
 * Description of RealEstatePresenter
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class RealestatePresenter extends BasePresenter {
	
	private $categoryRepository;
	
	private $page;
	
	private $photos;
	
	private $realEstate;
	
	protected function startup() {
		parent::startup();
		
		$this->categoryRepository = $this->em->getRepository('WebCMS\RealestateModule\Doctrine\Category');
	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault($idPage){
		
	}
	
	public function renderDefault($idPage){
		$this->reloadContent();
		
		$this->template->page = $this->page;
		$this->template->idPage = $idPage;
	}
	
	protected function createComponentRealEstateGrid($name){
				
		$grid = $this->createGrid($this, $name, '\WebCMS\RealestateModule\Doctrine\RealEstate', NULL,
			array(
				'language = ' . $this->state->language->getId(),
			)
		);
		
		$grid->addColumnText('title', 'Name')->setSortable()->setFilterText();
		$grid->addColumnNumber('price', 'Price')->setCustomRender(function($item){
			return \WebCMS\PriceFormatter::format($item->getPrice());
		})->setSortable()->setFilterNumber();
		$grid->addColumnText('mark', 'Mark')->setSortable()->setFilterText();
		$grid->addColumnText('address', 'Address')->setSortable()->setFilterText();
		
		$grid->addActionHref("updateRealEstate", 'Edit', 'updateRealEstate', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => 'btn btn-primary ajax'));
		$grid->addActionHref("deleteRealEstate", 'Delete', 'deleteRealEstate', array('idPage' => $this->actualPage->getId()))->getElementPrototype()->addAttributes(array('class' => 'btn btn-danger', 'data-confirm' => 'Are you sure you want to delete this item?'));

		return $grid;
	}
	
	public function actionUpdateRealEstate($id, $idPage){
		$this->reloadContent();
		
		if($id){
			$this->realEstate = $this->repository->find($id);
		}else{
			$this->realEstate = new \WebCMS\RealestateModule\Doctrine\RealEstate();
		}
		
		if($this->realEstate->getId()){
			$this->photos = $this->em->getRepository('WebCMS\RealEstateModule\Doctrine\Photo')->findBy(array(
				'realEstate' => $this->realEstate
			));
		}
		else{ 
			$this->photos = array();
		}
	}
	
	public function renderUpdateRealEstate($idPage){
		
		$this->template->idPage = $idPage;
		$this->template->panel = 'basic';
		$this->template->photos = $this->photos;
	}
	
	public function createComponentRealEstateForm(){
		
		$hierarchy = $this->categoryRepository->getTreeForSelect(array(
			array('by' => 'root', 'dir' => 'ASC'), 
			array('by' => 'lft', 'dir' => 'ASC')
			),
			array(
				'language = ' . $this->state->language->getId()
		));
		
		$form = $this->createForm();
		$form->addText('title', 'Name')->setAttribute('class', 'form-control')->setRequired('Please fill in a name.');
		$form->addText('slug', 'SEO adresa url')->setAttribute('class', 'form-control');
		$form->addText('metaTitle', 'SEO title')->setAttribute('class', 'form-control');
		$form->addText('metaDescription', 'SEO description')->setAttribute('class', 'form-control');
		$form->addText('metaKeywords', 'SEO keywords')->setAttribute('class', 'form-control');
		$form->addText('mark', 'Mark')->setAttribute('class', 'form-control');
		$form->addText('address', 'Address')->setAttribute('class', 'form-control');
		$form->addText('longtitude', 'Longtitude')->setAttribute('class', 'form-control');
		$form->addText('latitude', 'Latitude')->setAttribute('class', 'form-control');
		$form->addCheckbox('hide', 'Hide')->setAttribute('class', 'form-control');
		$form->addText('price', 'Price')->setAttribute('class', 'form-control');
		$form->addText('priceSuffix', 'Price suffix')->setAttribute('class', 'form-control');
		$form->addMultiSelect('categories', 'Categories')->setTranslator(NULL)->setItems($hierarchy)->setAttribute('class', 'form-control');
		$form->addTextArea('description')->setAttribute('class', 'form-control editor');
		
		$form->addSubmit('save', 'Save')->setAttribute('class', 'btn btn-success');
		
		$form->onSuccess[] = callback($this, 'realEstateFormSubmitted');
		
		if($this->realEstate){
			$defaults = $this->realEstate->toArray();
			
			$defaultCategories = array();
			foreach($this->realEstate->getCategories() as $c){
				$defaultCategories[] = $c->getId();
			}
			
			$defaults['categories'] = $defaultCategories;
			$form->setDefaults($defaults);
		}
		
		return $form;
	}
	
	public function realEstateFormSubmitted(UI\Form $form){
		$values = $form->getValues();

		$this->realEstate->setTitle($values->title);
		if($this->realEstate->getId() || !empty($values->slug)){
			$this->realEstate->setSlug($values->slug);
		}
		$this->realEstate->setMetaTitle($values->metaTitle);
		$this->realEstate->setMetaDescription($values->metaDescription);
		$this->realEstate->setMetaKeywords($values->metaKeywords);
		$this->realEstate->setLanguage($this->state->language);
		$this->realEstate->setMark($values->mark);
		$this->realEstate->setAddress($values->address);
		$this->realEstate->setLongtitude($values->longtitude);
		$this->realEstate->setLatitude($values->latitude);
		$this->realEstate->setHide($values->hide);
		$this->realEstate->setPrice($values->price);
		$this->realEstate->setPriceSuffix($values->priceSuffix);
		$this->realEstate->setDescription($values->description);
		
		// delete old categories
		$this->realEstate->setCategories(new \Doctrine\Common\Collections\ArrayCollection());
		
		// set categories
		foreach($values->categories as $c){
			$category = $this->categoryRepository->find($c);
			$this->realEstate->addCategory($category);
		}
		
		// delete old photos and save new ones
		if($this->realEstate->getId()){
			$qb = $this->em->createQueryBuilder();
			$qb->delete('WebCMS\RealestateModule\Doctrine\Photo', 'l')
					->where('l.realEstate = ?1')
					->setParameter(1, $this->realEstate)
					->getQuery()
					->execute();
			
		}else{
			$this->realEstate->setDefaultPicture('');
		}
		
		if(array_key_exists('files', $_POST)){
			$counter = 0;
			if(array_key_exists('fileDefault', $_POST)) $default = intval($_POST['fileDefault'][0]) - 1;
			else $default = -1;
			
			foreach($_POST['files'] as $path){

				$photo = new \WebCMS\RealestateModule\Doctrine\Photo;
				$photo->setTitle($_POST['fileNames'][$counter]);
				// FIXME this is totaly wrong
				if($default === $counter){
					$photo->setDefault(TRUE);
					$this->realEstate->setDefaultPicture($path);
				}else
					$photo->setDefault(FALSE);
					
				$photo->setPath($path);
				$photo->setRealEstate($this->realEstate);

				$this->em->persist($photo);

				$counter++;
			}
		}
		
		if(!$this->realEstate->getId()){ 
			$this->em->persist($this->realEstate);
		}
		
		$this->em->flush();
		
		$this->flashMessage('Real estate has been added.', 'success');
		
		if(!$this->isAjax())
			$this->redirect('this', array(
				'id' => $this->realEstate->getId()
			));
	}
	
	public function actionDeleteRealEstate($id, $idPage){
		$realEstate = $this->repository->find($id);
		
		$this->em->remove($realEstate);
		$this->em->flush();
		
		$this->flashMessage('Real estate has been removed.', 'success');
		
		if(!$this->isAjax())
			$this->redirect('RealEstate:default', array('idPage' => $idPage));
	}
}