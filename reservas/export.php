<?php  
//export.php  

require_once('./config/config.php');

$dbconnect = mysqli_connect(SERVER,USER,PASSWORD,DBNAME);

$output = '';
if(isset($_POST["export"]))
{
 $query = $_POST["query"];
 $result = mysqli_query($dbconnect, $query);

 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">
        <tr>
            <th style="background-color: gray; color:white;">ID</th>  
            <th style="background-color: gray; color:white;">NOMBRE</th>  
            <th style="background-color: gray; color:white;">APELLIDO</th>  
            <th style="background-color: gray; color:white;">NIF</th>  
            <th style="background-color: gray; color:white;">EMAIL</th>
            <th style="background-color: gray; color:white;">TEL</th>
            <th style="background-color: gray; color:white;">DISPONIBILIDAD</th>
            <th style="background-color: gray; color:white;">PROCEDIMIENTO</th>
            <th style="background-color: gray; color:white;">PERIODO</th>
            <th style="background-color: gray; color:white;">PAGO</th>
            <th style="background-color: gray; color:white;">ULTIMA ALTERACIÓN</th>
        </tr>
';
  while($row = mysqli_fetch_array($result))
  {
    // MASCARAS
    $manana = "";
    $tarde = "";
    if ( $row["dispManana"] == "SI") {$manana = "MAÑANA";}
    if ( $row["dispTarde"] == "SI") {$tarde = "TARDE";}
    if ( $row["pago"] == "PAGO CONFIRMADO") {$pago = "PAGO CONFIRMADO";}
    if ( $row["pago"] != "PAGO CONFIRMADO") {$pago = "PENDIENTE";}
    // MASCARAS
   $output .= '
    <tr>  
        <td>'.$row["id"].'</td>  
        <td>'.$row["nombre"].'</td>  
        <td>'.$row["apellido"].'</td>  
        <td>'.$row["nif"].'</td>  
        <td>'.$row["email"].'</td>  
        <td>'.$row["telefono"].'</td>
        <td>'.$manana.' '.$tarde.'</td>
        <td style="text-align: center;">'.$row["procedimiento"].'</td>
        <td>'.$row["periodo"].'</td>
        <td style="text-align: center;">'.$pago.'</td>
        <td>'.$row["modified"].'</td>
    </tr>  
';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=ListaClientesTamara.xls');
  echo $output;
 }
}else{echo "ERRO";}

?>