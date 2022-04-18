<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado

session_start();

if ( !isset($_REQUEST['login']) OR $_REQUEST['login'] != "AcessoTamaraFreitas" ){
    die('Acesso Restrito');
}

require_once('./config/config.php');

$conexoes = 0;
function setConnection(){

    try {
        $connection = new \PDO("mysql:host=".SERVER.";dbname=".DBNAME,USER,PASSWORD);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

    catch(PDOException $e)
        {
        die("ERROR 001 - La conexión con el banco de datos ha fallado: " . $e->getMessage());
        }

    global $conexoes;
    $conexoes += 1;
    return $connection;
}

/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////

$GlobalConnection = setConnection();

/////////////////////////////////////
/////////////////////////////////////
/////////////////////////////////////




$dbconnect = mysqli_connect(SERVER,USER,PASSWORD,DBNAME);





if ( isset($_SESSION['TABLE'])) {

    if (isset($_REQUEST['condicionBasica'])) {

        if ($_SESSION['TABLE'] != $_REQUEST['condicionBasica']) {
            $_SESSION['TABLE'] = $_REQUEST['condicionBasica'];
        }
    }

} else {
    $_SESSION['TABLE'] = 'nuevo';
}

if ( ! isset($_REQUEST['pesquisa']) ){
    $dbquery_consulta = "SELECT * FROM ".TABLE_RESERVAS." WHERE CondicionBasica = '".$_SESSION['TABLE']."' ORDER BY mesNumero, nombre, apellido, nif";
}else{

    $pesquisaDigitada = $_REQUEST['pesquisa'];
    $pesquisaReplace = str_replace(' ','%',$pesquisaDigitada);


    $dbquery_consulta = "SELECT * FROM ".TABLE_RESERVAS." WHERE CondicionBasica = '".$_SESSION['TABLE']."' AND (CONCAT_WS('', nombre, apellido, nif, email, telefono, dispManana, dispTarde, procedimiento, ano, mesNumero, mesNombre, CondicionBasica, CondicionEspecifica, precioReserva, precioProcedimiento, paymentStatus) like '%".$pesquisaReplace."%') ORDER BY mesNumero, nombre, apellido, nif";


    if ( strtolower(trim($_REQUEST['pesquisa'])) == "manana" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "mañana"
         ){
            $dbquery_consulta = "SELECT * FROM ".TABLE_RESERVAS." WHERE dispManana = 1 AND CondicionBasica = '".$_SESSION['TABLE']."' ORDER BY mesNumero, nombre, apellido, nif";
    }

   if ( strtolower(trim($_REQUEST['pesquisa'])) == "tarde" ){
      $dbquery_consulta = "SELECT * FROM ".TABLE_RESERVAS." WHERE dispTarde = 1 AND CondicionBasica = '".$_SESSION['TABLE']."' ORDER BY mesNumero, nombre, apellido, nif";
    }

    if ( strtolower(trim($_REQUEST['pesquisa'])) == "solo manana" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "solo mañana"
         ){
            $dbquery_consulta = "SELECT * FROM ".TABLE_RESERVAS." WHERE dispManana = 1 AND dispTarde = 0 AND CondicionBasica = '".$_SESSION['TABLE']."' ORDER BY mesNumero, nombre, apellido, nif";
    }

    if ( strtolower(trim($_REQUEST['pesquisa'])) == "solo tarde" ){
        $dbquery_consulta = "SELECT * FROM ".TABLE_RESERVAS." WHERE dispManana = 0 AND dispTarde = 1 AND CondicionBasica = '".$_SESSION['TABLE']."' ORDER BY mesNumero, nombre, apellido, nif";
    }

    if ( strtolower(trim($_REQUEST['pesquisa'])) == "manana o tarde" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "mañana o tarde" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "mañana y tarde" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "manana y tarde" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "mañana tarde" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "manana tarde"

         ){
            $dbquery_consulta = "SELECT * FROM ".TATABLE_RESERVASBLE." WHERE dispManana = 1 AND dispTarde = 0 AND CondicionBasica = '".$_SESSION['TABLE']."' ORDER BY mesNumero, nombre, apellido, nif ";
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
            $dbquery_consulta = "SELECT * FROM ".TABLE_RESERVAS." WHERE paymentStatus = '' AND CondicionBasica = '".$_SESSION['TABLE']."' ORDER BY mesNumero, nombre, apellido, nif";
    }

        if ( strtolower(trim($_REQUEST['pesquisa'])) == "pago" OR
         strtolower(trim($_REQUEST['pesquisa'])) == "pagos"
         ){
            $dbquery_consulta = "SELECT * FROM ".TABLE_RESERVAS." WHERE paymentStatus = 'succeeded' AND CondicionBasica = '".$_SESSION['TABLE']."' ORDER BY mesNumero, nombre, apellido, nif";
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
        $dbquery_consulta = "SELECT * FROM ".TABLE_RESERVAS." WHERE paymentStatus = 'refund' AND CondicionBasica = '".$_SESSION['TABLE']."' ORDER BY mesNumero, nombre, apellido, nif";
    }



}

$command_consulta = CollectDatas($dbquery_consulta, $GlobalConnection);
$RegistrosEncontrados = count($command_consulta);

function CollectDatas($sql, $connection){
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function TotalRegistros($connection){
    $stmt = $connection->prepare("SELECT COUNT(*) FROM ".TABLE_RESERVAS." WHERE CondicionBasica = '".$_SESSION['TABLE']."'");
    $stmt->execute();
    return $stmt->fetchColumn();
}

function RegistrosPagos($connection){
    $stmt = $connection->prepare("SELECT COUNT(*) FROM ".TABLE_RESERVAS." WHERE paymentStatus = 'succeeded' AND CondicionBasica = '".$_SESSION['TABLE']."'");
    $stmt->execute();
    return $stmt->fetchColumn();
}

function RegistrosNaoPagos($connection){
    $stmt = $connection->prepare("SELECT COUNT(*) FROM ".TABLE_RESERVAS." WHERE paymentStatus <> 'succeeded' AND CondicionBasica = '".$_SESSION['TABLE']."'");
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

    <title>Consultar e Exportar Base de Dados (JULIO - AGOSTO - SEPTIEMBRE)</title>
</head>
<body>

<div class="container">
   <br/>
   <br/>
   <h2 align="center">Consultar e Exportar Base de Dados (JULIO-2022 EM DIANTE)</h2><br/>

    <div class="barra_infos_registros">
        <select name="table" id="selectedTable" class="selectedTable">
            <option value="nuevo" <?php if ( isset($_SESSION['TABLE']) AND $_SESSION['TABLE'] == "nuevo" ){echo('selected');}?>>Clientes Novas</option>
            <option value="repaso" <?php if ( isset($_SESSION['TABLE']) AND $_SESSION['TABLE'] == "repaso" ){echo('selected');}?>>Clientes Repaso</option>
        </select>
        <p style="margin-left: 10px;">TOTAL DE REGISTROS: <?php echo TotalRegistros($GlobalConnection); ?></p>
        <p style="margin-left: 10px;"><strong>REGISTROS PAGOS: <?php echo RegistrosPagos($GlobalConnection);?> </strong></p>
        <p style="margin-left: 10px;">REGISTROS NÃO PAGOS: <?php echo RegistrosNaoPagos($GlobalConnection); ?></p>
    </div>
    <div class="barra_forms">
        <div class="wrapper_form">

            <form class="form_pesquisa" method="GET" action="consulta.php">
                <input class="campo_pesquisa" id="campo_pesquisa" type="text" name="pesquisa"  placeholder="Pesquisar"
                <?php

                    if ( isset($pesquisaDigitada) AND $pesquisaDigitada != "" ){
                        echo('value="'.$pesquisaDigitada.'"');
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
                $i=0; foreach($command_consulta as $value)
                {
                    // MASCARAS
                    $manana = "";
                    $tarde = "";
                    if ( $command_consulta[$i]["dispManana"] == 1) {$manana = "MAÑANA";}
                    if ( $command_consulta[$i]["dispTarde"] == 1) {$tarde = "TARDE";}
                    if ( $command_consulta[$i]["paymentStatus"] == "succeeded") {$pago = "✅";}
                    if ( $command_consulta[$i]["paymentStatus"] == "") {$pago = "❌";}
                    if ( $command_consulta[$i]["paymentStatus"] == "refund") {$pago = "↩️";}

                    $periodo = $command_consulta[$i]["mesNombre"]." (".$command_consulta[$i]["ano"].")";

                    // MASCARAS

                    echo '
                        <div class="tr">
                            <div class="td id">'.$command_consulta[$i]["id"].'</div>
                            <div class="td nombre">'.$command_consulta[$i]["nombre"].'</div>
                            <div class="td apellido">'.$command_consulta[$i]["apellido"].'</div>
                            <div class="td nif">'.$command_consulta[$i]["nif"].'</div>
                            <div class="td email">'.$command_consulta[$i]["email"].'</div>
                            <div class="td tel">'.$command_consulta[$i]["telefono"].'</div>
                            <div class="td disponibilidade">'.$manana.' '.$tarde.'</div>
                            <div class="td procedimiento">'.$command_consulta[$i]["procedimiento"].'</div>
                            <div class="td periodo">'.$periodo.'</div>
                            <div class="td pago">'.$pago.'</div>
                            <div class="td created">'.$command_consulta[$i]["modified"].'</div>
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
                                    <div class="det nif_det">'.$command_consulta[$i]["nif"].'</div>
                                    <div class="det email_det">'.$command_consulta[$i]["email"].'</div>
                                    <div class="det tel_det">'.$command_consulta[$i]["telefono"].'</div>
                                    <div class="det disponibilidade_det">'.$manana.' '.$tarde.'</div>
                                    <div class="det procedimiento_det">'.$command_consulta[$i]["procedimiento"].'</div>
                                    <div class="det periodo_det">'.$periodo.'</div>
                                    <div class="det pago_det">'.$pago.'</div>
                                    <div class="det created_det">'.$command_consulta[$i]["modified"].'</div>
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
                    $i++;
                }
            ?>
        </div>
        <!-- <br/>
        <form method="post" action="export.php">
             <input type = "hidden" name="query" value="<?php echo($dbquery_consulta) ?>">
             <input class="btn_export" type="submit" name="export" value="EXPORTAR PESQUISA (.XLS)"/>
        </form>
        <br> -->
        <br>
   </div>

</div>


<script src="./js/consulta.js"></script>
</body>
</html>

