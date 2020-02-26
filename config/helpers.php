<?php

use App\Components\SessionHelpers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;


define('APP_URL', 'http://slim.by/');
define('IMAGE_PATH', __DIR__ . '/../public/img/');
define('IMAGE_DIR', __DIR__ . '/../public/img');

define('HOST', 'smtp.mail.ru');
define('USER_NAME_EMAIL', 'pavelmailer');
define('MAIL_PASSWORD', '340402698A');
define('SMTP_SECURE', 'SSL');
define('PORT_MAIL', '465');

function getMailConfig()
{
    return [
        'HOST' => HOST,
        'UserName' => USER_NAME_EMAIL,
        'password' => MAIL_PASSWORD,
        'SMTPsecure' => SMTP_SECURE,
        'Port' => PORT_MAIL
    ];
}

function dd($arr)
{
    echo '<pre>';
    echo print_r($arr, TRUE);
    echo '</pre>';
    die;
}

function asset($url)
{
    return APP_URL . $url;
}

function imageExist($filename)
{

    $imageUrl = APP_URL . 'img/';

    if (file_exists(IMAGE_PATH . $filename)) {
        $image = $imageUrl . $filename;
    } else {
        $image = $imageUrl . 'NOTexist.png';
    }

    return $image;
}

function imgParsed($file)
{


}

function moveUploadedFile($directory, UploadedFile $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

/**
 * @param $file UploadedFile
 * @param $field string
 * @return false|string
 */
function uploadRequestFile($file, $field = 'image')
{
    $filename = false;
    if (!empty($file[$field])) {
        $uploadedFile = $file[$field];

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = moveUploadedFile(IMAGE_DIR, $uploadedFile);
        }
    }

    return $filename;
}

function route($url)
{
    return APP_URL . $url;
}

function validateEmptyData($data, $requiredFields)
{
    $errors = [];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            $errors[] = $field;
        }
    }
    return $errors;
}

function old($data, $field)
{

    return !empty($data[$field]) ? $data[$field] : '';
}

function switchCurrency($currency, $value)
{
    sessionHelpers::addByKey('currency', ['name' => $currency, 'value' => $value]);
}

function getCurrencies()
{
    if (empty($_SESSION['currency'])) {
        $_SESSION['currency'] = ['name' => 'BYN', 'value' => 1];
    }
    return $_SESSION['currency']['name'];
}

function priceAfterCurrencyChange($price)
{
    return round($price / $_SESSION['currency']['value']);
}

function getEmailMessageOrder($orderInfo)
{

    $mail = "$orderInfo->name!Ваш заказ сформирован! <br>" .
        "Номер заказа: $orderInfo->order_number <br>" .
        "Общая цена заказа: $orderInfo->total_price<br>" .
        "Адрес доставки: $orderInfo->address<br>" .
        "Подробную информацию о заказе можете посмореть здесь: <a href='http://slim.by/order/$orderInfo->order_number '> Здесь </a> ";

    return $mail;

}

//[
// 'ssl' =>
//]

