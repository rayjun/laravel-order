<?php
/**
 * Created by PhpStorm.
 * User: ray
 * Date: 15/7/28
 * Time: 下午4:26
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use Rayjun\LaravelOrder\Model\OrderItem;
use Rayjun\LaravelOrder\Model\Order;

class OrderTest extends PhPUnit_Framework_TestCase {

    protected $capsule;

    protected $order;

    public function __construct()
    {
        $this->capsule = new Capsule;

        $this->capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'esyou',
            'username'  => 'homestead',
            'password'  => 'secret',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();

        $this->order = new Order();
    }


    public function testOrder()
    {
        $items = array();

        $item = new OrderItem();
        $item->good_id = 1;
        $item->price = 34.8;
        $item->count = 3;
        $item->color = 'red';
        $item->size = '12';

        $item2 = new OrderItem();
        $item2->good_id = 1;
        $item2->price = 34.8;
        $item2->count = 3;
        $item2->color = 'red';
        $item2->size = '12';
        array_push($items, $item);
        array_push($items, $item2);


        $order = $this->order->order($items, 1);

        $this->assertTrue($order instanceof Order);
    }

    public function testUpdateStatus()
    {
        $result = $this->order->updateStatus(10, $this->order->COMPLETE);

        $this->assertTrue($result);
    }

    public function testDeleteOrder()
    {
        $result = $this->order->deleteOrder(1);

        $this->assertTrue($result);
    }

    public function testUpdateQty()
    {
        $item = $this->order->updateQty(10, 4);

        $this->assertTrue($item->count == 4);
    }
    
}