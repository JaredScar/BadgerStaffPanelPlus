<?php
header('Content-Type: application/json');

function concatErrors() {
    $combinedArray = array();

    foreach($arrays as $index => $array) {
        $combinedArray['error' . ($index+1)] = $array;
    }

    return $combinedArray;
}

function validateThenGenerate($appname, $appenv, $appkey, $appdebug, $appurl, $masterapikey, $usecaptcha, $googlecaptchakey, $googlecaptchasecret, $usediscord, $discordusecase, $masteradmindiscordid, $masteradminroleidm, $discordredirecturi, $discordredirectauth, $publicbans, $dbconnection, $dbhost, $dbport, $dbusername, $dbpassword, $logchannel, $logdeprecationschannel, $loglevel, $bccrypt, $broadcastdriver, $cachedriver, $filesystemdisk, $queueconnection, $sessiondriver, $sessionlifetime, $memcachedhost, $redishost, $redispassword, $redisport, $mailmailer, $mailhost, $mailport, $mailusername, $mailpassword, $mailencryption) 
{
    //app_name
    if(!ctype_alpha($appname)) {
        $array1 = array('app_name' => array('status' => 'error', 'message' => 'Invalid Application Name. Please revise your selection to include characters A-Z, 0-9', 'errorid' => '1'));
    }

    //app_env
    if (strlen($appenv) < 2 || strlen($appenv) == 0) {
        $array2 = array('app_env' => array('status' => 'error', 'message' => 'Invalid Application Enviornment. Please revise your selection to include more than 2 characters.', 'errorid' => '2'));
    }

    //app_debug
    if ($appdebug !== 'true' && $appdebug !== 'false') {
        $array3 = array('app_debug' => array('status' => 'error', 'message' => 'Invalid Debug value, this must be true or false. Please revise your selection to being either enabled or disabled (true or false).'));
    }

    //app_key
    if (strlen($appkey) < 1) {
        $array = array('app_key' => array('status' => 'error', 'message' => 'Invalid Application Key. It is highly recommended this to be left its default value.'));
    }

    //app_
}
?>