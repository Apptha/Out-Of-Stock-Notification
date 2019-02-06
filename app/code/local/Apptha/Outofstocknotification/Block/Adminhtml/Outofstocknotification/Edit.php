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

class Apptha_Outofstocknotification_Block_Adminhtml_Outofstocknotification_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'outofstocknotification';
        $this->_controller = 'adminhtml_outofstocknotification';
        
        $this->_updateButton('save', 'label', Mage::helper('outofstocknotification')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('outofstocknotification')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('outofstocknotification_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'outofstocknotification_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'outofstocknotification_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('outofstocknotification_data') && Mage::registry('outofstocknotification_data')->getId() ) {
            return Mage::helper('outofstocknotification')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('outofstocknotification_data')->getTitle()));
        } else {
            return Mage::helper('outofstocknotification')->__('Add Item');
        }
    }
}