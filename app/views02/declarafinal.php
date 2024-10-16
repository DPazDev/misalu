<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$elusuario=$_REQUEST['tipousuario'];
list($idusuario,$tipousu)=explode('-',$elusuario);
if($tipousu=='B'){
  $buscartitu=("select beneficiarios.id_titular from beneficiarios where id_beneficiario=$idusuario;");
  $repdeltitu=ejecutar($buscartitu);
  $datatitu=assoc_a($repdeltitu);
  $elidtitular=$datatitu[id_titular];
}
$repuestas=$_REQUEST['laspregun'];
$arreglorepu=explode(',',$repuestas);
$cuantasrepu=count($arreglorepu);
for($i=0;$i<=$cuantasrepu;$i++){
    list($idpregunta,$valoresp)=explode('@',$arreglorepu[$i]);
    if($tipousu=='B'){
       $guardorespues=("insert into declaracion_t(id_declaracion,respuesta,id_titular,id_beneficiario,id_admin) 
                                     values($idpregunta,$valoresp,$elidtitular,$idusuario,$elid);");
      $repguardrespu=ejecutar($guardorespues);                                     
    }else{
           $guardorespues=("insert into declaracion_t(id_declaracion,respuesta,id_titular,id_beneficiario,id_admin) 
                                     values($idpregunta,$valoresp,$idusuario,0,$elid);");
           $repguardrespu=ejecutar($guardorespues);   
        }    
}
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha generado la declaracion de salud al usuario tipo  $tipousu con id $idusuario";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Se ha generado exitosamente la declaraci&oacute;n de salud</td>           
     </tr>
</table>
