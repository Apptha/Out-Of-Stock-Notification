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

class Apptha_Outofstocknotification_Model_Outofstocknotification extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('outofstocknotification/outofstocknotification');
    }

    public function notifyDataInserted() {
 
                        $pId = (int) Mage::app()->getRequest()->getParam('pId', false);

                        //get table prefix
						/* Old code 
                        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
                        $stockNotifiTable = $tPrefix . 'outofstocknotification'; */
							
                        if($pId){
									/* Old code 
                                    $read = Mage::getSingleton('core/resource')->getConnection('core_read');
                                    $email = (string)Mage::app()->getRequest()->getParam('email', false);
                                    $select = $read->select()
                                                    ->from($stockNotifiTable, 'COUNT(*) as isNotify')
                                                    ->where('product_id=' . $pId . ' AND status = 1 AND mailsend_status = "NO" AND email_id ="' . $email . '"');
                                    $data = $read->fetchAll($select);
                                    $isAlreadyNotify = intval($data[0]['isNotify']);  //check this user is already notify or not?
									*/
									$email = (string)Mage::app()->getRequest()->getParam('email', false);
									 $notifyCollection = Mage::getModel('outofstocknotification/outofstocknotification')->getCollection()
															 ->addFieldToFilter('product_id' , $pId )
												             ->addFieldToFilter('status',1)
															 ->addFieldToFilter('mailsend_status',"NO")
															 ->addFieldToFilter('email_id',$email); 
									
                                     $isAlreadyNotify = count($notifyCollection);  //check this user is already notify or not?

                                    if (!$isAlreadyNotify) { // not notify so insert in DB
                                                $newProduct = Mage::getModel('catalog/product')->load($pId);
                                                $pName = $newProduct->getName();
                                                $pUrl = base64_encode($newProduct->getUrlInStore());
                                                //$date = date("M d, Y");
												//$date = date('Y-m-d H:i:s');
												$date = Mage::getModel('core/date')->date('Y-m-d H:i:s', time());
                                                $data = array('product_id' => $pId, 'product_name' => $pName, 'product_url' => $pUrl, 'email_id' => $email, 'mailsend_status' => 'NO', 'created_time' => $date);
                                                $model = Mage::getModel('outofstocknotification/outofstocknotification')->setData($data);
                                                    try {
                                                        $insertId = $model->save();
                                                       }
                                                    catch (Exception $e) {
                                                        return $e->getMessage();
                                                    }

                                                return 1;
                                    } 
                                    else
                                    { // already notifided
                                    return 0;
                                    }
                        }
                        else
                            {
                            return 0;
                            }
    }

}