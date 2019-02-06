<?php
/**
 * Apptha
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.apptha.com/LICENSE.txt
 *
 * ==============================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * ==============================================================
 * This package designed for Magento COMMUNITY edition
 * Apptha does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Apptha does not provide extension support in case of
 * incorrect edition usage.
 * ==============================================================
 *
 * @category    Apptha
 * @package     Apptha_Out-Of-Stock-Notification
 * @version     1.7
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2014 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 *
 * */
class Apptha_Outofstocknotification_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();     
		$this->renderLayout();
	
    }
    /* insert notify email record and display the success message */
    public function storeNotificationProductDataAction(){
        
       
		 
		 $this->getResponse()->setHeader('Access-Control-Allow-Origin: *');
    	 $notifySuccessMes =  Mage::getStoreConfig('Outofstocknotification/general/activate_apptha_outofstock_notify_success_mes');
    	//get current server
        
         $currentUrl = Mage::app()->getFrontController()->getRequest()->getHttpHost();
        $arrVal = parse_url($this->getRequest()->getServer('HTTP_REFERER'));
        $previousUrl = str_replace(array(",","www."),"",$arrVal["host"]);
        //check domain
         $domain = strstr($currentUrl, $previousUrl);
        
      
        if(!empty ($domain))
        {
                $statusOfInsert = Mage::getModel('outofstocknotification/outofstocknotification')->notifyDataInserted();
           	
    	if($statusOfInsert){ 
		
			$this->getResponse()->setBody($notifySuccessMes); 
			} else   {	$msg = "okay"; 
				 $this->getResponse()->setBody($msg); 
				
			}
        }
        else
        {
        return $this->__('Error');
        }
        
    }
	
	/* Action for Out of Stock Subscriptions page */
	public function outofstocksubscribersAction(){
			
			$this->loadLayout();
			$this->renderLayout();
			
	}
		
	/* Subscribers Delete Record Action */	
	public function outofstockSubscribProductDelAction(){
	
	
		 $delId = $this->getRequest()->getParam('delId');
		
		if(Mage::helper('outofstocknotification')->massDeleteById($delId)==1){
			Mage::getSingleton('core/session')->addSuccess($this->__('Deleted successfully'));
			$this->_redirect('outofstocknotification/index/outofstocksubscribers');
		}else{
			Mage::getSingleton('core/session')->addError($this->__('There was a problem while delete the record'));
			$this->_redirect('outofstocknotification/index/outofstocksubscribers');
			
		}	
	}
	
}