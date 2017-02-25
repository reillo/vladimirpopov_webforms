<?php
class VladimirPopov_WebForms_CustomerController
    extends Mage_Core_Controller_Front_Action
{
    public function _init(){
        Mage::getSingleton('customer/session')->authenticate($this);
    }

    public function accountAction(){
        $this->_init();

        $webformId = $this->getRequest()->getParam('webform_id');
        $webform = Mage::getModel('webforms/webforms')->setStoreId(Mage::app()->getStore()->getId())->load($webformId);
        Mage::register('webform', $webform);

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($webform->getName());
        $this->getLayout()->getBlock('webforms_customer_account_form')->setData('webform_id',$webformId)->setData('scroll_to',1);

        $this->renderLayout();
    }

    public function resultAction(){
        $this->_init();

        $resultId = Mage::app()->getRequest()->getParam('id');
        $result = Mage::getModel('webforms/results')->load($resultId);
        Mage::register('result',$result);

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($result->getEmailSubject());

        $this->renderLayout();
    }
}