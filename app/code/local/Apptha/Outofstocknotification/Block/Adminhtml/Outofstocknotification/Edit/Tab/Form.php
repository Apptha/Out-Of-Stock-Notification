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

class Apptha_Outofstocknotification_Block_Adminhtml_Outofstocknotification_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('outofstocknotification_form', array('legend'=>Mage::helper('outofstocknotification')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('outofstocknotification')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('outofstocknotification')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('outofstocknotification')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('outofstocknotification')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('outofstocknotification')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('outofstocknotification')->__('Content'),
          'title'     => Mage::helper('outofstocknotification')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getOutofstocknotificationData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getOutofstocknotificationData());
          Mage::getSingleton('adminhtml/session')->setOutofstocknotificationData(null);
      } elseif ( Mage::registry('outofstocknotification_data') ) {
          $form->setValues(Mage::registry('outofstocknotification_data')->getData());
      }
      return parent::_prepareForm();
  }
}