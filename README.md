# laravel-order
laravel 框架中的 order 组件。

# 安装

可以直接在命令行中安装：

    composer require "rayjun/laravel-order:1.0.*"

或者可以在你项目中 `composer.json` 中加入以下代码：

    "require": {
        "rayjun/laravel-order": "1.0.*"
    }

然后再执行:

    composer update

在完成上面的安装之后，可以在 `config/app.php` 中的 `providers` 数组中增加以下代码：

    'Rayjun\LaravelOrder\LaravelOrderServiceProvider'

然后增加一行到

    'Order'   => 'Rayjun\LaravelOrder\Facades\Order',


# 使用

## 创建一个订单

    Order Order::order(array $items, int $user_id);

## 获取一个订单

    Order Order::getOrder($order_id);

## 获取用户的订单

    Collection Order::getUserOrders($user_id);

## 更改订单状态

    boolean Order::updateStatus(int $order_id, string $status);

## 删除一个订单

    boolean Order::deleteOrder(int $order_id);

## 更新订单项数量

    OrderItem Order::updateQty(int $item_id, int qty);

## 计算订单总价格

    float Order::total($order_id);

## 计算商品总数量

    int Order::count($order_id);

## 更新订单商品数量和总价

    boolean Order::updateOrder($order_id);
