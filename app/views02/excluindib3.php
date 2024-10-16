<?php
include ("../../lib/jfunciones.php");
sesion();
$elbenf=$_REQUEST['elbenefi'];
$estado=$_REQUEST['elstado'];
$comenta=strtoupper($_REQUEST['comenta']);
$hijos=$_REQUEST['elhijo'];
//guardar todo en las distintas tablas 
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];

                       $bustitu=("select beneficiarios.id_titular from beneficiarios where beneficiarios.id_beneficiario=$elbenf");
                       $repbustitu=ejecutar($bustitu);
                       $datatti=assoc_a($repbustitu);
                       $titular=$datatti[id_titular];
                       $actualetbben=("update estados_t_b set id_estado_cliente=$estado where  id_beneficiario=$elbenf and id_titular=$titular");
                       $repactualetbben=ejecutar($actualetbben);
                       $insertregexclu=("insert into registros_exclusiones(fecha_inclusion,id_titular,id_beneficiario,fecha_creado,
                                id_estado_cliente,comentario,fecha_exclusion,id_admin) 
                                values('$fecha',$titular,$elbenf,'$fecha',$estado,'$comenta','$fecha',$elid)");
                        $repinserregexclu=ejecutar($insertregexclu);        
                         //**********************************//
                         //Guardar los datos en la tabla logs;
                         $mensaje="$elus, ha cambiado el estado del beneficiario=$elbenf al estado id_estado_cliente=$estado del titular=$titular";
                         $relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
                         $inrelo=ejecutar($relog); 
                       
    
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
   <tr> 
     <td colspan=4 class="titulo_seccion">Se ha generado el cambio existosamente!!</td>  
    </tr>
 </table>
