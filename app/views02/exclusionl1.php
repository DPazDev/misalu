<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$acambia=$_REQUEST['quien'];
$alente=$_REQUEST['quente'];
$alestado=$_REQUEST['questado'];
$alafecha=$_REQUEST['quefecha'];
$alcomentario=$_REQUEST['quecomenta'];
$countitu=0;
$counben=0;
if($acambia==1){
   $querbt=" ,estados_t_b.id_beneficiario";
   $quetit="titulares.id_titular";
   $mensajetipo="Todos los clientes";
}else{
      if($acambia==3){
            $quebenef=" ,beneficiarios.id_beneficiario";
            $quetit="titulares.id_titular";
            $tabla=",beneficiarios";
            $tituben="and titulares.id_titular=beneficiarios.id_titular and
beneficiarios.id_beneficiario=estados_t_b.id_beneficiario";
            $mensajetipo="Todos los beneficiarios";
          }else{
                $solotitu="and estados_t_b.id_beneficiario=0";
                $quetit="titulares.id_titular";
                $mensajetipo="Todos los titulares";
              }
    }

$busqueda=("select $quetit,titulares.id_ente,estados_clientes.estado_cliente$quebenef $querbt
 from
  titulares,estados_clientes,estados_t_b$tabla
where
  titulares.id_ente=$alente and
  titulares.id_titular=estados_t_b.id_titular and
  estados_t_b.id_estado_cliente=4 and
  estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente  
  $solotitu $tituben
  order by titulares.id_titular desc");
  $repsbusq=ejecutar($busqueda);
  $nuacambiar=num_filas($repsbusq);
  if($nuacambiar==0){?>
      <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
       <tr> 
           <td colspan=4 class="titulo_seccion">No existe informaci&oacute;n en el rango seleccionado!!</td>  
       </tr>
    </table>	
  <?}else{    
       while($cambestado=asignar_a($repsbusq,NULL,PGSQL_ASSOC)){
             $idtitular=$cambestado['id_titular'];
             $idbenfi=$cambestado['id_beneficiario'];
              if($acambia==1){
                if($idbenfi==0){
                       $cargcambio=("insert into registros_exclusiones(id_titular,comentario,fecha_inclusion,fecha_exclusion,id_estado_cliente) values($idtitular,'$alcomentario','$fecha','$fecha',$alestado)");
                       $repcarcambio=ejecutar($cargcambio);                       
                       $buscambioestb=("update estados_t_b set id_estado_cliente=$alestado,fecha_creado='$alafecha' where id_titular=$idtitular and id_beneficiario=0");
                       $actcambestb=ejecutar($buscambioestb);            
                       $countitu++;
                    }else{
                       $cargcambio=("insert into registros_exclusiones(id_titular,id_beneficiario,comentario,fecha_inclusion,fecha_exclusion,id_estado_cliente) values($idtitular,$idbenfi,'$alcomentario','$fecha','$fecha',$alestado)");
                       $repcarcambio=ejecutar($cargcambio);                      
                       $buscambioestb=("update estados_t_b set id_estado_cliente=$alestado,fecha_creado='$alafecha' where id_beneficiario=$idbenfi");
                       $actcambestb=ejecutar($buscambioestb);
                       $counben++;
                       }
                       $mesaje1="Se han actualizado un total de $countitu titulares y $counben beneficiarios exitosamente!!";
            }else{
                      if($acambia==2){
                        $cargcambio=("insert into registros_exclusiones(id_titular,comentario,fecha_inclusion,fecha_exclusion,id_estado_cliente,id_admin) values($idtitular,'$alcomentario','$fecha','$fecha',$alestado,$elid)");
                        $repcarcambio=ejecutar($cargcambio);
                        $buscambioestb=("update estados_t_b set id_estado_cliente=$alestado,fecha_creado='$alafecha' where id_titular=$idtitular and id_beneficiario=0");
                        $actcambestb=ejecutar($buscambioestb);
                        $countitu++;
                        $mesaje1="Se han actualizado un total de $countitu titulares exitosamente!!";
                     }else{
                          $cargcambio=("insert into registros_exclusiones(id_titular,id_beneficiario,comentario,fecha_inclusion,fecha_exclusion,id_estado_cliente,id_admin) values($idtitular,$idbenfi,'$alcomentario','$fecha','$fecha',$alestado,$elid)");
                          $repcarcambio=ejecutar($cargcambio);
                          $buscambioestb=("update estados_t_b set id_estado_cliente=$alestado,fecha_creado='$alafecha' where id_beneficiario=$idbenfi");
                          $actcambestb=ejecutar($buscambioestb); 
                          $counben++;
                          $mesaje1="Se han actualizado un total de $countitu titulares exitosamente!!";
                        }
                }
        }   
     }   
//**********************************//
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha cambiado el estado por lote de $mensajetipo del ente $alente al estado id_estado_cliente=$alestado para recuperar en
                   fecha=$alafecha";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
//fin de los registros en la tabla logs;
//**********************************//
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion"><?echo $mesaje1?></td>  
     </tr>
</table>	
