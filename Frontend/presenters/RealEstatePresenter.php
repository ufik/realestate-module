<?php

namespace FrontendModule\RealestateModule;

/**
 * Description of RealEstatePresenter
 *
 * @author Tomáš Voslař <tomas.voslar at webcook.cz>
 */
class RealestatePresenter extends \FrontendModule\BasePresenter{
	
	private $repository;
	
	private $categoryRepository;
	
	private $page;
	
	protected function startup() {
		parent::startup();
	
		$this->repository = $this->em->getRepository('WebCMS\RealestateModule\Doctrine\RealEstate');
		$this->categoryRepository = $this->em->getRepository('WebCMS\RealestateModule\Doctrine\Category');
	}

	protected function beforeRender() {
		parent::beforeRender();
		
	}
	
	public function actionDefault($id){
		$parameters = $this->getParameter('parameters');
		
		$product = NULL;
		$products = array();
		// if there are no parameters, show all categories
		if(count($parameters) === 0){
			
			$category = $this->categoryRepository->findOneBy(array(
				'language' => $this->language,
				'title' => 'Main'
			));
			
			if(is_object($category) > 0){ 
				$title = $category->getTitle();
				$categories = $this->getStructure($this, $category, $this->categoryRepository, TRUE, 'nav navbar-nav', FALSE, FALSE, $this->actualPage);
			}else{
				$category = NULL;
				$title = '';
				$categories = '';
			}
		// otherwise try to find category or product by parameters and show it
		}else{
			$lastParam = $parameters[count($parameters) - 1];
			
			// check whether is this product
			$product = $this->repository->findBy(array(
				'slug' => $lastParam
			));
			
			if(count($product) > 0){
				unset($parameters[count($parameters) - 1]);
				$product = $product[0];
			}
			
			// define category
			$lastParam = $parameters[count($parameters) - 1];
			
			$category = $this->categoryRepository->findOneBy(array(
				'slug' => $lastParam
			));
			
			$title = $category->getTitle();
			
			foreach($parameters as $p){
				$item = $this->categoryRepository->findOneBy(array(
					'slug' => $p
				));
				$this->addBreadcrumbsItem($item);
			}
			
			// and finally add product to breadcrumbs
			if($product){
				// set product url
				$product->setLink(
					$this->link(':Frontend:RealEstate:Realestate:default', array(
						'id' => $category->getId(),
						'path' => $this->actualPage->getPath() . '/' . $category->getPath() . '/' . $product->getSlug(),
						'abbr' => $this->abbr
					))
				);
				
				// seo settings
				$this->actualPage->setMetaTitle($product->getMetaTitle());
				$this->actualPage->setMetaDescription($product->getMetaDescription());
				$this->actualPage->setMetaKeywords($product->getMetaKeywords());
				
				$this->addBreadcrumbsItem($category, $product);
			}else{
				// category
				// seo settings
				$this->actualPage->setMetaTitle($category->getMetaTitle());
				$this->actualPage->setMetaDescription($category->getMetaDescription());
				$this->actualPage->setMetaKeywords($category->getMetaKeywords());
			}
			
			// check for products
			$products = $category->getRealEstates();
			
			$categories = $this->getStructure($this, $category, $this->categoryRepository, TRUE, 'nav navbar-nav', FALSE, FALSE, $this->actualPage);
			
		}
		
		// it is here, because of breadcrumbs
		parent::beforeRender();
		
		$this->template->product = $product;
		$this->template->category = $category;
		$this->template->page = $this->actualPage;
		$this->template->products = $products;
		$this->template->title = $title;
		$this->template->categories = $categories;
	}
	
	public function renderDefault($id){
		
		$this->template->id = $id;
	}
	
	private function addBreadcrumbsItem($item, $product = NULL){
		
		if($product){
			$title = $product->getTitle();
			$path = '/' . $product->getSlug();
		}
		else{
			$title = $item->getTitle();
			$path = '';
		}
		
		$this->addToBreadcrumbs($this->actualPage->getId(), 
				'Eshop',
				'Categories',
				$title,
				$this->actualPage->getPath() . '/' . $item->getPath() . $path
			);
	}
	
	public function listBox($context, $fromPage){
		$realestate = $context->em->getRepository('WebCMS\RealestateModule\Doctrine\RealEstate')->findBy(array(
			'language' => $context->language
		), array('id' => 'DESC'), 5);
		
		$template = $context->createTemplate();
		$template->setFile('../app/templates/realestate-module/Realestate/boxes/listBox.latte');
		$template->link = $context->link(':Frontend:Realestate:RealEstate:default', array(
			'id' => $fromPage->getId(),
			'path' => $fromPage->getPath(),
			'abbr' => $context->abbr
				));
		$template->items = $realestate;
		
		return $template;
	}
}