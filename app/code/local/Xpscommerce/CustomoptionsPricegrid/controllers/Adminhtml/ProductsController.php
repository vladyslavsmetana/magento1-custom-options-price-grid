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
        foreach ($products as $productId => $productData) {
            $product = Mage::getModel('catalog/product')->load($productId);
            $stockData = $product->getStockData();
            $stockData['qty'] = $productData['stock'];
            $stockData['is_in_stock'] = $stockData['qty'] ? 1 : 0;
            $product->setStockData($stockData);
            $product->save();
        }
    }
} 

