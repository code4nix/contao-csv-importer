:: Run easy-coding-standard (ecs) via this batch file inside your IDE e.g. PhpStorm (Windows only)
:: Install inside PhpStorm the  "Batch Script Support" plugin
cd..
cd..
cd..
cd..
cd..
cd..
php vendor\bin\ecs check vendor/code4nix/contao-csv-importer/src --fix --config vendor/code4nix/contao-csv-importer/tools/ecs/config.php
php vendor\bin\ecs check vendor/code4nix/contao-csv-importer/contao --fix --config vendor/code4nix/contao-csv-importer/tools/ecs/config.php
php vendor\bin\ecs check vendor/code4nix/contao-csv-importer/config --fix --config vendor/code4nix/contao-csv-importer/tools/ecs/config.php
php vendor\bin\ecs check vendor/code4nix/contao-csv-importer/templates --fix --config vendor/code4nix/contao-csv-importer/tools/ecs/config.php
php vendor\bin\ecs check vendor/code4nix/contao-csv-importer/tests --fix --config vendor/code4nix/contao-csv-importer/tools/ecs/config.php
