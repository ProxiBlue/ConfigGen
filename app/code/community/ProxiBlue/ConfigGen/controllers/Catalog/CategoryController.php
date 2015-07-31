<?php

class ProxiBlue_ConfigGen_Catalog_CategoryController extends Mage_Adminhtml_Controller_Action
{


    public function configGenAction()
    {
        try {
            $id = $this->getRequest()->getParam('category_id');
            $category = mage::getModel('catalog/category')->load($id);
            if($category->getId()) {
                $data = $category->getData();
                $output = $this->getLayout()->createBlock('core/template')
                    ->setTemplate('proxiblue_configgen_category.phtml')
                    ->setConfigItems($data);
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
                    'data-upgrade-x.x.x-x.x.x.php', array(
                        'type' => 'filename',
                        'value' => $file,
                        'rm' => true // can delete file after use
                    )
                );

                Mage::getSingleton("adminhtml/session")->addSuccess(
                    Mage::helper("adminhtml")->__("Config was generated")
                );
            } else {
                Mage::getSingleton("adminhtml/session")->addError(
                    Mage::helper("adminhtml")->__("Config was not generated")
                );
            }
        } catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

}
