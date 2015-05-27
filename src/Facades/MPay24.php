<?php

namespace Stalinko\LaravelMPay24\Facades;

use Illuminate\Support\Facades\Facade;

class MPay24 extends Facade {
    protected static function getFacadeAccessor() { return 'mpay24'; }
}