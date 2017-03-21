<?php

class Xpscommerce_CustomoptionsPricegrid_Adminhtml_ProductsController 
    extends Mage_Adminhtml_Controller_action
{
    public function indexAction()
    {
        $block = Mage::app()->getLayout()
                  ->createBlock('customoptionspricegrid/adminhtml_products') 
                  ->setTemplate('customoptionspricegrid/products.phtml');

        echo $block->toHtml();
    }

    public function saveAction()
    {
        $products = $this->getRequest()->getParam('products');

        foreach ($products as $productId => $priceData) {
          $product = Mage::getModel('catalog/product')->load($priceData['product_id']);
          if ($priceData['option_type_id']) {
              $price = $priceData['price'];
              $resource = Mage::getSingleton('core/resource');
							$writeConnection = $resource->getConnection('core_write');
							$table = $resource->getTableName('catalog/product_option_type_price');
							$query = "UPDATE {$table} SET price = '{$price}' WHERE option_type_id = "
													 . (int)$priceData['option_type_id'];
							$writeConnection->query($query);
          } else {
            $product->setPrice($priceData['price']);
            $product->save();
          }
        }
    }
} 

