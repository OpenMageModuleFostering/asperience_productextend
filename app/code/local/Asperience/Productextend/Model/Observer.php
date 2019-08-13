<?php
/**
 * @category   ASPerience
 * @package    Asperience_Productextend
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Asperience_Productextend_Model_Observer
{
	
	const XML_PATH_EMAIL_RECIPIENT = 'catalog/productextend/recipient_email';
    const XML_PATH_EMAIL_SENDER    = 'catalog/productextend/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE  = 'catalog/productextend/email_price_template';
    
    const XML_PATH_ALLOW_NEW       = 'catalog/productextend/allow_new';
    const XML_PATH_ALLOW_PRICE     = 'catalog/productextend/allow_price';
    const XML_PATH_ALLOW_SPE_PRICE = 'catalog/productextend/allow_price_special';
    const XML_PATH_ALLOW_SAVE      = 'catalog/productextend/allow_save';

    
    protected $_oldProduct = array();
    protected $_isNew = False;
    protected $_isNewPrice = False;
    protected $_isNewPriceSpecial = False;
    
	protected function _sendMail($product)
    {
        if($product) {
           	try {
           		$translate = Mage::getSingleton('core/translate');
	            $translate->setTranslateInline(false);

	            //Préparation des données du mail
	           	$dataMail = array(
		           	'name' => $product->getName(),
		           	'sku'  => $product->getSku(),
					'type' => $product->getTypeId(),
	           		'user' => Mage::getSingleton('admin/session')->getUser()->getUsername(),
	           		'date' => Mage::helper('core')->formatDate(null, 'full')
	           	);
           	
	           	if($this->_isNew){
				//Si le nouveau produit est une promotion
					$dataMail['title'] = Mage::helper('productextend')->__('New product :');
					if(!$product->isGrouped()){
						$dataMail['price'] = Mage::helper('core')->currency($product->getPrice());
						if($this->_isNewPriceSpecial){
							$dataMail['special_price'] = Mage::helper('core')->currency($product->getSpecialPrice());
						}
					}
					
				} else {
					//Si le prix du produit existant a changé
					if($this->_isNewPrice){
						$dataMail['title'] = Mage::helper('productextend')->__('Price change :');
						$dataMail['price'] = Mage::helper('core')->currency($this->_oldProduct['price']);
		           		$dataMail['new_price'] = " -> ".Mage::helper('core')->currency($product->getPrice());
		           		$dataMail['special_price'] = Mage::helper('core')->currency($product->getSpecialPrice());
					}elseif($this->_isNewPriceSpecial){
						$dataMail['title'] = Mage::helper('productextend')->__('Special price change :');
						$dataMail['price'] = Mage::helper('core')->currency($product->getPrice());
						$dataMail['special_price'] = Mage::helper('core')->currency($this->_oldProduct['special_price']);
		           		$dataMail['new_special_price'] = " -> ".Mage::helper('core')->currency($product->getSpecialPrice());
					}elseif($this->_isNewPrice && $this->_isNewPriceSpecial){
						$dataMail['title'] = Mage::helper('productextend')->__('Special and Current price change :');
						$dataMail['price'] = Mage::helper('core')->currency($this->_oldProduct['price']);
		           		$dataMail['new_price'] = " -> ".Mage::helper('core')->currency($product->getPrice());
						$dataMail['special_price'] = Mage::helper('core')->currency($this->_oldProduct['special_price']);
		           		$dataMail['new_special_price'] = " -> ".Mage::helper('core')->currency($product->getSpecialPrice());
					}else{
						throw new Exception();
					}
				}
           		
	           	$postObject = new Varien_Object();
	            $postObject->setData($dataMail);
            	
	            //Configuration des destinataires
	            $emails = explode(',', Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
                //Configuration du mail
           		$mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
           		
           		foreach ($emails as $email) {
	                $mailTemplate->setDesignConfig(array('area' => 'backend'))
	                    ->sendTransactional(
	                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
	                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
	                        $email,
	                        null,
	                        array('data' => $postObject)
	                    );

	                if (!$mailTemplate->getSentSuccess()) {
	                    throw new Exception();
	                    break;
	                }
           		}

                $translate->setTranslateInline(true);
               
            }catch (Exception $e) {
                Mage::getSingleton('core/session')->addError(Mage::helper('productextend')->__('An error arose during the sending of emails.'));
            }
        }
    }
    
    public function savePromoAfter($observer)
    {
    	$product = $observer->getEvent()->getProduct();
    	
		//Si le produit est nouveau
		if($this->_isNew){
			//Si le nouveau produit est une promotion
			if(!$product->isGrouped() && $product->getSpecialPrice()){
				$this->_isNewPriceSpecial = True;
			}
		} else {
			//Si le prix du produit existant à changé
			if($product->getPrice() != $this->_oldProduct['price']){
				$this->_isNewPrice = True;
			}
			//Si le prix spécial du produit existant est nouveau ou changé
			if($product->getSpecialPrice() && ($product->getSpecialPrice() != $this->_oldProduct['special_price'] ||
	          $product->getSpecialFromDate() != $this->_oldProduct['special_date'])){
				$this->_isNewPriceSpecial = True;
			}
		}
		
		//s'il y a une promo: mise en base de données
        if($this->_isNewPriceSpecial && Mage::getStoreConfig(self::XML_PATH_ALLOW_SAVE)){
	        Mage::getModel('productextend/promotion')->savePromo($product);
        }

        //S'il y a un nouveau produit ou un changement de prix: envoie de mail
       	if($this->_isNew ||
	       	$this->_isNewPrice && Mage::getStoreConfig(self::XML_PATH_ALLOW_PRICE)|| 
	       	$this->_isNewPriceSpecial && Mage::getStoreConfig(self::XML_PATH_ALLOW_SPE_PRICE)){
       		$this->_sendMail($product);
        }
    }
    
	public function beforeSave($observer)
    {
    	$product   = $observer->getEvent()->getProduct();
        $productId = $product->getId();
    	
    	if($productId){
        	$product = Mage::getModel('catalog/product')
                ->load($productId);
	        $this->_oldProduct = array(
	        	'price' 		=> $product->getPrice(),
	        	'special_price' => $product->getSpecialPrice(),
	        	'special_date'  => $product->getSpecialFromDate()
	        );
    	} elseif(Mage::getStoreConfig(self::XML_PATH_ALLOW_NEW)){
	        $this->_isNew = True;
    	} 
    }
	
}
