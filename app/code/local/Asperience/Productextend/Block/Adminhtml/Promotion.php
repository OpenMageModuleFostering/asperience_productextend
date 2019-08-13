<?php
/**
 * @category   ASPerience
 * @package    Asperience_Productextend
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Asperience_Productextend_Block_Adminhtml_Promotion extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
    	$this->_controller = 'adminhtml_promotion';
	    $this->_blockGroup = 'productextend';
	    $this->_headerText = Mage::helper('productextend')->__('Promotions history');
	    parent::__construct();
	    $this->_removeButton('add');
    }
}