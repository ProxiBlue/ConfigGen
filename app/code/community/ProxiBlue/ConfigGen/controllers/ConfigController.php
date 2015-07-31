<?php

class ProxiBlue_ConfigGen_ConfigController extends Mage_Adminhtml_Controller_Action
{

    /**
     * Initialize the controller
     *
     * @return Object
     */
    protected function _initAction()
    {


        $this->loadLayout()
            ->_setActiveMenu('configgen/configform');

        return $this;
    }

    /**
     * Index Action
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_forward('config');
    }

    /**
     * Display report grid
     *
     * @return void
     */
    public function configAction()
    {

        $block = $this->getLayout()->createBlock(
            'configgen/adminhtml_configform', 'configgen.adminhtml.configform.grid'
        );
        $this->_initAction()
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Tools'), Mage::helper('adminhtml')->__('ProxiBlue Config Generator')
            )
            ->_addContent($block)
            ->renderLayout();
    }

    public function gridAction()
    {

        $this->loadLayout();

        return $this->getResponse()->setBody(
            $this->getLayout()->createBlock('configgen/adminhtml_configform/grid')->getGridHtml()
        );
    }

    public function massRemoveAction()
    {
        try {
            $ids = $this->getRequest()->getPost('ids', array());
            foreach ($ids as $id) {
                $model = Mage::getModel("configgen/config");
                $model->setId($id)->delete();
            }
            Mage::getSingleton("adminhtml/session")->addSuccess(
                Mage::helper("adminhtml")->__("Item(s) was successfully removed")
            );
        } catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

    public function massConfigGenAction()
    {
        $configArray = array();
        try {
            $ids = $this->getRequest()->getPost('ids', array());
            foreach ($ids as $id) {
                $model = Mage::getModel("configgen/config")->load($id);
                $configArray[] = $model->getData();
            }
            $output = $this->getLayout()->createBlock('core/template')
                ->setTemplate('proxiblue_configgen.phtml')
                ->setConfigItems($configArray);
            $io = new Varien_Io_File();
            $path = Mage::getBaseDir('var') . DS . 'export' . DS;
            $name = md5(microtime());
            $file = $path . DS . $name . '.php';
            $io->setAllowCreateFolders(true);
            $io->open(array('path' => $path));
            $io->streamOpen($file, 'w+');
            $io->streamLock(true);
            $io->streamWrite($output->toHtml());
            $io->streamUnlock();
            $io->streamClose();
            $this->_prepareDownloadResponse(
                'proxiblue_generated_config.php', array(
                'type' => 'filename',
                'value' => $file,
                'rm' => true // can delete file after use
            )
            );


            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Config was generated"));
        } catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'configform.csv';
        $grid = $this->getLayout()->createBlock('configgen/adminhtml_configform_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName = 'configform.xml';
        $grid = $this->getLayout()->createBlock('configgen/adminhtml_configform_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }


}
