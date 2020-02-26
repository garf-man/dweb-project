<?php


namespace App\Components;

use App\Models\Product;
use Psr\Container\ContainerInterface;

include('CartInterface.php');

class Cart implements CartInterface
{


    public $items = [];

    public function add($id, $qty, $price)
    {
        $this->items = $this->get();
        if (!empty($this->items) && array_key_exists($id, $this->items)) {
            $this->increment($id,$qty);
        } else {
            $this->addFirstItems($id,$qty,$price);
            $this->save();
        }
    }


    public function addFirstItems ($id,$qty,$price)
    {
        if($_SESSION['currency']['name'] != 'BYN'){

            $this->items[$id] = [
                'id' => $id,
                'price' => round($price * $_SESSION['currency']['value']),
                'qty' => $qty,
            ];
        }else {
            $this->items[$id] = [
            'id' => $id,
            'price' => $price,
            'qty' => $qty,
            ];
        }
    }

    public function edit($id, $qty, $action)
    {
        if (in_array($action, ['incr', 'decr'])) {
            switch ($action) {
                case 'incr':
                    $this->increment($id, $qty);
                    break;
                case 'decr':
                    $this->decrement($id, $qty);
                    break;
            }
        } else {
            throw new \Exception('unsupported action');
        }
    }

    public function decrement($id, $qty)
    {
        $this->items = $this->get();
        if ($this->items[$id]['qty'] <= 1) {
            $this->delete($id);
        } else {
            $this->items[$id]['qty'] = $this->items[$id]['qty'] - $qty;
        }
        $this->save();
    }

    public function increment($id, $qty)
    {
        $this->items = $this->get();
        $this->items[$id]['qty'] = $this->items[$id]['qty'] + $qty;
        $this->save();
    }


    public function delete($id)
    {
        $this->items = $this->get();
        unset($this->items[$id]);
        $this->save();
    }

    public function get()
    {
        if (!empty ($_SESSION['cart'])){
            return $_SESSION['cart'];
        }
        return false;
    }

    public function count()
    {

        $sum = 0;
        if(!empty($this->get())){

        foreach ($this->get() as $key) {
            $sum += $key['qty'];
        }
            return $sum;
        }else {return $sum;}

    }

    public function save()
    {
        SessionHelpers::addByKey('cart',$this->items);
    }

    public function destroy()
    {
        SessionHelpers::destroyByKey('cart');
    }

    public function totalPrice()
    {
        $sum = 0;
        if (!empty($this->get())) {

            foreach ($this->get() as $key) {
                $sum += $key['price']/($_SESSION['currency']['value']) * $key['qty'];
            }
            return round($sum);
        } else {
            return $sum;
        }

    }
}