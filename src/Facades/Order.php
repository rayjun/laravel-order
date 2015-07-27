<?php

namespace Rayjun\LaravelOrder\Facades;

/**
 * Created by PhpStorm.
 * User: ray
 * Date: 15/7/27
 * Time: 上午11:15
 */

use Illuminate\Support\Facades\Facade;


class Order extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'order';
    }
}