<?php
/**
 * @category   ASPerience
 * @package    Asperience_Productextend
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Asperience_Productextend_Block_Adminhtml_Catalog_Product_Edit_Tab_Price_Promotion extends Mage_Adminhtml_Block_Widget implements Varien_Data_Form_Element_Renderer_Interface
{

    public function __construct()
    {
        $this->setTemplate('productextend/catalog/product/edit/price/promotion.phtml');
    }

    public function getProduct()
    {
        return Mage::registry('product');
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

	public function loadPromotions()
    {
		return Mage::getModel('productextend/promotion')->loadAllPromoByProduct($this->getProduct()->getId());
    }
    
	public function getPromotion($idPromotion)
    {
        return Mage::getModel('productextend/promotion')->load($idPromotion);
    } 
    
    public function setElement(Varien_Data_Form_Element_Abstract $element)
    {
        $this->_element = $element;
        return $this;
    }

    public function getElement()
    {
        return $this->_element;
    }

}