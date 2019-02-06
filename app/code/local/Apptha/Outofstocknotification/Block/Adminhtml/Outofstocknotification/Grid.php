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
class Apptha_Outofstocknotification_Block_Adminhtml_Outofstocknotification_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
  	
      parent::__construct();
      $this->setId('outofstocknotificationGrid');
      $this->setDefaultSort('outofstocknotification_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('outofstocknotification/outofstocknotification')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
  	 $this->addColumn('outofstocknotification_id', array(
          'header'    => Mage::helper('outofstocknotification')->__('ID'),
          'align'     =>'center',
          'width'     => '50px',
          'index'     => 'outofstocknotification_id',
      ));

      $this->addColumn('product_id', array(
          'header'    => Mage::helper('outofstocknotification')->__('Product ID'),
          'align'     =>'center',
          'width'     => '50px',
          'index'     => 'product_id',
      ));

	  
      $this->addColumn('product_name', array(
			'header'    => Mage::helper('outofstocknotification')->__('Product Name'),
                        'align'     =>'left',
			'width'     => '200px',	
			'index'     => 'product_name',
      ));
      $this->addColumn('email_id', array(
			'header'    => Mage::helper('outofstocknotification')->__('Customer Email Id'),
                        'align'     =>'left',
                        'width'     => '50px',
			'index'     => 'email_id',
      ));
	  $this->addColumn('mailsend_status', array(
			'header'    => Mage::helper('outofstocknotification')->__('Mail Status'),
			'width'     => '50px',
			'index'     => 'mailsend_status',
	   		'align'     =>'center',
      ));
      $this->addColumn('Notify_Added', array(
			'header'    => Mage::helper('outofstocknotification')->__('Notify Added'),
			'width'     => '50px',
			'index'     => 'created_time',
	   		'align'     =>'left',
			'type'      => 'datetime',
            'gmtoffset' => true
      ));
	  
      $this->addColumn('Notify_Updated', array(
			'header'    => Mage::helper('outofstocknotification')->__('Notify Updated'),
			'width'     => '50px',
			'index'     => 'update_time',
	   		'align'     =>'left',
			'type'      => 'datetime',
            'gmtoffset' => true
      ));

 
		$this->addExportType('*/*/exportCsv', Mage::helper('outofstocknotification')->__('CSV'));
		
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('outofstocknotification_id');
        $this->getMassactionBlock()->setFormFieldName('outofstocknotification');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('outofstocknotification')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('outofstocknotification')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('outofstocknotification/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
       
        return $this;
    }

  public function getRowUrl($row)
  {
  
  }

}
