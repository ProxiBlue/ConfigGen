<?php

class ProxiBlue_ConfigGen_Block_Adminhtml_Configform_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId("configformGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("ASC");
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel("configgen/config")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn("id", array(
            "header" => Mage::helper("configgen")->__("Config ID"),
            "align" => "right",
            "width" => "10px",
            "type" => "number",
            "index" => "config_id",
        ));

        $this->addColumn("customer_id", array(
            "header" => Mage::helper("configgen")->__("Path"),
            "align" => "right",
            "width" => "50px",
            "index" => "path",
        ));

        $this->addColumn("firstname", array(
            "header" => Mage::helper("configgen")->__("Scope"),
            "align" => "right",
            "width" => "50px",
            "index" => "scope",
        ));

        $this->addColumn("lastname", array(
            "header" => Mage::helper("configgen")->__("Scope ID"),
            "align" => "right",
            "width" => "50px",
            "index" => "scope_id",
        ));

        $this->addColumn("email", array(
            "header" => Mage::helper("configgen")->__("Value"),
            "align" => "right",
            "width" => "100px",
            "index" => "value",
        ));

        
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return '#';
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('generate_config', array(
            'label' => Mage::helper('configgen')->__('Generate Config'),
            'url' => $this->getUrl('*/*/massConfigGen')
        ));
        $this->getMassactionBlock()->addItem('remove_configform', array(
            'label' => Mage::helper('configgen')->__('Delete'),
            'url' => $this->getUrl('*/*/massRemove'),
            'confirm' => Mage::helper('configgen')->__('Are you sure?')
        ));
        return $this;
    }

}