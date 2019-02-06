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


class Apptha_Outofstocknotification_Block_View extends Mage_Catalog_Block_Product_View
{
    protected function _prepareLayout()
    {   
        $enableOutOfStock = Mage::getStoreConfig('Outofstocknotification/general/activate_apptha_outofstock_enable');
        $enableOutOfStock = intval($enableOutOfStock);
        if($enableOutOfStock == 0){
        return ;
        }
        
        $simpleBlock  = $this->getLayout()->getBlock('product.info.simple');
        $virtualBlock = $this->getLayout()->getBlock('product.info.virtual');
        
        $groupedBlock = $this->getLayout()->getBlock('product.info.grouped');
        $configurableBlock = $this->getLayout()->getBlock('product.info.configurable');

        //Mage_Downloadable_Block_Catalog_Product_View_Type
        if ($simpleBlock) {
            $simpleBlock->setTemplate('outofstocknotification/view.phtml');
        }
        else if ($virtualBlock) {
            $virtualBlock->setTemplate('outofstocknotification/view.phtml');
        }
        else if($configurableBlock){
            $configurableBlock->setTemplate('outofstocknotification/view.phtml');
           
        }
        else if($groupedBlock){
            
            $groupedBlock->setTemplate('outofstocknotification/view.phtml');
        }
        
    }
}


