<?php
/**
 * @category   ASPerience
 * @package    Asperience_Productextend
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class Asperience_Productextend_Block_Adminhtml_Promotion_Grid_Renderer_Products extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract 
{
    public function render(Varien_Object $row)
    {
        try{
        	$product = Mage::getModel('catalog/product')->load($row->getProductId());
        	return $product->getName()." [".$product->getSku()."]";
        }catch (Exception $e) {
        	return '<span style="color:red;">'.Mage::helper('productextend')->__('Deleted product ').'</span>';
        }
    }
}