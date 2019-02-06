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


$installer = $this;

$installer->startSetup();

$installer->run("

 DROP TABLE IF EXISTS {$this->getTable('outofstocknotification')};
CREATE TABLE IF NOT EXISTS {$this->getTable('outofstocknotification')} (
    `outofstocknotification_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` varchar(20) NOT NULL DEFAULT '',
  `product_name` varchar(100) NOT NULL DEFAULT '',
  `product_url` varchar(255) NOT NULL,
  `email_id` varchar(100) NOT NULL,
  `mailsend_status` varchar(5) NOT NULL DEFAULT 'NO',
  `status` smallint(1) NOT NULL DEFAULT '1',
  `created_time` varchar(20) DEFAULT NULL ,
  `update_time` varchar(20) DEFAULT NULL ,
   PRIMARY KEY (`outofstocknotification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 
