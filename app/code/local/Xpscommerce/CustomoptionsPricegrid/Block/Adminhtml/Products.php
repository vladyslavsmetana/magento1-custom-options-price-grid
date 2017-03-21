<?php

class Xpscommerce_CustomoptionsPricegrid_Block_Adminhtml_Products 
    extends Mage_Core_Block_Template
{
    /**
     * Search products
     **/
    public function getProducts()
    {
        $search = $this->getRequest()->getParam('search');
        $searchSKU = $this->getRequest()->getParam('search_sku');

        if ($search) {
            // search by product name or SKU
            $products = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter(
                    array(
                        array('attribute' => 'name', 'like' => "%{$search}%"),
                        array('attribute' => 'sku', 'like' => "%{$search}%"),
                        )
                    )
                ->load();
        } else {
            // no search parameters found
            $products = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('*')
                ->setPageSize(20)
                ->load();
        }

        // process search results
        $ret = array();
        $sindex_name = array();
        $sindex_price = array();
        $sindex_stock = array();

        //var_dump(412290, $_id);exit;
        foreach ($products as $product) {
            $product->load($product->getId());
            $_id = $product->getId();
            //$product = Mage::getModel('catalog/product')->load($_id);
            $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);

            $qty = $stock->getQty() * $stock->getIsInStock();
            $price = $product->getPrice();
            $ret[$_id]['name'] = $product->getName();
            $ret[$_id]['price'] = $price;
            $ret[$_id]['stock'] = round($qty);
            $ret[$_id]['sku'] = $product->getSku();
            $ret[$_id]['custom_option'] = 'default';
            $ret[$_id]['product_id'] = $_id;
            $ret[$_id]['option_type_id'] = 0;

            foreach ($product->getOptions() as $o) {
              $optionType = $o->getTitle();
              $values = $o->getValues();
              //var_dump(562493, $o->getData());exit;
              foreach ($values as $k  => $v) {
                $price = (float)$v->getPrice();
                $vtitle = $v->getTitle();

                $ret["{$_id}-{$k}"] = array(
                  'name'  => $product->getName(),
                  'price' => $price,
                  'stock' => round($qty),
                  'sku'   => $product->getSku(),
                  'custom_option' => "{$vtitle} ({$optionType})",
                  'option_type_id' => $k,
                  'product_id' => $_id,
                  );
              }
            }
        }

        return array('products' => $ret);
    }
}

