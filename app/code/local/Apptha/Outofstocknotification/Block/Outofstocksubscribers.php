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

class Apptha_Outofstocknotification_Block_Outofstocksubscribers extends Mage_Core_Block_Template
{
	//Function to get all review collection
       protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $review_collection = $this->getCustomer();            
        $this->setCollection($review_collection);
        $pager = $this->getLayout()
                ->createBlock('page/html_pager', 'my.pager')                   
                ->setCollection($review_collection);           
        $this->setChild('pager', $pager);
        return $this;
    }
   //Function to get the Pagination
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    //Function to get all review collection
    function getCustomer()
    {
       if (Mage::getSingleton('customer/session')->isLoggedIn()) {
           $customer    = Mage::getSingleton('customer/session')->getCustomer();
           $customerEmail = trim($customer->getEmail());
           
           $subscribersProductCollection = Mage::getModel('outofstocknotification/outofstocknotification')->getCollection()
								->addFieldToFilter('email_id',$customerEmail);
       }     
       return $subscribersProductCollection; 
   } 
}