<?php
/**
 * Created by PhpStorm.
 * User: peterzhang
 * Date: 2020/7/10
 * Time: 5:11 PM
 */
define("ROOT", dirname(__DIR__));
// DEBUG should create dir use command sudo mkdir /var/log/wechaty && sudo chmod 777 /var/log/wechaty
define("DEBUG", 1);

function autoload($clazz) {
    $file = str_replace('\\', '/', $clazz);
    if(stripos($file, "PuppetHostie") > 0) {
        require ROOT . "/wechaty-puppet-hostie/$file.php";
    } elseif(stripos($file, "PuppetMock") > 0) {
        require ROOT . "/wechaty-puppet-mock/$file.php";
    } elseif(stripos($file, "Puppet") > 0) {
        require ROOT . "/wechaty-puppet/$file.php";
    } else {
        if(is_file(ROOT . "/wechaty/$file.php")) {
            require ROOT . "/wechaty/$file.php";
        }
    }
}

spl_autoload_register("autoload");

require ROOT . '/vendor/autoload.php';

// change dir
// \IO\Github\Wechaty\Util\Logger::$_LOGGER_DIR = "/tmp/";

$token = getenv("WECHATY_PUPPET_HOSTIE_TOKEN");
$endPoint = getenv("WECHATY_PUPPET_HOSTIE_ENDPOINT");
$wechaty = \IO\Github\Wechaty\Wechaty::getInstance($token, $endPoint);
$wechaty->onScan(function($qrcode, $status, $data) {
    //{"qrcode":"http://weixin.qq.com/x/IcPycVXZP4RV8WZ9MXF-","status":2}
    //[0] => PuppetHostie 22 payload {"qrcode":"","status":3}
    if($status == 3) {
        echo "SCAN_STATUS_SCANNED\n";
    } else {
        $qr = \IO\Github\Wechaty\Util\QrcodeUtils::getQr($qrcode);
        echo "$qr\n\nOnline Image: https://wechaty.github.io/qrcode/$qrcode\n";
    }
})->onLogin(function($user) {
    print_r($user);
})->onMessage(function(\IO\Github\Wechaty\User\Message $message) {
    print_r($message);
    $message->say("hello from PHP");
})->onHeartBeat(function($data) use ($wechaty) {
    // {"data":"heartbeat@browserbridge ding","timeout":60000}
    echo $data . "\n";
    // $wechaty->stop();
})->start();