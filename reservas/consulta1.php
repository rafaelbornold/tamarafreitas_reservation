<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado

session_start();

if ( !isset($_REQUEST['login']) OR $_REQUEST['login'] != "AcessoTamaraFreitas" ){
    die('Acesso Restrito');
}

// DADOS DO DATABASE

require_once('./config/config.php');

function getDB($table){

    switch ($table){

        case 'nuevo':
            $table = '01_ReservasNuevas_AbrilMayoJunio_2022';
            break;

        case 'repaso':
            $table = '02_ReservasRepaso_MayoJunioJulio_2022';
            break;
    }

    return $table;
}


/////////////////////////////////


$dbconnect = mysqli_connect(SERVER,USER,PASSWORD,DBNAME);

// die($_REQUEST['pesquisa']);


if ( isset($_SESSION['TABLE'])) {

    if (isset($_REQUEST['table'])) {

        if ($_SESSION['TABLE'] != $_REQUEST['table']) {
            $_SESSION['TABLE'] = $_REQUEST['table'];
        }
    }

} else {
    $_SESSION['TABLE'] = 'nuevo';
}

define('TABLE', getDB($_SESSION['TABLE']));

if ( ! isset($_REQUEST['pesquisa']) ){
    $dbquery_consulta = "SELECT * FROM ".TABLE." ORDER BY periodo, nombre, apellido, nif";
}else{

    $pesquisaDigitada = $_REQUEST['pesquisa'];

    $dbquery_consulta = "SELECT * FROM ".TABLE." WHERE CONCAT_WS('', nombre, apellido, nif, email, telefono, dispManana, dispTarde, procedimiento, periodo, precioReserva, pago) like '%".$_REQUEST['pesquisa']."%' ORDER BY periodo, nombre, apellido, nif";

    if ( strtolower(trim($_REQUEST['pesquisa'])) == "manana" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "mañana"
         ){
            $dbquery_consulta = "SELECT * FROM ".TABLE." WHERE dispManana='SI' ORDER BY periodo, nombre, apellido, nif";
    }

   if ( strtolower(trim($_REQUEST['pesquisa'])) == "tarde" ){
      $dbquery_consulta = "SELECT * FROM ".TABLE." WHERE dispTarde='SI' ORDER BY periodo, nombre, apellido, nif";
    }

    if ( strtolower(trim($_REQUEST['pesquisa'])) == "solo manana" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "solo mañana"
         ){
            $dbquery_consulta = "SELECT * FROM ".TABLE." WHERE dispManana='SI' AND dispTarde='NO' ORDER BY periodo, nombre, apellido, nif";
    }

    if ( strtolower(trim($_REQUEST['pesquisa'])) == "solo tarde" ){
        $dbquery_consulta = "SELECT * FROM ".TABLE." WHERE dispManana='NO' AND dispTarde='SI' ORDER BY periodo, nombre, apellido, nif";
    }

    if ( strtolower(trim($_REQUEST['pesquisa'])) == "manana o tarde" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "mañana o tarde" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "mañana y tarde" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "manana y tarde" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "mañana tarde" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "manana tarde"

         ){
            $dbquery_consulta = "SELECT * FROM ".TABLE." WHERE dispManana='SI' AND dispTarde='SI' ORDER BY periodo, nombre, apellido, nif";
    }

    if ( strtolower(trim($_REQUEST['pesquisa'])) == "nao pago" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "não pago" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "nao pagos" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "não pagos" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "no pago" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "no pagos" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "no pagados" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "no pagado" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "pago não confirmado" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "pago nao confirmado" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "pago não confirmados" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "pago nao confirmados" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "pago no confirmado" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "pago no confirmados" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "não confirmado" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "nao confirmado" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "não confirmados" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "nao confirmados" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "no confirmados" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "no confirmado"
         ){
            $dbquery_consulta = "SELECT * FROM ".TABLE." WHERE pago='PENDIENTE' OR pago='PROCESSO INICIADO' ORDER BY periodo, nombre, apellido, nif";
    }

    if ( strtolower(trim($_REQUEST['pesquisa'])) == "refund" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "refunded" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "devolvido" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "devolvidos" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "reembolsado" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "reembolsados" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "devuelto" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "devueltos" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "devolução" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "devolucao" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "devoluções" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "devolucoes" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "estorno" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "estornos" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "estornado" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "estornados" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "inversión" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "inversion" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "contracargo" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "contracargos" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "inversiones" OR
      strtolower(trim($_REQUEST['pesquisa'])) == "inversiónes"
      ){
      $dbquery_consulta = "SELECT * FROM ".TABLE." WHERE pago='refund' ORDER BY periodo, nombre, apellido, nif";
}


}
$command_consulta = mysqli_query($dbconnect,$dbquery_consulta);
$RegistrosEncontrados = mysqli_num_rows($command_consulta);

// $dbquery_consultaCountTUDO = "SELECT COUNT(*) FROM ".TABLE;
// $command_CountTUDO = mysqli_query($dbconnect,$dbquery_consultaCountTUDO);


function TotalRegistros(){
    $con = new \PDO("mysql:host=".SERVER.";dbname=".DBNAME,USER,PASSWORD);
    $stmt = $con->prepare("SELECT COUNT(*) FROM ".TABLE);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function RegistrosPagos(){
    $con = new \PDO("mysql:host=".SERVER.";dbname=".DBNAME,USER,PASSWORD);
    $stmt = $con->prepare("SELECT COUNT(*) FROM ".TABLE." WHERE pago = 'PAGO CONFIRMADO'");
    $stmt->execute();
    return $stmt->fetchColumn();
}

function RegistrosNaoPagos(){
    $con = new \PDO("mysql:host=".SERVER.";dbname=".DBNAME,USER,PASSWORD);
    $stmt = $con->prepare("SELECT COUNT(*) FROM ".TABLE." WHERE pago <> 'PAGO CONFIRMADO'");
    $stmt->execute();
    return $stmt->fetchColumn();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">

    <link rel="shortcut icon" href="./images/icontamara.ico"/>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link href="css/style_consultas.css?<?=time()?>" type="text/css" rel="stylesheet" />

    <title>Consultar e Exportar Base de Dados (ABRIL - MAYO - JUNIO - JULIO)</title>
</head>
<body>

<div class="container">
   <br/>
   <br/>
   <h2 align="center">Consultar e Exportar Base de Dados (ABRIL - MAYO - JUNIO - JULIO)</h2><br/>

    <div class="barra_infos_registros">
        <select name="table" id="selectedTable" class="selectedTable">
            <option value="nuevo" <?php if ( isset($_SESSION['TABLE']) AND $_SESSION['TABLE'] == "nuevo" ){echo('selected');}?>>Clientes Novas</option>
            <option value="repaso" <?php if ( isset($_SESSION['TABLE']) AND $_SESSION['TABLE'] == "repaso" ){echo('selected');}?>>Clientes Repaso</option>
        </select>
        <p style="margin-left: 10px;">TOTAL DE REGISTROS: <?php echo TotalRegistros(); ?></p>
        <p style="margin-left: 10px;"><strong>REGISTROS PAGOS: <?php echo RegistrosPagos();?> </strong></p>
        <p style="margin-left: 10px;">REGISTROS NÃO PAGOS: <?php echo RegistrosNaoPagos(); ?></p>
    </div>
    <div class="barra_forms">
        <div class="wrapper_form">

            <form class="form_pesquisa" method="GET" action="consulta1.php">
                <input class="campo_pesquisa" id="campo_pesquisa" type="text" name="pesquisa"  placeholder="Pesquisar"
                <?php
                    if ( isset($_REQUEST['pesquisa']) AND $_REQUEST['pesquisa'] != "" ){
                        echo('value='.$_REQUEST['pesquisa']);
                        }
                ?>
                        >
                <button class="btn_pesquisar" type="submit">PESQUISAR</button>
                <button class="btn_limpar" type="submit" onclick="clearSearch()">LIMPAR PESQUISA</button>
                <input type="hidden" name="login" value="AcessoTamaraFreitas">
            </form>

        </div>
    </div>
        <br>
        <div class="box_registros">
            <p class="registros_encontrados">
            <?php
            if ( isset($_REQUEST['pesquisa']) AND $_REQUEST['pesquisa'] != "" ){
                echo('Pesquisa: "'.$_REQUEST['pesquisa'].'" | ');
                }
                ?>
            ** Encontrados <?php echo($RegistrosEncontrados)  ?> Registros
            </p>
            <!-- <form method="post" action="export.php">
                <input type = "hidden" name="query" value="<?php echo($dbquery_consulta) ?>">
                <input class="btn_export" type="submit" name="export" value="EXPORTAR PESQUISA (.XLS)"/>
             </form> -->
        </div>
   <div class="tabela">
        <div class="tabela_conteudo">
            <div class="tr">
                <div class="th id">ID</div>
                <div class="th nombre">NOMBRE</div>
                <div class="th apellido">APELLIDO</div>
                <div class="th nif">NIF</div>
                <div class="th email">EMAIL</div>
                <div class="th tel">TEL</div>
                <div class="th disponibilidade">DISPONIBILIDAD</div>
                <div class="th procedimiento">PROCEDIMIENTO</div>
                <div class="th periodo">PERIODO</div>
                <div class="th pago">PAGO</div>
                <div class="th created">ALTERACIÓN</div>
            </div>
            <?php
                while($row = mysqli_fetch_array($command_consulta))
                {
                    // MASCARAS
                    $manana = "";
                    $tarde = "";
                    if ( $row["dispManana"] == "SI") {$manana = "MAÑANA";}
                    if ( $row["dispTarde"] == "SI") {$tarde = "TARDE";}
                    if ( $row["pago"] == "PAGO CONFIRMADO") {$pago = "✅";}
                    if ( $row["pago"] != "PAGO CONFIRMADO") {$pago = "❌";}
                    if ( $row["pago"] == "refund") {$pago = "↩️";}

                    preg_match_all('/\((.*?)\)/', $row["periodo"], $mesReduzido);
                    $periodo = $mesReduzido[1][0];

                    // MASCARAS

                    echo '
            <div class="tr">
                <div class="td id">'.$row["id"].'</div>
                <div class="td nombre">'.$row["nombre"].'</div>
                <div class="td apellido">'.$row["apellido"].'</div>
                <div class="td nif">'.$row["nif"].'</div>
                <div class="td email">'.$row["email"].'</div>
                <div class="td tel">'.$row["telefono"].'</div>
                <div class="td disponibilidade">'.$manana.' '.$tarde.'</div>
                <div class="td procedimiento">'.$row["procedimiento"].'</div>
                <div class="td periodo">'.$periodo.'</div>
                <div class="td pago">'.$pago.'</div>
                <div class="td created">'.$row["modified"].'</div>
            </div>
            <div class="tr_det">
                <div class="detalhes">
                    <div class="det_titulos">
                        <div class="det nif_det">NIF:</div>
                        <div class="det email_det">E-MAIL:</div>
                        <div class="det tel_det">TEL:</div>
                        <div class="det disponibilidade_det">DISPONIBILIDAD:</div>
                        <div class="det procedimiento_det">PROCEDIMIENTO:</div>
                        <div class="det periodo_det">PERÍODO:</div>
                        <div class="det pago_det">PAGO:</div>
                        <div class="det created_det">ULTIMA ALTERAÇÃO:</div>
                    </div>
                    <div class="det_infos">
                        <div class="det nif_det">'.$row["nif"].'</div>
                        <div class="det email_det">'.$row["email"].'</div>
                        <div class="det tel_det">'.$row["telefono"].'</div>
                        <div class="det disponibilidade_det">'.$manana.' '.$tarde.'</div>
                        <div class="det procedimiento_det">'.$row["procedimiento"].'</div>
                        <div class="det periodo_det">'.$periodo.'</div>
                        <div class="det pago_det">'.$pago.'</div>
                        <div class="det created_det">'.$row["modified"].'</div>
                    </div>
                </div>
                    <div class="det nif_det">.</div>
                    <div class="det email_det">.</div>
                    <div class="det tel_det">.</div>
                    <div class="det disponibilidade_det">.</div>
                    <div class="det procedimiento_det">.</div>
                    <div class="det periodo_det">.</div>
                    <div class="det pago_det">.</div>
                    <div class="det created_det">.</div>
            </div>

                        ';
                }
            ?>
        </div>
        <br/>
        <!-- <form method="post" action="export.php">
             <input type = "hidden" name="query" value="<?php echo($dbquery_consulta) ?>">
             <input class="btn_export" type="submit" name="export" value="EXPORTAR PESQUISA (.XLS)"/>
        </form> -->
        <br>
        <br>
   </div>
</div>


<script src="./js/consulta1.js"></script>
</body>
</html>
