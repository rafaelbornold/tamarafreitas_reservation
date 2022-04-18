<?php //backend inicial

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

session_start();

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

require_once("./config/config.php");
require_once("./classes/class_plazas.php");
require_once("./classes/class_procedure.php");

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

$dateNow = new \DateTime('now', new DateTimeZone('Europe/Madrid'));
$dateLimit = new \DateTime('Apr 12 2022 10:00:00', new DateTimeZone('Europe/Madrid'));

if ($dateNow < $dateLimit){

  if (isset($_REQUEST["permit"])){

    if ($_REQUEST["permit"] != "rafaelbornold"){

      header("Location: index.html");

    }

} else {
  header("Location: index.html");
}

}

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

if ( isset($_SESSION['reload']) ){

    If ($_SESSION['reload'] == 1 ){
        $_SESSION['reload'] = 0 ;
        header("Location: index.php");
    }

}

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

if (isset($_REQUEST["checkout"])){

    if ($_REQUEST["checkout"] === "Finished"
        && isset($_REQUEST["nif_session"])
        && isset($_REQUEST["payment_intent"]) ){

            // $_SESSION['reload'] = 1;

        }

}

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////



$plazas = new Plazas($PROCEDURE_DATAS["CondicionBasica"], 0, 0, 0);

$periodosDisponibles = $plazas->getTodosPeriodosDisponibles();


$DesableFields   = (count($periodosDisponibles) == 0) ? "disabled"          : "";

$numPlazas       = (count($periodosDisponibles) == 0) ? 0                   : $periodosDisponibles[0]["plazas"];
$mesActual       = (count($periodosDisponibles) == 0) ? ""                  : $periodosDisponibles[0]["mesNombre"];
$anoActual       = (count($periodosDisponibles) == 0) ? ""                  : $periodosDisponibles[0]["ano"];
$periodoActual   = (count($periodosDisponibles) == 0) ? "Plazas Agotadas !" : $mesActual . "-" . $anoActual;
$mesNumeroActual = (count($periodosDisponibles) == 0) ? ""                  : $periodosDisponibles[0]["mesNumero"];


//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

$procedure = new Procedure($PROCEDURE_NAME, $PROCEDURE_DATAS);
$precioReserva = $procedure->getReservationPrice();

$allProceduresPrices = $procedure->getAllProceduresPrices();


$i=0; foreach($allProceduresPrices as $key => $value){

    $proceduresResumedPrices[$allProceduresPrices[$i]['Procedimiento']][$allProceduresPrices[$i]['CondicionEspecifica']] = $allProceduresPrices[$i]['PrecioProcedimiento'];
    $i++;

}

// $dataProximaReserva = "XX/XX/22 las 10h00";
$dataProximaReserva = "";

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////




?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <meta name="author" content="Rafael Bornold">

    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">


    <link rel="shortcut icon" href="./images/logo_redondo_fundo_claro.png"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">

    <link href="./css/style_index.css?<?=time()?>" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/style_checkout.css?<?=time()?>" />

    <script src="https://js.stripe.com/v3/"></script>
    <script type="module" src="./stripe_checkout/js/checkout.js?<?=time()?>" defer></script>

    <title>Tamara Freitas Studio Academy</title>

</head>
<body>

<header>
    <div class="container header_display">
        <div class="logo_header"><div class="logo_img"></div></div>
        <div class="title_header">

            <?php // paragrafos em caso de clientes REPASO

                if ($PROCEDURE_DATAS["CondicionBasica"] == 'repaso'){
                    echo('

                    <h1>RESERVAS CLIENTAS REPASO</h1></div>

                    ');
                }
            ?>

            <?php // paragrafos em caso de clientes NUEVAS

                if ($PROCEDURE_DATAS["CondicionBasica"] == 'nuevo'){
                    echo('

                    <h1>RESERVAS NUEVAS CLIENTAS</h1></div>

                    ');
                }
            ?>

        <div class="contact_header">
            <div class="whats_img">
                <a href="https://api.whatsapp.com/send?phone=34662296124&text=Hola!%20Que%20tal?" target="_blank"><img src="images/iconmonstr-whatsapp-1.png"></a>
            </div>
        </div>
    </div>
</header>

<main>

<?php

    if ( count($periodosDisponibles) == 0){
    $message = "SE HAN AGOTADO LAS PLAZAS, PRONTO VOLVEREMOS A ABRIR NUEVAS FECHAS";
    echo("
    <div class='status container'>
        <div class='status_warning'>
            $message
        </div>
    </div>
    ");
    }
    ?>

    <div class="payment-message-header">
      <div id="payment-message" class='hidden'></div>
    </div>

    <header>

        <div class="container display">
            <section class="main1">
                <div class="main1_border"></div>
            </section>
            <section class="main2">
                <div class="main2_container">

                    <h3>DISPONIBLE EN <p id="clock">-</p> </h3>

                    <div class="main2_content">

                        <?php // paragrafos em caso de clientes REPASO

                            if ($PROCEDURE_DATAS["CondicionBasica"] == 'repaso'){
                                echo('

                                <p class="p1">RESERVA PARA REPASO DE CEJAS, LABIOS Y EYELINER</p>

                                ');
                            }
                        ?>

                        <?php // paragrafos em caso de clientes NUEVAS

                            if ($PROCEDURE_DATAS["CondicionBasica"] == 'nuevo'){
                                echo('

                                <p class="p1">MICRO DE CEJAS, LABIOS Y EYELINER</p>

                                ');
                            }
                        ?>

                        <div class="wrapper_main2_content">
                            <div>
                                <p><span><img class="img_icon" src="./images/icon_people.svg"></span><span class="p2">Con Tamara Freitas</span></p>

                                <p><span><img class="img_icon" src="./images/icon_calendar.svg"></span><span class="p2"><?php echo $periodoActual;?></span></p>
                            </div>
                            <div class="p3">
                                <div>
                                    <p style="margin: 0; padding: 0;"><span>Consultar los precios abajo</span></p>
                                    <p style="margin: 5px 0 0 0; padding: 0; text-align: left;"><span style="font-size: 17px; margin: 0; padding: 0;">RESERVA €<?php echo($precioReserva); ?>,00</span></p>
                                    <span style="font-size: 10px; margin-left: 0px; margin-top: 0px;"> por procedimiento</span>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </section>
           </div>
    </header>

    <article id="principal">
        <div class="container display1">
            <section>
                <div class="wrapper A1">

                        <?php

                            //paragrafo condicional a disponibilidade de vagas
                            if ($numPlazas > 0){
                                echo('

                                <p>
                                    <h4>Llegó el gran día !</h4>
                                </p>
                                <p>
                                    Queda abierta la reserva de plazas para '.
                                    ($CbClientas = $PROCEDURE_DATAS["CondicionBasica"] == 'nuevo' ? 'el procedimiento de Micropigmentación' : '<strong>REPASO</strong> de tu procedimiento de Micropigmentación')
                                    .' <strong>con Tamara Freitas.</strong>
                                </p>
                                '. ($dataProximaReserva != '' ?
                                '<p>
                                    Las reservas para '.
                                    ($CbClientas = $PROCEDURE_DATAS["CondicionBasica"] == 'nuevo' ? 'REPASO' : 'CLIENTES NUEVAS')
                                    .' deben ser realizadas el dia <strong>'.$dataProximaReserva.'</strong>.
                                </p>' : ''). '
                                <p>
                                    <h4>Aprovecha la oportunidad</h4>
                                </p>
                                <p>
                                    ¡Sólo quedan <strong> '.$numPlazas.' Plaza(s) </strong> disponibles para el mes de <strong>'.$periodoActual.'</strong> ! No dejes escapar la tuya!
                                </p>

                                ');
                            }

                        ?>

                        <?php // paragrafos em caso de clientes REPASO

                            if ($PROCEDURE_DATAS["CondicionBasica"] == 'repaso'){
                                echo('
                                <p>
                                    <h4>Información</h4>
                                </p>
                                <p>
                                    Sigue los pasos para hacer la reserva abonando los '.$precioReserva.',00 euros, en este momento la persona quedará inscrita en una lista, con su disponibilidad (mañana o tarde) en el mes correspondiente.
                                </p>
                                <p>
                                    En el día del tratamiento deberás abonar el resto del pago y <strong>el valor de la reserva será descontado del precio total. </strong>
                                </p>
                                <p style="padding:0 0 0px 5px; line-height: 16px;">
                                    ➡ <strong>REPASO HASTA 12 MESES – 50% de descuento en el precio actual</strong>
                                </p>
                                <p style="margin-left: 15px; margin-bottom:5px; line-height: 14px;">
                                    (€'.$proceduresResumedPrices['MicroCejas']['repasoHasta12meses'].',00 incl. IVA)
                                </p>
                                <p style="padding:0 0 0px 5px; line-height: 16px;">
                                    ➡ <strong>REPASO DE 13 A 24 MESES – 30% de descuento en el precio actual</strong>
                                </p>
                                <p style="margin-left: 15px; margin-bottom:5px; line-height: 14px;">
                                    (€'.$proceduresResumedPrices['MicroCejas']['repasoHasta24meses'].',00 incl. IVA)
                                </p>
                                <p style="padding:0 0 0px 5px; line-height: 16px;">
                                    ➡ <strong>REPASO MÁS DE 24 MESES - 15% de descuento en el precio actual</strong>
                                </p>
                                <p style="margin-left: 15px; margin-bottom:5px; line-height: 14px;">
                                    (€'.$proceduresResumedPrices['MicroCejas']['repasoMasDe24meses'].',00 incl. IVA)
                                </p>
                                <p>
                                    Debes contar los meses a partir de la fecha de tu último tratamiento.
                                </p>
                                <p>
                                    <strong>LOS PRECIOS DEL REPASO SON SOLO PARA CLIENTAS QUE REALIZARÓN SU PROCEDIMIENTO EN EL STUDIO TAMARA FREITAS</strong>, las clientas que hicieron la micro en otro lugar tienen que hacer la reserva para un tratamiento nuevo, cuya agenda será abierta el próximo '.$dataProximaReserva.'.
                                </p>
                                <p>
                                    <strong>🏅 Método exclusivo Tamara Freitas </strong>
                                </p>
                                <p>
                                    Aproximadamente un mes y medio antes te llamaremos para concretar la fecha y la hora de tu cita.
                                </p>
                                <p>
                                    Recibirás un correo de confirmación al email que hayas indicado.
                                </p>
                                <p>
                                    Debes hacer un registro individual por cada persona que quiera obtener su plaza.
                                </p>
                                <p class="uppercase">
                                    <strong>Leed bien las contraindicaciones y políticas de cancelación.</strong>
                                </p>

                                ');
                            }

                        ?>

                        <?php // paragrafos em caso de clientes NUEVAS

                            if ($PROCEDURE_DATAS["CondicionBasica"] == 'nuevo'){
                                echo('
                                <p>
                                    <h4>Información</h4>
                                </p>
                                <p>
                                    Sigue los pasos para hacer la reserva abonando los 50,00 euros, en este momento la persona quedará inscrita en una lista, con su disponibilidad (mañana o tarde) en el mes correspondiente.
                                </p>
                                <p>
                                    En el día del tratamiento deberás abonar el resto del pago y <strong>el valor de la reserva será descontado del precio total. </strong>
                                </p>
                                <p style="padding:0 0 0px 5px; line-height: 16px;">
                                    ➡ <strong>Micropigmentación de Cejas:</strong>
                                </p>
                                <p style="margin-left: 15px; margin-bottom:5px; line-height: 14px;">
                                    (€'.$proceduresResumedPrices['MicroCejas']['nuevo'].',00 incl. IVA)
                                </p>
                                <p style="padding:0 0 0px 5px; line-height: 16px;">
                                    ➡ <strong>Micropigmentación Eyeliner:</strong>
                                </p>
                                <p style="margin-left: 15px; margin-bottom:5px; line-height: 14px;">
                                    (€'.$proceduresResumedPrices['MicroEyeliner']['nuevo'].',00 incl. IVA)
                                </p>
                                <p style="padding:0 0 0px 5px; line-height: 16px;">
                                    ➡ <strong>Micropigmentación Labios (Acquarell lips):</strong>
                                </p>
                                <p style="margin-left: 15px; margin-bottom:5px; line-height: 14px;">
                                    (€'.$proceduresResumedPrices['MicroLabios']['nuevo'].',00 incl. IVA)
                                </p>
                                <p>
                                    <strong>🏅 Método exclusivo Tamara Freitas </strong>
                                </p>
                                <p>
                                    Aproximadamente un mes y medio antes te llamaremos para concretar la fecha y la hora de tu cita.
                                </p>
                                <p>
                                    Recibirás un correo de confirmación al email que hayas indicado.
                                </p>
                                <p>
                                    Debes hacer un registro individual por cada persona que quiera obtener su plaza.
                                </p>
                                <p class="uppercase">
                                    <strong>Leed bien las contraindicaciones y políticas de cancelación.</strong>
                                </p>

                                ');
                            }

                        ?>

                        <p>
                            <h4 class="uppercase"><strong>La reserva sólo será efectiva una vez se haya confirmado el pago. </strong></h4>
                        </p>
                        <p>
                            <h4>CONTRAINDICACIONES</h4>
                        </p>
                        <div class="uppercase">
                            <p>
                            ⚠️ Si tienes un procedimiento ANTERIOR / ANTIGUO es necesario enviar una foto o reservar cita para evaluación <strong>ANTES DE REALIZAR NINGUNA RESERVA</strong> y con antelación. Sólo se puede hacer la micro si el pigmento está muy muy claro, prácticamente transparente.
                            </p>
                            <p>
                            ✅ <strong>TEMPORALES:</strong> EMBARAZO, HERPES SIMPLE O ZOSTER, CONJUNTIVITIS, DEBILIDAD INMUNOLÓGICA, INFILTRACIONES MEDICO ESTÉTICAS RECIENTES, INTERVENCIONES QUIRÚRGICAS ESTÉTICAS RECIENTES, QUIMIOTERAPIA O RADIOTERAPIA, INFECCIÓN LOCAL, CICATRICES NO ESTABILIZADAS, AFECCIONES DE LA PIEL EN LA ZONA DE APLICACIÓN (DERMATITIS LOCAL, HEMATOMA, QUEMADURAS SOLARES, ÚLCERAS DE PIEL) E INFECCIONES BACTERIANAS, FÚNGICAS O VÍRICAS.
                            </p>
                            <p>
                            ✅ <strong>TOTALES:</strong> NO PODRÁ REALIZAR EL PROCEDIMIENTO QUIEN TENGA CUALQUIERA DE ESTAS AFECCIONES: HEPATITIS, VIH, REACCIONES ALÉRGICAS A LOS PIGMENTOS, AFECCIONES DE LA PIEL EN LA ZONA DE APLICACIÓN (QUELOIDES, ANGIOMAS ABULTATOS, MELANOMAS, IMPÉTIGO, PSORIASIS, URTICARIA, CLOASMA, NEVO O NEVUS, Y CÁNCER DE PIEL, EN CASO DE PECAS, LUNARES Y VERRUGAS SE PUEDE HACER PERO NO SOBRE ELLAS).
                            </p>
                            <p>
                            ✅ <strong>BAJO SUPERVISIÓN MÉDICA:</strong> LUPUS, DIABETES, HEMOFILIA, ALTERACIONES DE LA PIEL O LESIONES CUTÁNEAS NO DIAGNOSTICADAS EN LA ZONA DE APLICACIÓN Y OTRAS PATOLOGÍAS CRÓNICAS. <br>EN ESTE CASO SERÁ SOLICITADO EN EL DÍA DE LA CITA UNA AUTORIZACIÓN MÉDICA.
                            </p>

                            <p>
                                <h4>Política de Cancelación</h4>
                            </p>
                            <p>
                            ⁃ Las cancelaciones deben ser avisadas con <strong>48 HORAS</strong> de antelación y de ese modo el importe sera reembolsado íntegramente, o la clienta puede reservar una nueva cita conforme disponibilidad de agenda si no puede asistir por algún motivo y no quiere cancelar su procedimiento.
                            </p>
                            <p>
                            Para la no asistencia o cancelación de cita <strong>con menos de 48h</strong> de antelación el importe <strong>NO SERÁ REEMBOLSADO</strong>.
                            </p>
                            <p>
                            ⁃ El importe pagado es transferible a otra persona con aviso previo.
                            </p>
                            <p>
                            ⁃ Una vez realizado el pago de la reserva de cita, <strong>el importe restante deberá ser abonado en el día del procedimiento, en efectivo o tarjeta.</strong>
                            </p>
                        </div>
                        <div class="plazas">
                            <p>
                            <h4>FORMULARIO DE INSCRIPCIÓN</h4>
                            </p>
                            <h5>PLAZAS DISPONIBLES:
                                <span>
                                    <?php $infoPlazasDisponibles = $numPlazas == 0 ? $periodoActual : $numPlazas." plaza(s) para ".$periodoActual;
                                     echo($infoPlazasDisponibles); ?>
                                </span></h5>
                        </div>

                        <div id="forms">

                        <div class="payment-message-form">
                          <div id="payment-message" class='hidden'></div>
                        </div>

                            <div class="wrapper_form">
                                <form method="POST" action="javascript:void(0);" id="register-form" class="form_up">

                                    <div class="form_left" id="form_left">
                                        <input type="text" id="nombre" name="nombre" required placeholder="Nombre"<?php echo $DesableFields ?>>
                                        <input type="text" id="apellido" name="apellido" required placeholder="Apellido"<?php echo $DesableFields ?>>
                                        <input type="text" id="nif" name="nif" required placeholder="DNI / NIE"<?php echo $DesableFields ?>>
                                        <input type="email" id="email" name="email" required placeholder="E-Mail"<?php echo $DesableFields ?>>
                                        <input type="tel" id="telefono" name="telefono" onblur="TelAdjust()" required placeholder="Teléfono"<?php echo $DesableFields ?>>
                                    </div>
                                    <div class="form_right" id="form_right">

                                        <div class="box_checkboxes<?php echo $DesableFields ?>"  id="divDisponibilidad">
                                            <span class="box_checkboxes_label">Tengo disponibilidad por la:</span>
                                            <label class="wrapper_checkbox">Mañana
                                                <input type="checkbox" id="dispManana" name="dispManana" value="SI" <?php echo $DesableFields ?>>
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="wrapper_checkbox">Tarde
                                                <input type="checkbox" id="dispTarde" name="dispTarde" value="SI" <?php echo $DesableFields ?>>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>

                                        <div class="box_checkboxes<?php echo $DesableFields ?>"   id="divProcedimientos">
                                            <span class="box_checkboxes_label">Quiero reservar la micro para:</span>
                                            <label class="wrapper_checkbox">Cejas
                                                <input type="checkbox" id="ProcCejas" name="ProcCejas" value="CEJAS" <?php echo $DesableFields ?>>
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="wrapper_checkbox">Labios
                                                <input type="checkbox" id="ProcLabios" name="ProcLabios" value="LABIOS" <?php echo $DesableFields ?>>
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="wrapper_checkbox">Eyeliner
                                                <input type="checkbox" id="ProcEyeliner"  name="ProcEyeliner" value="EYELINER" <?php echo $DesableFields ?>>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <input type="text" id="periodo" name="periodo" required value="<?php echo $periodoActual ?>" readonly <?php echo $DesableFields ?>>
                                        <input type="text" id="InformativoValor" name="InformativoValor" required placeholder="Elija los procedimientos para calcular el valor" readonly <?php echo $DesableFields ?>>
                                        <div class="form-submit">

                                            <input type="hidden" id="operation" name="operation" value="create">
                                            <input type="hidden" id="valorReserva" name="valorReserva" value="<?php echo($precioReserva) ?>">
                                            <input type="hidden" id="condicionBasica" name="condicionBasica" value="<?php echo($PROCEDURE_DATAS["CondicionBasica"]) ?>">
                                            <input type="hidden" id="condicionEspecifica" name="condicionEspecifica" value="<?php echo($PROCEDURE_DATAS["CondicionEspecifica"]) ?>">
                                            <input type="hidden" id="periodo_ano" name="periodo_ano" value="<?php echo($anoActual) ?>">
                                            <input type="hidden" id="periodo_mesNumero" name="periodo_mesNumero" value="<?php echo($mesNumeroActual) ?>">
                                            <input type="hidden" id="periodo_mesNombre" name="periodo_mesNombre" value="<?php echo($mesActual) ?>">


                                            <input type="hidden" id="valorTotal" name="valorTotal" value="">

                                            <input type="submit" id="ConfirmButtom" class="ConfirmButtom" name="Confirmar" value="Confirmar y pasar al Pago" <?php echo $DesableFields ?>>
                                        </div>
                                    </div>
                                </form>
                                <form method="POST" action="javascript:void(0);" class="form_down" id="payment-form" >
                                        <div class = "container_form">

                                            <div id="payment_infos">
                                                <p id="registerName"></p>
                                                <p id="email_payment"></p>
                                                <p id="reservation_amount"></p>
                                                <p id="procedures"></p>
                                                <p id="reservationPeriod"></p>
                                            </div>

                                            <div id="payment-element">
                                                <div style="margin: 10px 0 10px 0;">Cargando el formulario de pago</div>
                                                <div class="spinnerElement"></div>
                                            </div>

                                            <button class="submitButton" id="submit" disabled>
                                                <div class="spinner hidden" id="spinner"></div>
                                                <span id="button-text">PAGAR</span>
                                            </button>
                                            <br />
                                        </div>
                                </form>
                            </div>

                        </div>

                </div>
            </section>
            <section>
                <div class="wrapper A2"> <img class="bg_img" src="./images/main3_bg.png"> </div>
            </section>
        </div>
     </div>
    </article>

    <article id="video">
        <div class="container display2">
            <section>
                <div class="wrapper A3">
                    <div class="video_wrap_border">
                        <div class="video_wrap">
                            <video id="video_main" playsinline autoplay muted loop disablePictureInPicture controlsList="nodownload" poster="./videos/video_preload.jpg" preload="none">
                                <source src="./videos/Tamara_videoInicial_01.mp4" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <img id="video_cover" src="./videos/video_preload.jpg">
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </article>

</main>

<footer>
    <section class="footer1">
        <div class="container">
            <div class="footer1_content">
                <div class="fc1"><p>SÍGUENOS EN LAS REDES SOCIALES</p></div>
                <div class="fc2">
                    <a href="https://www.facebook.com/TamaraFreitasMakeup/" target="_blank"><img src="./images/facebook_color.svg"></a>
                </div>
                <div class="fc3">
                    <a href="http://www.google.com" target="_blank"><img src="./images/twitter_color.svg"></a>
                </div>
                <div class="fc4">
                    <a href="https://www.instagram.com/tamarafreitas.micro/" target="_blank"><img src="./images/instagram_color.svg"></a>
                </div>
            </div>
        </div>
    </section>
    <section class="footer2">
        <div class="container">
            <div class="footer2_content_left">
                <div class="fcl1">TAMARA FREITAS</div>
                <div class="fcl2"></div>
                <div class="fcl4">
                    <a href="https://www.instagram.com/tamarafreitas.micro/" target="_blank"><img src="./images/instagram_mono.svg"></a>
                </div>
                <div class="fcl5">
                    <a href="mailto:contacto@tamarafreitas.com"><img src="./images/email_mono.svg"></a>
                </div>
                <div class="fcl6">
                    <a href="https://api.whatsapp.com/send?phone=34662296124&text=Hola!%20Que%20tal?" target="_blank"><img src="./images/whatsapp_mono.svg"></a>

                </div>

            </div>
            <div class="footer2_content_right">
                    <div class="fcr1"><p>+34 662 29 61 24</p></div>
                    <div class="fcr2"></div>
                    <div class="fcr3"></div>
                    <div class="fcr4"></div>
                    <div class="fcr5"></div>
                    <div class="fcr6"></div>
            </div>
        </div>
    </section>
    <section class="footer3">
        <div class="container">
            <div class="footer3_content">
                <p>© 2021 Tamara Freitas Studio Academy.</p>
                <p>&nbsp Reservados todos los derechos.</p>
            </div>
        </div>
    </section>
</footer>

<script src="./js/sum_procedures_values.js"></script>
<script src="./js/checkbox_verify.js"></script>
<script src="./js/input_masks.js"></script>

</body>
</html>

<?php // contador de visitas

   function contadorvisitas()
    {
        $archivo = "contadorvisitas.txt"; //el archivo de texto que contendra las visitas
        $f = fopen($archivo, "r"); //abrimos el fichero en modo de lectura
        if($f)
        {
            $contadorvisitas = fread($f, filesize($archivo)); //vemos el archivo de texto
            $contadorvisitas = $contadorvisitas + 1; //Le sumamos +1 al contador de visitas
            fclose($f);
        }
        $f = fopen($archivo, "w+");
        if($f)
        {
            fwrite($f, $contadorvisitas);
            fclose($f);
        }
        return $contadorvisitas;
    }

    contadorvisitas()
?>


