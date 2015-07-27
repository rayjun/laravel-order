<?php
namespace Rayjun\LaravelOrder\Model;


use Illuminate\Database\Eloquent\Model;

/**
 * Created by PhpStorm.
 * User: ray
 * Date: 15/7/27
 * Time: 上午11:04
 */

class Order extends Model {

    protected $table = 'orders';


    /**
     * 订单状态的定义
     * @var string
     */
    public $INIT = 'init';    // 订单初始化

    public $OBLIGATION = 'obligation';  // 订单待付款

    public $PROCESSING = 'processing';  // 订单处理中

    public $COMPLETE = 'complete'; // 收货完成交易


    /**
     * 创建一个新的订单
     * @param array $items
     * @param $user_id
     * @return Order
     */
    public function order(array $items, $user_id)
    {
        $order = new Order();
        $items_number = 0;
        $items_total = 0;

        $order->user_id = $user_id;
        $order->state = $this->INIT;
        $order->items_number = 0;
        $order->items_total = 0;

        $order->save();

        foreach($items as $item)
        {
            $items_number += $item->count;
            $items_total += $item->total_price;
            $item->order_id = $order->id;

            $item->save();
        }

        $order->item_number = $items_number;
        $order->item_total = $items_total;

        $order->save();

        return $order;
    }

    /**
     * 获取一个订单
     * @param $order_id
     * @return mixed
     */
    public function getOrder($order_id)
    {
        return findOrFail($order_id);
    }


    /**
     * 获取用户的所有订单
     * @param $user_id
     * @return mixed
     */
    public function getUserOrders($user_id)
    {
        return Order::where('user_id', $user_id)->get();
    }

    /**
     * 更新一个订单项的数量
     * @param $item_id
     * @param $qty
     * @return bool
     */
    public function updateQty($item_id, $qty)
    {
        $item = OrderItem::findOrFail($item_id);

        if($qty <= 0)
        {
            $this->removeItem($item->order_id, $item_id);
        }


        if(! $item)
        {
            return false;
        }

        $item->count = $qty;
        $item->save();

        $this->updateOrder($item->order_id);

        return true;
    }


    /**
     * 删除一个订单项
     * @param $item_id
     * @return bool
     */
    public function removeItem($order_id, $item_id)
    {
        $order = Order::findOrFail($order_id);

        $item = OrderItem::findOrFail($item_id);

        if(! ($order->id == $item->order_id))
        {
            return false;
        }

        if(! $item)
        {
            return false;
        }

        $item->delete();

        $this->updateOrder($order_id);

        return true;
    }

    /**
     * 改变订单状态
     * @param $order_id
     * @param $status
     * @return bool
     */
    public function updateStatus($order_id, $status)
    {
        if( ! ($status == $this->INIT || $status == $this->COMPLETE
            || $status == $this->OBLIGATION || $status == $this->PROCESSING))
        {
            return false;
        }

        $order = $this->getOrder($order_id);

        if(!$order)
        {
            return false;
        }

        $order->state = $status;
        $order->save();

        return true;
    }

    /**
     * 删除一个订单
     * @param $order_id
     * @return bool
     */
    public function delete($order_id)
    {
        $order = $this->getOrder($order_id);

        if(! $order)
        {
            return false;
        }

        OrderItem::where('order_id', $order->id)->delete();

        $order->delete();

        return true;
    }


    /**
     * 更新订单中商品数量和总价格
     * @param $order_id
     * @return bool
     */
    public function updateOrder($order_id)
    {
        $order = Order::findOrFail($order_id);

        if(! $order)
        {
            return false;
        }

        $order->items_total = $this->total($order_id);
        $order->items_number = $this->count($order_id);

        $order->save();

        return true;
    }

    /**
     * 计算订单中商品总数量
     * @param $order_id
     * @return int
     */
    public function total($order_id)
    {
        $items = OrderItem::where('order_id', $order_id)->get();
        $total = 0;

        foreach($items as $item)
        {
            $total += $item->total_price;
        }

        return $total;
    }

    /**
     * 计算订单中商品的总数
     * @param $order_id
     * @return int
     */
    public function count($order_id)
    {
        $items = OrderItem::where('order_id', $order_id)->get();

        $count = 0;

        foreach($items as $item)
        {
            $count += $item->count;
        }

        return $count;
    }
}