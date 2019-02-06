<?php
/**
 * @name         :  Apptha Out Of Stock Notification
 * @version      :  0.1.5
 * @since        :  Magento 1.4
 * @author       :  Apptha - http://www.apptha.com
 * @copyright    :  Copyright (C) 2011 Powered by Apptha
 * @license      :  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Creation Date:  June 20 2011
 * @Modified By  :  Murali B
 * @Modified Date:  Feb 20 2014
 *
 * */


class Apptha_Outofstocknotification_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function domainKey($tkey) {

        $message = "EM-OSNMP0EFIL9XEV8YZAL7KCIUQ6NI5OREH4TSEB3TSRIF2SI1ROTAIDALG-JW";
		$lenstr = strlen($tkey);
        for ($i = 0; $i < $lenstr; $i++) {
            $key_array[] = $tkey[$i];
        }
        $enc_message = "";
        $kPos = 0;
        $chars_str = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
		$lenCharStr = strlen($chars_str);
        for ($i = 0; $i < $lenCharStr; $i++) {
            $chars_array[] = $chars_str[$i];
        }
		$cKeyArr = count($key_array);
		$lenmessgae = strlen($message);
        for ($i = 0; $i < $lenmessgae; $i++) {
            $char = substr($message, $i, 1);

            $offset = $this->getOffset($key_array[$kPos], $char);
            $enc_message .= $chars_array[$offset];
            $kPos++;
            if ($kPos >= $cKeyArr) {
                $kPos = 0;
            }
        }

        return $enc_message;
    }

    public function getOffset($start, $end) {

        $chars_str = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
		$lenstrs = strlen($chars_str);
        for ($i = 0; $i < $lenstrs; $i++) {
            $chars_array[] = $chars_str[$i];
        }

        for ($i = count($chars_array) - 1; $i >= 0; $i--) {
            $lookupObj[ord($chars_array[$i])] = $i;
        }

        $sNum = $lookupObj[ord($start)];
        $eNum = $lookupObj[ord($end)];

        $offset = $eNum - $sNum;

        if ($offset < 0) {
            $offset = count($chars_array) + ($offset);
        }

        return $offset;
    }	
	public function massDeleteById($idd){
		$outofstocknotification = Mage::getModel('outofstocknotification/outofstocknotification')->load($idd);
        if($outofstocknotification->delete()){
			return 1;
		}else{
		    return 0;
		}
		
	}
	
	public function massStatusUpdate($outofstocknotificationId, $stat){
		$outofstocknotification = Mage::getSingleton('outofstocknotification/outofstocknotification')
                        ->load($outofstocknotificationId)
                        ->setStatus($stat)
                        ->setIsMassupdate(true)
                        ->save();
	}
	
	public function _getProductCollection($pId){
		return Mage::getModel('catalog/product')->load($pId);
	}
	
	public function _getOrderCollection($oId){
		return Mage::getModel('sales/order')->load($oId);
	}
	
	public function emailTempLoadId($templeId){
		return Mage::getModel('core/email_template')->load($templeId);
	}
	
	public function _getDelUrl($dId){
		return Mage::getUrl('outofstocknotification/index/outofstockSubscribProductDel',array('delId'=>$dId));
	}
}
