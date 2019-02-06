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
?>
<?php

class Apptha_Outofstocknotification_Block_Adminhtml_Outofstocknotification_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('outofstocknotification_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('outofstocknotification')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('outofstocknotification')->__('Item Information'),
          'title'     => Mage::helper('outofstocknotification')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('outofstocknotification/adminhtml_outofstocknotification_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}