<?php
/**
 * @category   ASPerience
 * @package    Asperience_Productextend
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Asperience_Productextend_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Attributes
{
    protected function _prepareForm()
    {
    	parent::_prepareForm();
    	$form = $this->getForm();
        if ($group = $this->getGroup()) {
       	 	if ($promotion = $form->getElement('promotion_list')) {
                $promotion->setRenderer(
					$this->getLayout()->createBlock('productextend/adminhtml_catalog_product_edit_tab_price_promotion')
                );
            }
        }
    }
}
