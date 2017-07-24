<?php

use Model\ShtrafovnetClient\ShtrafovnetClient;
use Symfony\Component\Yaml\Yaml;

date_default_timezone_set('Europe/Moscow');

try {
    require __DIR__.'/vendor/autoload.php';
    require __DIR__.'/bootstrap.php';

    $params = Yaml::parse(file_get_contents('app/config/parameters.yml'));

    $apiClient = new ShtrafovnetClient($params);

//    list($headers, $body) = $apiClient->createAccount(
//        'sinyukov.ivan+client4@gmail.com',
//        'fuck2music',
//        'Sinyukov Client',
//        'Sinyukov Ivan Sergeevich',
//        '+79507756028',
//        '1234567890', [
//            'companyName' => 'Sinyukov Client Fullname'
//        ]
//    );

//    list($headers, $body) = $apiClient->updateAccount([
//        'plainPassword'      => 'fuck2music',
//        'notificationFormat' => 'csv',
//        'url'                => 'https://google.com',
//    ]);

//    list($headers, $body) = $apiClient->getAccount();

//    list($headers, $body) = $apiClient->resetPasswordAccount();

//    list($headers, $body) = $apiClient->createToken();

//    list($headers, $body) = $apiClient->getTariffs();

//    list($headers, $body) = $apiClient->createCar([
//        'name' => 'Ford Focus 2',
//        'cert' => '36 20 848239',
//        'reg' => 'р 668 xo 36 ',
//    ]);

//    list($headers, $body) = $apiClient->getCar(1003708);

//    list($headers, $body) = $apiClient->getCars();

//    list($headers, $body) = $apiClient->updateCar(1003163, [
//        'name' => 'Ford Focus 2 (Xzibit Edition)'
//    ]);

//      list($headers, $body) = $apiClient->deleteCar(1003163);

    list($headers, $body) = $apiClient->getFines([
//        'cars' => [1003726],
//        'org' => 'gibdd',
//        'filters' => ['discount']
//'started' => '2017-06-10',
//'ended'   => '2017-06-12',
    ]);
//
//    list($headers, $body) = $apiClient->getFine(2952289);

//    list($headers, $body) = $apiClient->getServices();

//    list($headers, $body) = $apiClient->getService(317);

//    list($headers, $body) = $apiClient->createService(7, 12);

//    list($headers, $body) = $apiClient->getInvoices();

//    list($headers, $body) = $apiClient->getInvoice(610);

//    list($headers, $body) = $apiClient->createRegistry([
//        'uins' => [
//            '18810136161108008161',
//            '0355431010117031600011275',
//        ],
//    ]);

//    list($headers, $body) = $apiClient->getRegistries();
//    list($headers, $body) = $apiClient->getRegistry(209);
//    list($headers, $body) = $apiClient->deleteRegistry(208);

//    list($headers, $body) = $apiClient->billRegistry(209);

    displayResponse($headers, $body);
} catch (\Exception $e) {
    displayError($e);
}