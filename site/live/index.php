<?php
// URL real do streaming
$streamUrl = 'https://radio.saopaulo01.com.br:10996/;';

// Detecta se é navegador (User-Agent de navegadores comuns)
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$isBrowser = preg_match('/Mozilla|Chrome|Safari|Edge|Firefox/i', $userAgent);

// Se for navegador, exibe um player simples
if ($isBrowser) {
    echo "<!DOCTYPE html>
    <html lang='pt-br'>
    <head><meta charset='UTF-8'><title>RITUAL FM - LIVE</title></head>
    <body style='background:#000;position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);'>
        <audio controls autoplay>
            <source src='$streamUrl' type='audio/mpeg'>
            Seu navegador não suporta áudio.
        </audio>
    </body>
    </html>";
    exit;
}

// Se for player externo, redireciona direto
header("Location: $streamUrl");
exit;