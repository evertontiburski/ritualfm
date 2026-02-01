<?php

session_start();
date_default_timezone_set('America/Sao_Paulo');

include './../app/configuracao.php';
include './../app/autoload.php';

$rotas = new Rotas();