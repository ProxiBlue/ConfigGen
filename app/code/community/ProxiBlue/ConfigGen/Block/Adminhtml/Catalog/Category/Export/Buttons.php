<?php

class ProxiBlue_Configgen_Block_Adminhtml_Catalog_Category_Export_Buttons
    extends Mage_Adminhtml_Block_Catalog_Category_Abstract
{

    /**
     * Add buttons on category edit page
     *
     * @return Enterprise_CatalogEvent_Block_Adminhtml_Catalog_Category_Buttons
     */
    public function addButtons()
    {
        if (Mage::getIsDeveloperMode()
            && $this->getCategoryId()
            && $this->getCategory()->getLevel() > 1
        ) {
            $url = $this->helper('adminhtml')->getUrl(
                '*/*/configgen', array(
                    'category_id' => $this->getCategoryId()
                )
            );
            $this->getParentBlock()->getChild('form')
                ->addAdditionalButton(
                    'configgen_export', array(
                        'label'   => $this->helper('configgen')->__('Generate Config'),
                        'class'   => 'add',
                        'onclick' => 'setLocation(\'' . $url . '\')'
                    )
                );
        }

        return $this;
    }
}
