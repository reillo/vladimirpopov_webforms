<?php
class VladimirPopov_WebForms_Adminhtml_CustomerController
    extends Mage_Adminhtml_Controller_Action
{
    protected function _init(){
        $customer = Mage::getModel('customer/customer')->load($this->getRequest()->getParam('id'));
        Mage::register('current_customer',$customer);
    }

    public function resultsAction(){
        $this->_init();


        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('webforms/adminhtml_customer_tab_results')->toHtml()
        );
    }
}