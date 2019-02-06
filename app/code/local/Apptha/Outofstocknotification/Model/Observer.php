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
class Apptha_Outofstocknotification_Model_Observer extends Mage_Core_Model_Abstract {

    private $stockNotifiTable;
    private $read;
    private $bcc;
    private $productName;
    private $productPrice;
    private $productUrl;
    private $prodcutImg;
    private $siteLink;
    private $productDescr;
    private $storeName;

    const XML_PATH_EMAIL_ADMIN_QUOTE_NOTIFICATION = 'Outofstocknotification/outofstock_email/outofstock_credit_template';

    public function _construct() {

        parent::_construct();
        $this->_init('outofstocknotification/outofstocknotification');
        $isArray = count(Mage::app()->getRequest()->getParams());
        $resource = Mage::getSingleton('core/resource');
        $this->read = $resource->getConnection('write');
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
        $this->stockNotifiTable = $tPrefix . 'outofstocknotification';
        $this->siteLink = Mage::getBaseUrl();
    }

    //Method to send an email to notified customers
    public function sendMailToNotifiedCustomerOk($observer) {


        $enableOutOfStock = Mage::getStoreConfig('Outofstocknotification/general/activate_apptha_outofstock_enable'); //backend you given module status in off then dont exe this codings
        $product = $observer->getProduct();  //get the current product
        $isInStock = $product['stock_data']['is_in_stock'];
        $enableOutOfStock = intval($enableOutOfStock); //if the product is out of stock val = 0 , that time dont send mails
        $productUrl = $product->getUrlInStore();
        $stockLevel = (int) Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();
        $status = $product->getStatus();

        //echo "<pre>";    	print_r($product);echo "</pre>";die;
        $product_id = $product->getId();
        $products = Mage::getModel('catalog/product')->load($product_id);

        $this->productPrice = $product->getPrice();

        if ($product->_isObjectNew) {
            return 1;
        }

        if (!$isInStock) { //if it is a out of stock product no need to do any task
            return 1;
        }
        if (!$enableOutOfStock) {  // if out of stock is disable then dont do any task just return
            return 1; //if outof stock notifi status is no then dont exe all funs
        }
        $this->storeName = Mage::getStoreConfig("general/store_information/name");
        $this->productDescr = $product->getDescription();

        $getProductImageList = json_decode($product->_data['media_gallery']['images']); //get images
        for ($i = 0; $i < count($getProductImageList); $i++) {
            if (!$getProductImageList[$i]->removed) {
                if (!$getProductImageList[$i]->disabled && $getProductImageList[$i]->position == 1 && !$getProductImageList[$i]->removed) {
                    $prodcutImageIs = $getProductImageList[$i]->url;
                    break;
                } else if (!$getProductImageList[$i]->disabled && !$getProductImageList[$i]->removed) {
                    $prodcutImageIs = $getProductImageList[$i]->url;
                }
            }
        }
        $this->prodcutImg = $product->getImageUrl(); 
		
		$product = Mage::getModel('catalog/product')->load($product_id);
		//echo Mage::helper('checkout/cart')->getAddUrl($product);
		//echo $product_id;
		//echo Mage::helper('checkout/cart')->getAddUrl($product_id);
		//echo Mage::getUrl('checkout/cart/add', array('product' => $product_id));
		//exit;

        if (!strlen($this->prodcutImg)) {

            $this->prodcutImg = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . DS . 'frontend' . DS . 'default' . DS . 'default' . DS . 'outofstocknotification' . DS . 'defaultimage.jpg';
        }


        if (trim($product->_data['type_id']) == 'grouped' || trim($product->_data['type_id']) == 'bundle') {
            $stockLevel = 2; //quantity is not check for grouped
        }

        if ($status && ($stockLevel > 0)) {
            $productId = $product->getId();
            $mailFunCallOrNot = $this->isProductInNotifiyList($productId);  //find this product in notify list or not
            if ($mailFunCallOrNot) {
                $this->_sendNotificationEmail($this->bcc);
                $this->updateMailAndStatusOfNotifiy($productId); //changes the status of mailsend_status and status
            }
        } else {

            return false;  //product is out of stock
        }
    } //function is end hear
    
    //Method to get the notified list
    private function isProductInNotifiyList($productId) {

        $this->bcc = array();
        $mailStaus = 'NO';

        $collection = Mage::getModel('outofstocknotification/outofstocknotification')->getCollection()
                ->addFieldToFilter('product_id', $productId)
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('mailsend_status', $mailStaus);
        $collection->getSelect()->group(array("email_id"));
        $isArray = $collection->getSize();
        if ($isArray) {
            foreach ($collection as $productlist) {
                $this->bcc[] = $productlist->getEmailId();
            }
            $this->productUrl = base64_decode($productlist['product_url']);
            $this->productName = $productlist['product_name'];
            return 1;
        } else {
            return 0;
        }
    }

    /* Send mail to user when product stock is available */

    public function _sendNotificationEmail($to, $templateConfigPath = self::XML_PATH_EMAIL_ADMIN_QUOTE_NOTIFICATION) {
        if (!$to)
            return;

        $this->productDescr = substr($this->productDescr, 0, 420);
        if (strlen($this->productDescr) > 420) {
            $this->productDescr .= '...';
        }
	
        $emailTemplateVariables = array();
        $emailTemplateVariables['productName'] = $this->productName;
        $emailTemplateVariables['productPrice'] = $this->productPrice;
        $emailTemplateVariables['productUrl'] = $this->productUrl;
        $emailTemplateVariables['productImg'] = $this->prodcutImg;
        $emailTemplateVariables['storeName'] = $this->storeName;
        $emailTemplateVariables['siteLink'] = $this->siteLink;
        $emailTemplateVariables['productDesc'] = $this->productDescr;

        $marchentNotificationMailId = Mage::getStoreConfig('Outofstocknotification/outofstock_email/outofstock_sender_email_identity');
        $senderMailId = Mage::getStoreConfig("trans_email/ident_$marchentNotificationMailId/email");
        $senderName = Mage::getStoreConfig("trans_email/ident_$marchentNotificationMailId/name");
        $templeId = (int) Mage::getStoreConfig('Outofstocknotification/outofstock_email/outofstock_credit_template');

        //if it is user template then this process is continue
        if ($templeId) {
            $emailTemplate = Mage::getModel('core/email_template')->load($templeId);
        } else {   //  we are calling default template
            $emailTemplate = Mage::getModel('core/email_template')
                    ->loadDefault('outofstock_email_template');
        }
		$storeName = Mage::app()->getStore()->getName();

        $emailTemplate->setSenderName($senderName);     //mail sender name
        $emailTemplate->setSenderEmail($senderMailId);  //mail sender email id
        $emailTemplate->setTemplateSubject('Out of stock Notification from ' . $storeName);
        $emailTemplate->setDesignConfig(array('area' => 'frontend'));
        $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables); //it return the temp body
	   
        foreach ($to as $recipient) {
            $emailTemplate->send($recipient, $senderName, $emailTemplateVariables);  //send mail to customer email ids
        }
    }

    //Method to update the customers list who received the email
    private function updateMailAndStatusOfNotifiy($productId) {
        $deleteNotify = (int) Mage::getStoreConfig('Outofstocknotification/general/delete_apptha_outofstock_mail');
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        if ($deleteNotify) {
            $where = "product_id = $productId";
            $write->delete($this->stockNotifiTable, $where);
        } else {
            //$date = date("M d, Y");
			//$date = date('Y-m-d H:i:s');
			$date = Mage::getModel('core/date')->date('Y-m-d H:i:s', time());
            $mailSend = 'YES';
            $deleteNotify = 1;
            $data = array('mailsend_status' => $mailSend, 'status' => $deleteNotify, 'update_time' => $date);
            $where = "product_id = $productId";
            $write->update($this->stockNotifiTable, $data, $where);
        }
    }

    /* Send mail to Merchant when product stock is low */

    public function sendMailToNotifiyMerchant($observer) {

        //check if Enable out of stock 
        $enableOutOfStock = Mage::getStoreConfig('Outofstocknotification/general/activate_apptha_outofstock_enable');

        //check if Enable out of stock threshod qty of product stock
        $enableOutOfStockAdminNotify = Mage::getStoreConfig('Outofstocknotification/general/merchant_apptha_outofstock_mail');

        // store currency code eg. USD, INR
        $currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();

        // store currency symbol eg. $ 
        $currency_symbol = Mage::app()->getLocale()->currency($currency_code)->getSymbol();

        if (intval($enableOutOfStockAdminNotify) > 0 && intval($enableOutOfStock) > 0) {

            // get threshold qty as mentioned by Merchant
            $thresholdQty = Mage::getStoreConfig('Outofstocknotification/general/stocklimit_apptha_outofstock_mail');

            $orderIds = $observer->getData('order_ids');
            foreach ($orderIds as $_orderId) {
                $order = Mage::getModel('sales/order')->load($_orderId);
                $items = $order->getAllItems();
                foreach ($items as $itemId => $item) {
                    $ids[] = $item->getProductId();
                }
            }

            foreach ($ids as $pid) {

                $products = Mage::getModel('catalog/product')->load($pid);

                $producttype = $products->getTypeId();

                if ($producttype == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) { // check if product is configurable
                    $flag = 1;
                } else {
                    $flag = 0;
                }


                $qty = $products->getStockItem()->getQty();

                if ($thresholdQty >= $qty && $flag != 1) {

                    $emailTemplateVariables = array();

                    $emailTemplateVariables['productName'] = $products->getName();
                    $emailTemplateVariables['productPrice'] = $currency_symbol . $products->getPrice();
                    $emailTemplateVariables['productUrl'] = $products->getProductUrl();
                    $emailTemplateVariables['productImg'] = $products->getImageUrl();
                    $emailTemplateVariables['productQty'] = $qty;
                    $emailTemplateVariables['thresholdQty'] = $thresholdQty;
                    $emailTemplateVariables['productDesc'] = $products->getDescription();
                    $marchentNotificationMailId = Mage::getStoreConfig('Outofstocknotification/outofstock_email/outofstock_sender_email_identity');
                    $senderMailId = Mage::getStoreConfig("trans_email/ident_$marchentNotificationMailId/email");
                    $senderName = Mage::getStoreConfig("trans_email/ident_$marchentNotificationMailId/name");
                    $templeId = (int) Mage::getStoreConfig('Outofstocknotification/general/outofstock_admin_template');

                    //if it is user template then this process is continue
                    if ($templeId) {
                        $emailTemplate = Mage::getModel('core/email_template')->load($templeId);
                    } else {   //  we are calling default template
                        $emailTemplate = Mage::getModel('core/email_template')
                                ->loadDefault('Outofstocknotification_general_outofstock_admin_template');
                    }

                    $emailTemplateVariables['storeName'] = Mage::getStoreConfig("general/store_information/name");
                    $emailTemplateVariables['siteLink'] = Mage::getBaseUrl();

                    $toMailId = $senderMailId;
					
					

                    $emailTemplate->setSenderName($senderName);     //mail sender name
                    $emailTemplate->setSenderEmail($senderMailId);  //mail sender email id
                    $emailTemplate->setTemplateSubject('Out of stock Notification from ' . $emailTemplateVariables['storeName']);
                    $emailTemplate->setDesignConfig(array('area' => 'frontend'));
                    $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables); //it return the temp body
					
                    $emailTemplate->send($toMailId, $senderName, $emailTemplateVariables);  //send mail to admin email ids
                }
            }
        }
    }

}