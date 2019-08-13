<?php
/**
 * @category   ASPerience
 * @package    Asperience_Productextend
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Asperience_Productextend_Model_Promotion extends Mage_Core_Model_Abstract
{
	
	protected $_promoCollection = null;
	
    public function _construct()
    {
        parent::_construct();
        $this->_init('productextend/promotion');
    }
    
	public function savePromo($product){
    	$this->setProductId($product->getId())
	    	->setPrice($product->getPrice())
			->setTypeId($product->getTypeId())	
	    	->setSpecialPrice($product->getSpecialPrice())
	    	->setSpecialFromDate($product->getSpecialFromDate())
	    	->setSpecialToDate($product->getSpecialToDate())
	    	->setUser(Mage::getSingleton('admin/session')->getUser()->getUsername())
	    	->setCreatedTime(date("Y-m-d G:i:s", Mage::getModel('core/date')->timestamp(time())));
	    if($webId = $product->getWebsiteIds()){
	    	$this->setWebsiteId($webId[0]);
	    }
	    $this->save();
    }
    
	public function loadAllPromoByProduct($productId)
    {
        if (is_null($this->_promoCollection)) {
            $this->_promoCollection =  Mage::getResourceModel('productextend/promotion')
                ->loadAllPromoByProduct($productId);
        }
        return $this->_promoCollection;
    
    }
    
}
