# Logger Package

Use ```Ctrl+Shift+V``` in VS Code to view this file properly.

## Usage

1. Place this Logger folder inside directory app/Packages/
2. Open filesystem.php file inside config/ folder
3. Add below piece of code inside disk array

    ```php
    'log' => [
        'driver' => 'local',
        'root' => storage_path('logs/activity'),
        'url' => env('APP_URL').'storage/log/activity/',
    ],
    ```

4. Create a activity folder inside storage/logs/activity (not compulsory)
5. How to use this in your code ex:-
    - open any controller
    - add this snippet at the top below namespace

        ```php
        use App\Packages\Logger\Log;
        ```

    - add below snippet of code where you want to create log.

        ```php
        Log::write([
            'operation' => '<Your Log Title>', // like registration, login, change password etc
            'request_details' => $this->request->all(), // request parameters you are getting or you can leave it empty.
            'status' => 'success/error',
            'message' => '<your success/error message>'
            'ip_address' => '<IP address>',
        ]);
        ```

6. That's it, logger ready to use
