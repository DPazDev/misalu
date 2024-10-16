<?php
include ("../../lib/jfunciones.php");
sesion();
$pasoporben=0;
$cultitu=$_REQUEST['eltitular'];
list($titular,$ente)=explode('@',$cultitu);
$estado=$_REQUEST['elpasestado'];
$comenta=strtoupper($_REQUEST['elcomenta']);
$hijos=$_REQUEST['elhijo'];
//guardar todo en las distintas tablas 
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
if($hijos==0){
    //primero actualizamos el estado en la tabla estados_tb
    $actutb=("update estados_t_b set id_estado_cliente=$estado where id_titular=$titular and id_beneficiario=0");
    $repactutb=ejecutar($actutb);
    //luego guardamos el cambio en la tabla registros_exclusiones
    $guardregexcl=("insert into registros_exclusiones(fecha_inclusion,id_titular,id_beneficiario,fecha_creado,
                                id_estado_cliente,comentario,fecha_exclusion,id_admin) 
                                values('$fecha',$titular,0,'$fecha',$estado,'$comenta','$fecha',$elid)");
     $repguardregex=ejecutar($guardregexcl); 
     //**********************************//
     //************Guardar el comentario**********************//
     $guarcoment=("update clientes set comentarios='$comenta' from titulares where
                    clientes.id_cliente=titulares.id_cliente and titulares.id_titular=$titular;");
     $repguarcoment=ejecutar($guarcoment);               
   //Guardar los datos en la tabla logs;
  $mensaje="$elus, ha cambiado el estado del titular=$titular al estado id_estado_cliente=$estado";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
//fin de los registros en la tabla logs;
//**********************************//

}else{
        $benfestado=explode(",",$hijos);
        $hayhijo=count($benfestado);
        for($i=0;$i<=$hayhijo;$i++){
               list($elbenf,$opc)=explode('-',$benfestado[$i]);
               $busben=("select count(id_titular) from estados_t_b where id_titular=$titular and id_beneficiario=$elbenf");
               $repbusben=ejecutar($busben);
               $datdebus=assoc_a($repbusben);
               $infobus=$datdebus['count'];
                if($infobus==1){
                       $pasoporben=1;
                       $actualetbben=("update estados_t_b set id_estado_cliente=$estado where id_titular=$titular and 
                                                  id_beneficiario=$elbenf");
                       $repactualetbben=ejecutar($actualetbben);    
                       $insertregexclu=("insert into registros_exclusiones(fecha_inclusion,id_titular,id_beneficiario,fecha_creado,
                                id_estado_cliente,comentario,fecha_exclusion,id_admin) 
                                values('$fecha',$titular,$elbenf,'$fecha',$estado,'$comenta','$fecha',$elid)");
                        $repinserregexclu=ejecutar($insertregexclu);        
                         //**********************************//
                          //************Guardar el comentario**********************//
                          $guarcomenbe=("update clientes set comentarios='$comenta' from beneficiarios where
                                    clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$elbenf;");
                        $repguarcomenbe=ejecutar($guarcomenbe);               
                      //**********************************//
                         //Guardar los datos en la tabla logs;
                         $mensaje="$elus, ha cambiado el estado del beneficiario=$elbenf al estado id_estado_cliente=$estado del titular=$titular";
                         $relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
                         $inrelo=ejecutar($relog); 
                    }
            }
            if($pasoporben==1){
                   $insertregexclu1=("insert into registros_exclusiones(fecha_inclusion,id_titular,id_beneficiario,fecha_creado,
                                id_estado_cliente,comentario,fecha_exclusion,id_admin) 
                                values('$fecha',$titular,0,'$fecha',$estado,'$comenta','$fecha',$elid)");
                        $repinserregexclu1=ejecutar($insertregexclu1);    
                        $actualetbbent=("update estados_t_b set id_estado_cliente=$estado where id_titular=$titular and 
                                                  id_beneficiario=0");
                       $repactualetbbent=ejecutar($actualetbbent); 
                }
    }
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
   <tr> 
     <td colspan=4 class="titulo_seccion">Se ha generado el cambio existosamente!!</td>  
    </tr>
 </table>
