<?php
/**
 * @category   ASPerience
 * @package    Asperience_Productextend
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Asperience_Productextend_Block_Adminhtml_Promotion_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('promotionGrid');
      $this->setDefaultSort('promo_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }
  
  protected function _getStore()
  {
      $storeId = (int) $this->getRequest()->getParam('store', 0);
      return Mage::app()->getStore($storeId);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getResourceModel('productextend/promotion_collection');
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('promo_id', array(
          'header'    => Mage::helper('productextend')->__('ID'),
          'align'     =>'right',
          'width'     => '30px',
          'index'     => 'promo_id',
      ));

      $this->addColumn('products', array(
          'header'    => Mage::helper('productextend')->__('Products'),
      	  'renderer'  => 'Asperience_Productextend_Block_Adminhtml_Promotion_Grid_Renderer_Products',
          'align'     =>'left',
          'index'     => 'products',
      ));
      
      $this->addColumn('type',array(
          'header'  => Mage::helper('productextend')->__('Type'),
          'width'   => '60px',
          'index'   => 'type_id',
          'type'    => 'options',
          'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
       ));
      
      $this->addColumn('user', array(
          'header'    => Mage::helper('productextend')->__('User'),
          'align'     => 'left',
          'index'     => 'user',
      ));

      $store = $this->_getStore();
      $this->addColumn('price', array(
          'header'		  => Mage::helper('productextend')->__('Price'),
          'type'          => 'price',
          'currency_code' => $store->getBaseCurrency()->getCode(),
          'index' 		  => 'price',
      ));
        
      $this->addColumn('special_price', array(
          'header'    	  => Mage::helper('productextend')->__('Special price'),
          'type'  	  	  => 'price',
          'currency_code' => $store->getBaseCurrency()->getCode(),
          'index'     	  => 'special_price',
      ));
      
      $this->addColumn('special_from_date', array(
          'header'    => Mage::helper('productextend')->__('Special Price From Date'),
          'align'     =>'left',
          'index'     => 'special_from_date',
      	  'type'	  => 'datetime',
      ));
	  
      $this->addColumn('special_to_date', array(
          'header'    => Mage::helper('productextend')->__('Special Price To Date'),
          'align'     =>'left',
          'index'     => 'special_to_date',
      	  'type'	  => 'datetime',
      ));
      
      $this->addColumn('site', array(
          'header'    => Mage::helper('productextend')->__('Website'),
	  	  'width'     => '100px',
          'align'     =>'left',
	  	  'sortable'  => false,
          'index'     => 'website_id',
          'type'      => 'options',
          'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
      ));
      
       $this->addColumn('created_time', array(
          'header'    => Mage::helper('productextend')->__('Created At'),
          'align'     =>'left',
	  	  'type'      => 'datetime',
          'index'     => 'created_time',
      ));
	  
		
		$this->addExportType('*/*/exportCsv', Mage::helper('productextend')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('productextend')->__('XML'));
	  
     return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('promo_id');
        $this->getMassactionBlock()->setFormFieldName('productextend');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('productextend')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('productextend')->__('Are you sure?')
        ));

       
        return $this;
    }

}