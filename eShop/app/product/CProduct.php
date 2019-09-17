<?php
namespace app\product;

class CProduct 
{
    private $weight = 0;
    private $cost = 0;
    private $name = "";
    private $description = "";
    private $vogue = 0;
    private $category = "";
    private $count = 0;
    private $img = "";
    private $id = 0;
    private $receipt_data = "";
    private $count_in_cart = 0;
    private $summ_cost = 0;
    private $class = '';
    private $in_stok = '';
    private $order_date = '';
    private $class_catalog_item = '';
    private $class_filterby_item = '';
    private $class_sortby_item = '';
    private $class_goods_item_count = '';
    private $class_for_a = '';


    public function __get($name)
    {
        return $this->$name ?? false;
    }

    public function __set($name, $value)
    {
        if(isset($this->$name))
        {
            $this->name = $value;
            return true;
        }
        return false;
    }

    public function render($view) { //принимать параметр(какое представление взять)
        $path_to_view = "../app/views/"
        ."/product/"
        .$view. ".php";
        include ($path_to_view);
    }
    public function toArray()
    {
        $res = [];
        foreach($this as $key => $val) {
            $res[$key] = $val;
        }
        return $res;
    }
    public function fromArray($arr)
    {
        foreach($arr as $key => $val)
        {
            if(property_exists($this, $key)) 
                $this->$key = $val;
        }
    }
}