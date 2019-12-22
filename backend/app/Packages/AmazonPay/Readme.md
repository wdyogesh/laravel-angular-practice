# Amazon Pay

Use ```Ctrl+Shift+V``` in VS Code to view this file properly.

## Usage

1. Put AmazonPay folder inside your app/Packages directory.
2. Add this piece of code in composer.json file

    ```php
    "require": {
        "tuurbo/amazon-payment": "^1.5"
    }
    ```

3. Run composer update command.
4. Add

    ```php
    use App\Packages\AmazonPay\AmazonPay;
    ```

    above line of code after your namespace

    ex :

    ```php
    <?php

    namespace App\Packages\AmazonPay;

    use App\Packages\AmazonPay\AmazonPay;

    ......
    ```

5. Add piece of code in services.php available on app/config/services.php

    ```php
    'amazonpayment' => [
        'sandbox_mode' => true,
        'store_name' => 'Your store name',
        'statement_name' => 'Your statement name',
        'client_id' => 'Your client ID provided by amazon seller account',
        'seller_id' => 'Your seller ID provided by amazon seller account',
        'access_key' => 'Your access key provided by amazon seller account',
        'secret_key' => 'Your secret key provided by amazon seller account',
    ]
    ```

6. Now access your functions you want to use

    Ex.

    ```php
    $your_variable_name = AmazonPay::<Your_function_name>
    ```

    ```$your_variable_name``` will contain response returned from that funvtion call.

    ```<Your_function_name>``` will require some parameter, you can refer AmazonPay class inside app/Packages/AmazonPay/ for detail about parameters.

7. All done!
