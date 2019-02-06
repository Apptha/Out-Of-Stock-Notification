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

class Apptha_Outofstocknotification_Adminhtml_OutofstocknotificationController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('outofstocknotification/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
	
		$this->_initAction()->renderLayout();
		
			
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('outofstocknotification/outofstocknotification')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('outofstocknotification_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('outofstocknotification/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('outofstocknotification/adminhtml_outofstocknotification_edit'))
				->_addLeft($this->getLayout()->createBlock('outofstocknotification/adminhtml_outofstocknotification_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('outofstocknotification')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('outofstocknotification/outofstocknotification');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $outofstocknotificationIds = $this->getRequest()->getParam('outofstocknotification');
        if(!is_array($outofstocknotificationIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                /*echo '<pre>'; print_r($outofstocknotificationIds); 
				$collection = Mage::getModel('outofstocknotification/outofstocknotification')->getCollection(); 
				$collection->addFieldToFilter('outofstocknotification_id',array('in'=>$outofstocknotificationIds));
				$collection->printLogQuery(true); */
			
                foreach ($outofstocknotificationIds as $outofstocknotificationId) {
                    /* $outofstocknotification = Mage::getModel('outofstocknotification/outofstocknotification')->load($outofstocknotificationId);
                    $outofstocknotification->delete(); */
					Mage::helper('outofstocknotification')->massDeleteById($outofstocknotificationId);
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($outofstocknotificationIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $outofstocknotificationIds = $this->getRequest()->getParam('outofstocknotification');
        if(!is_array($outofstocknotificationIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($outofstocknotificationIds as $outofstocknotificationId) {
                      /* $outofstocknotification = Mage::getSingleton('outofstocknotification/outofstocknotification')
                        ->load($outofstocknotificationId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save(); */
				  Mage::helper('outofstocknotification')->massStatusUpdate($outofstocknotificationId,$this->getRequest()->getParam('status'));
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($outofstocknotificationIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'outofstocknotification.csv';
        $content    = $this->getLayout()->createBlock('outofstocknotification/adminhtml_outofstocknotification_grid')
            ->getCsv();	

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'outofstocknotification.xml';
        $content    = $this->getLayout()->createBlock('outofstocknotification/adminhtml_outofstocknotification_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
		/* This is old format to download the file */
       // $response->setBody($content);
       // $response->sendResponse();
       // die;
	   /* This is new format to download the file */
	   $this->getResponse()->setBody($content);
    }
}