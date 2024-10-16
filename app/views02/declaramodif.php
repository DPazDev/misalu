<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$idtitular=$_REQUEST['elusuario'];
$tipousuar=$_REQUEST['tipousuario'];
$repuestas=$_REQUEST['laspregun'];
$arreglorepu=explode(',',$repuestas);
$cuantasrepu=count($arreglorepu);
for($i=0;$i<=$cuantasrepu;$i++){
    list($idpregunta,$valoresp)=explode('@',$arreglorepu[$i]);
    if($tipousuar=='b'){
       $guardorespues=("update declaracion_t set respuesta=$valoresp 
                                 where id_declaracion=$idpregunta and id_beneficiario=$idtitular;");
      $repguardrespu=ejecutar($guardorespues);                                     
    }else{
           $guardorespues=("update declaracion_t set respuesta=$valoresp 
                                 where id_declaracion=$idpregunta and id_titular=$idtitular;");           
           $repguardrespu=ejecutar($guardorespues);   
        }    
}
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha modificado la declaracion de salud al usuario tipo  $tipousuar con id $idtitular";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Se ha modificado exitosamente la declaraci&oacute;n de salud</td>           
     </tr>
</table>