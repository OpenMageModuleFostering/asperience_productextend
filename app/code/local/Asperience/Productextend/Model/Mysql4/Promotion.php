<?php
/**
 * @category   ASPerience
 * @package    Asperience_Productextend
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Asperience_Productextend_Model_Mysql4_Promotion extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('productextend/promotion', 'promo_id');
    }
    
	public function loadAllPromoByProduct($productId)
    {	
    	$select = $this->_getReadAdapter()->select()
            ->from(array('main_table'=>$this->getTable('promotion')))
            ->where('product_id=?', $productId);
        return $this->_getReadAdapter()->fetchCol($select);
    }
}