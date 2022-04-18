<?php

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

// DADOS DO DATABASE

    // define('SERVER','127.0.0.1:3306'); //hostinger
    // define('SERVER','sql262.main-hosting.eu'); // hostinger remoto
    // define('PASSWORD','2mJ/xAm2oKJ'); //hostinger

    define('SERVER','localhost:3306'); //evidalia e local
    define('PASSWORD','2mJ/xAm2oKJ@!');  //evidalia e local

    define('DBNAME','u837310599_TamaraReservas');
    define('USER','u837310599_admReservas');

    define('TABLE_REGISTRATIONS','Reservas');
    define('TABLE_PROCEDURES','Procedimientos');
    define('TABLE_PLAZAS','Plazas');
    define('TABLE_RESERVAS','Reservas');


//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

//// DEFININDO VARIAVEIS GLOBAIS


$ALL_PROCEDURES = [

    'nuevo' => [
        'Profesional'         => 'Tamara',
        'CondicionBasica'     => 'nuevo',
        'CondicionEspecifica' => 'nuevo',
    ],

    'repaso' => [
        'Profesional'         => 'Tamara',
        'CondicionBasica'     => 'repaso',
        'CondicionEspecifica' => 'repaso',
    ],

    'repasoHasta12meses' => [
        'Profesional'         => 'Tamara',
        'CondicionBasica'     => 'repaso',
        'CondicionEspecifica' => 'repasoHasta12meses',
    ],

    'repasoHasta24meses' => [
        'Profesional'         => 'Tamara',
        'CondicionBasica'     => 'repaso',
        'CondicionEspecifica' => 'repasoHasta24meses',
    ],

    'repasoMasDe24meses' => [
        'Profesional'         => 'Tamara',
        'CondicionBasica'     => 'repaso',
        'CondicionEspecifica' => 'repasoMasDe24meses',
    ],

];

$CondicionActual = 'repaso';
$PROCEDURE_NAME = "MicroCejas";
$PROCEDURE_DATAS  = $ALL_PROCEDURES[$CondicionActual];

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
