# EnvUpdater Package

Use ```Ctrl+Shift+V``` in VS Code to view this file properly.

## Usage

1. Place in App\Packages folder

2. Import into the controller where you want to use it

    ```php
    use App\Packages\EnvUpdater\EnvUpdater;
    ```

3. To add or update a value in env, call 

    ```php
    EnvUpdater::setEnvironmentValue($envKey, $envValue);
    ```

4. To delete a value in env, call

    ```php
    EnvUpdater::delEnvironmentValue($envkey);
    ```
