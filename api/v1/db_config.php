<?
require '../../lib/vendor/autoload.php';

use Medoo\Medoo;

// Initialize
// TEST
// $database = new Medoo([
//     'database_type' => 'mysql',
//     'database_name' => 'smarthome_test',
//     'server' => 'iotdidsystem.cafe24.com',
//     'username' => 'testuser',
//     'password' => 'iotest2@',
//     'charset' => 'utf8'
// ]);

// PROD
$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'iotdidsystem',
    'server' => '101.101.165.130',
    'username' => 'root',
    'password' => 'iotdidsystem2@',
    'charset' => 'utf8'
]);
