<?php
/**
 * @category   ASPerience
 * @package    Asperience_Productextend
 * @author     ASPerience - www.asperience.fr
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
$installer = $this;

$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('history_promotion')} (
  `promo_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(11) unsigned NOT NULL,
  `type_id` varchar(32) NOT NULL default 'simple',
  `user` varchar(50) default NULL,
  `price` decimal(12,4) NOT NULL default '0.0000',
  `special_price` decimal(12,4) NOT NULL default '0.0000',
  `special_from_date` datetime default NULL,
  `special_to_date` datetime default NULL,
  `website_id` smallint(5) unsigned default '0',
  `created_time` datetime default NULL,
  PRIMARY KEY  (`promo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

$installer->endSetup(); 
