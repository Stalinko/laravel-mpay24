# laravel-mpay24

This package is a plugin for Laravel 5 for working with mpay24.com payments.

## Installation

Install using composer:
`composer require stalinko/laravel-mpay24`

Then add this to `providers` list in `config/app.php`:
```php
    'LaravelMPay24\LaravelMPay24ServiceProvider',
```

Give settings for the new service in `config/services.php`:
```php
    'mpay24' => [
        'merchantId' => 'XXXXXXX', //required
        'password' => 'XXXXXXXX', //required
        'test' => true, //optional
        'debug' => true, //optional
        'successUrl' => 'WelcomeController@anySuccess', //optional
        'errorUrl' => 'WelcomeController@anyError', //optional
        'confirmationUrl' => 'WelcomeController@anyConfirmation', //optional
    ],
```

## Usage

Now you have a service `app()->mpay24` which is object of `\LaravelMPay24\Shop` class.
This object initiates `MPay24Shop` class for you with settings given in the config.
To define logic of the shop you must create your own shop class extending `\LaravelMPay24\Models\AbstractShop`,
instantiate an object of that class and pass it to `app()->mpay24` service:
```php
    $shopDelegator = new BasicShop();
    app()->mpay24->setShopDelegator($shopDelegator);
```
Thus mpay24 will delegate all it's actions to your shop. `BasicShop` is an example implementation of the delegator shop, you can find it in the package.

`app()->mpay24` sets callback urls to the `ORDER` object using the service settings. But you are free to set it in your custom shop class,
`app()->mpay24` won't override these setting. 

## Working example

Example controller (`WelcomeController.php`):
```php
namespace App\Http\Controllers;

use LaravelMPay24\Models\BasicShop;
use LaravelMPay24\ORDER;
use LaravelMPay24\PaymentResponse;
use LaravelMPay24\Transaction;

class WelcomeController extends Controller {
    /**
     * Show the application welcome screen to the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        return view('welcome');
    }

    /**
     * Create a test transaction and redirect user to mpay24 page
     */
    public function postIndex()
    {
        $transaction = new Transaction('test transaction');
        $transaction->PRICE = 100.11;

        $order = new ORDER();
        $order->Order->Tid   = $transaction->TID;
        $order->Order->Price = $transaction->PRICE;

        $shopDelegator = new BasicShop();
        $shopDelegator->setTransaction($transaction);
        $shopDelegator->setOrder($order);

        /** @var \LaravelMPay24\Shop $mpay24 */
        $mpay24 = app()->mpay24;
        $mpay24->setShopDelegator($shopDelegator);
        /** @var PaymentResponse $result */
        $result = app()->mpay24->pay();

        if($result->getGeneralResponse()->getStatus() == 'OK') {
            $url = $result->getLocation();
            header('Location: '.$url);
        } else {
            echo "Return Code: " . $result->getGeneralResponse()->getReturnCode();
        }
    }

    public function anySuccess()
    {
        echo 'Success';
    }

    public function anyError()
    {
        echo 'Error';
    }

    public function anyConfirmation()
    {
        echo 'Confirmation';
    }
}
```

Code of `welcome.blade.php`:
```twig
<html>
 	<head>
 		<title>Payment Test</title>
 	</head>
 	<body>
         <form method="POST">
             <input type="hidden" name="_token" value="{{ csrf_token() }}">
             <input type="submit" value="Try it" />
         </form>
 	</body>
 </html>
```