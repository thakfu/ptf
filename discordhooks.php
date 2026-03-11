<?php

    $abbrev = '&1148819266355875870';
    $message = '<@' . $abbrev . '> is on the clock!';

    $url = 'https://discord.com/api/webhooks/1150508324316516483/JSAfb8L9iYGpa9Jx-jsFmqLpwCkClKRZ6cTEyQJb6sRQqbK2cm-jmxSFrMcKGLDZJCSt';
    $headers = [ 'Content-Type: application/json; charset=utf-8' ];
    $POST = [ 'username' => 'League Offices', 'content' => $message ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
    $response   = curl_exec($ch);


?>