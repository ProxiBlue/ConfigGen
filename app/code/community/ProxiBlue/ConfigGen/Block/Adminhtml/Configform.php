<?php

class ProxiBlue_ConfigGen_Block_Adminhtml_Configform extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = "adminhtml_configform";
        $this->_blockGroup = "configgen";
        $this->_headerText = Mage::helper("configgen")->__("ProxiBlue Config Generator");
//        $this->_addButtonLabel = Mage::helper("configgen")->__("Add New Item");
        parent::__construct();
        $this->_removeButton('add');
    }

    protected function _prepareLayout() {
        $this->setChild('grid', $this->getLayout()->createBlock($this->_blockGroup . '/' . $this->_controller . '_grid', $this->_controller . '.grid'));
        parent::_prepareLayout();
    }

}