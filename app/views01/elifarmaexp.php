<?php
include ("../../lib/jfunciones.php");
sesion();
$idproceso=$_POST['elproceso'];
$operacion=$_POST['elopera'];
$comentanular=strtoupper($_POST['elcomenta']);
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$seactuali=0;

///DATOS DE LA PLANILLA Y EL
echo $consulPlanilla=("select procesos.id_proceso,procesos.nu_planilla,procesos.id_estado_proceso from procesos where procesos.id_proceso=$idproceso");
$Planilla_proceso=ejecutar($consulPlanilla);
  $prsplanilla=assoc_a($Planilla_proceso);
  $planll=$prsplanilla['nu_planilla'];
  $estadoproceso=$prsplanilla['id_estado_proceso'];


if($planll<>'') {
$planilla=",desancle de palnilla $planll";
}else {$planilla='';}

///comprovar el proceso no ya esta anulado
if($estadoproceso=='14')
{//el porceso esta anulado
  $mensaje="El PROCESO ($idproceso) SE ENCUENTRA ANULADO, NO SE REALIZO NINGUNA ACCION";

}else{ // se puede anular


    if($operacion=='externa'){
       $buscdatelimina=("select procesos.id_proceso,count(gastos_t_b.id_proceso),gastos_t_b.id_cobertura_t_b from
                         procesos,gastos_t_b
                         where
                         procesos.id_proceso=gastos_t_b.id_proceso and
                         procesos.id_proceso=$idproceso and procesos.id_estado_proceso<14
                         group by procesos.id_proceso,gastos_t_b.id_cobertura_t_b;");
       $repbuscadatelmina=ejecutar($buscdatelimina);
       $dataelimi=assoc_a($repbuscadatelmina);
       $idcobert=$dataelimi['id_cobertura_t_b'];
       //ver el total de lo que gasto el cliente
       $totalgtb=("select gastos_t_b.monto_reserva from gastos_t_b where gastos_t_b.id_proceso=$idproceso;");
       $reptotalgtb=ejecutar($totalgtb);
      $acumu=0;
       while($montarti=asignar_a($reptotalgtb,NULL,PGSQL_ASSOC)){
         $elmontov=$montarti['monto_reserva'];
          $acumu=$acumu+$elmontov;
      }
      //poner en cero los monto en gastos_t_b
      $ponercero=("update gastos_t_b set monto_aceptado='0',monto_pagado='0' where id_proceso=$idproceso;");
      $runponercero=ejecutar($ponercero);
      //actualizo la cobertura afectada
       $buscobafec=("select coberturas_t_b.monto_actual where coberturas_t_b.id_cobertura_t_b=$idcobert;");
       $repubuscobafe=ejecutar($buscobafec);
       $dacobafe=assoc_a($repubuscobafe);
       $montafcob=$dacobafe['monto_actual'];
       $nuevomont=$montafcob+$acumu;
      //hago la actualizacion
       $actualizoco=("update coberturas_t_b set monto_actual=$nuevomont where coberturas_t_b.id_cobertura_t_b=$idcobert;");
       $repactualizoco=ejecutar($actualizoco);
      //actualizo el estado del proceso
       $proactuali=("update procesos set id_estado_proceso=14,nu_planilla='0',comentarios='$comentanular' where procesos.id_proceso=$idproceso;");
       $reproactuali=ejecutar($proactuali);
       //se registra el log
           $mensaje="El usuario $elus ha anulado el proceso NO.$idproceso de una orden de medicamentos externa";
           $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip)
            values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
            $repactualizoellog=ejecutar($actualizoellog);
      //a ver si se actualizo
      $seactuali=1;
      if($seactuali==1){
      $mensaje="El proceso $idproceso fue anulado exitosamente!!!";
      }else{
      $mensaje="El proceso $idproceso no pudo ser anulado";
     }
    }//fin de los datos externos
    else{
       if($operacion=='interna'){
       //datos pra eliminar el proceso
       $busdatelimina=("select procesos.id_proceso,count(gastos_t_b.id_proceso),gastos_t_b.id_cobertura_t_b from
                         procesos,gastos_t_b
                         where
                         procesos.id_proceso=gastos_t_b.id_proceso and
                         procesos.id_proceso=$idproceso and procesos.id_estado_proceso<14
                         group by procesos.id_proceso,gastos_t_b.id_cobertura_t_b;");
       $repdataelimina=ejecutar($busdatelimina);
       $datacbtb=assoc_a($repdataelimina);
       $idcobtb=$datacbtb['id_cobertura_t_b'];

      //acutalizamos lo que tenemos en el proceso, y consulta de numero de planilla para desanclar
      $losgtb=("select
                     procesos.id_admin,gastos_t_b.unidades,gastos_t_b.id_insumo,gastos_t_b.descripcion,
                     gastos_t_b.monto_reserva,tbl_admin_dependencias.id_dependencia,gastos_t_b.id_proveedor
                     from
                     gastos_t_b,procesos,tbl_admin_dependencias,tbl_dependencias
                     where
                     gastos_t_b.id_proceso=$idproceso and
                     procesos.id_proceso=gastos_t_b.id_proceso and
                     procesos.id_admin=tbl_admin_dependencias.id_admin and
                     gastos_t_b.id_dependencia = tbl_admin_dependencias.id_dependencia and
                     tbl_admin_dependencias.id_dependencia = tbl_dependencias.id_dependencia and
                     tbl_dependencias.activo <> 1;");
       $reptotalgtb=ejecutar($losgtb);
      $acumu=0;
       while($montarti=asignar_a($reptotalgtb,NULL,PGSQL_ASSOC)){
         $elmontov=$montarti['monto_reserva'];
         $elproducto=$montarti['id_insumo'];
         $ladepenasumar=$montarti['id_dependencia'];
         $proveedor=$montarti['id_proveedor'];
         $lasunidades=$montarti['unidades'];
         $PrecioUnidad=$elmontov/$lasunidades;
        //busco el producto en la tabla tbl_insumos
        /* $busproinsu=("select tbl_insumos.id_insumo from tbl_insumos where tbl_insumos.id_insumo=$elproducto;");
         $repbusproinsu=ejecutar($busproinsu);
         $dataproinsu=assoc_a($repbusproinsu);
         $elidproinsu=$dataproinsu['id_insumo'];*/
         //busco el producto en la tabla tbl_insumos_almacen
         $proinsalma=("select tbl_insumos_almacen.cantidad,tbl_insumos_almacen.id_insumo_almacen from tbl_insumos_almacen where
                       tbl_insumos_almacen.id_insumo=$elproducto and tbl_insumos_almacen.id_dependencia=$ladepenasumar;");
         $repproinsalma=ejecutar($proinsalma);
         $dataproinsalma=assoc_a($repproinsalma);
         $cantidactual=$dataproinsalma['cantidad'];
         $idinsumalmac=$dataproinsalma['id_insumo_almacen'];
         $nuevacanti=$cantidactual+$lasunidades;
        //actualizo las cantidades de la dependencia afectada
          $actualisalma=("update tbl_insumos_almacen set cantidad=$nuevacanti where
                          tbl_insumos_almacen.id_insumo_almacen=$idinsumalmac and
                          tbl_insumos_almacen.id_dependencia=$ladepenasumar;");
          $repactulisalma=ejecutar($actualisalma);
          $acumu=$acumu+$elmontov;

          ///REGISTRAR MOVIMIENTO DE INVENTARIO franklin monsalve
          $Movimiento='DEVOLUCION';///que tipo de movimiento se esta haciendo
          $TipoMovimiento=1;//Es una entrada(1) o una salida(2)
          $Descripcion="DEVOLUCION SEGUN PROCESO:$idproceso";//como se puede comprovar el movimiento
          $RegistroMovimientossql=("INSERT INTO tbl_insumos_movimientos (id_insumo,id_dependencia,id_proveedor,movimiento,tipo_movimiento,precio_unitario,cantida,cantidad_almacen,cantida_actual,id_admin,nota_movimiento)
                            VALUES ('$elproducto','$ladepenasumar','$proveedor','$Movimiento','$TipoMovimiento','$PrecioUnidad','$lasunidades','$cantidactual','$nuevacanti','$elid','$Descripcion');");
         $RegistroMov=ejecutar($RegistroMovimientossql);
         //fin REGISTRAR MOVIMIENTO DE INVENTARIO
      }
         //actualizo la cobertura afectada
       $buscoaactul=("select coberturas_t_b.monto_actual from coberturas_t_b where coberturas_t_b.id_cobertura_t_b=$idcobtb;");
       $repubuscobafe=ejecutar($buscoaactul);
       $dacobafe=assoc_a($repubuscobafe);
       $montafcob=$dacobafe['monto_actual'];
       $nuevomont=$montafcob+$acumu;
      //hago la actualizacion
       $actualizoco=("update coberturas_t_b set monto_actual=$nuevomont where coberturas_t_b.id_cobertura_t_b=$idcobtb;");
       $repactualizoco=ejecutar($actualizoco);
      //actualizo el proceso a Proceso Anulado(14),nu_planilla Quitar Planilla
       $proactuali=("update procesos set id_estado_proceso=14,nu_planilla='',comentarios='$comentanular' where procesos.id_proceso=$idproceso;");
       $reproactuali=ejecutar($proactuali);
      //poner en cero los monto en gastos_t_b
      $ponercero=("update gastos_t_b set monto_aceptado='0',monto_pagado='0' where id_proceso=$idproceso;");
      $runponercero=ejecutar($ponercero);
      //a ver si se actualizo
      $seactuali=1;
      //se registra el log
           $mensaje="El usuario $elus ha anulado el proceso NO.$idproceso de una orden de medicamentos interna $planilla ";
           $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip)
            values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
            $repactualizoellog=ejecutar($actualizoellog);
      if($seactuali==1){
      $mensaje="El proceso $idproceso fue anulado exitosamente!!!";
      }else{
      $mensaje="El proceso $idproceso no pudo ser anulado";
     }

    }//fin de los datos internos
    else{
         $bucargtb=("select gastos_t_b.id_gasto_t_b,gastos_t_b.id_insumo,gastos_t_b.id_cobertura_t_b,gastos_t_b.monto_reserva,
                     gastos_t_b.unidades,gastos_t_b.id_dependencia,gastos_t_b.id_proveedor from gastos_t_b where id_proceso=$idproceso;");
         $repbuscargtb=ejecutar($bucargtb);
          $montocargado=0;
          while($loqsecargo=asignar_a($repbuscargtb,NULL,PGSQL_ASSOC)){
           $montocargado=$montocargado+$loqsecargo['monto_reserva'];
           $loqhaypedido=$loqsecargo['unidades'];
           $PrecioUnidad=($loqsecargo['monto_reserva'])/$loqhaypedido;
           $ladependencia=$loqsecargo['id_dependencia'];
           $proveedor=$loqsecargo['id_proveedor'];
           $elidinsumo=$loqsecargo['id_insumo'];
           $elidengstb=$loqsecargo['id_gasto_t_b'];
           $lacobertura=$loqsecargo['id_cobertura_t_b'];
           //busco en tbl_insumos_almacen
              $insualmacen=("select tbl_insumos_almacen.cantidad,tbl_insumos_almacen.id_insumo_almacen from tbl_insumos_almacen
     where tbl_insumos_almacen.id_insumo=$elidinsumo and tbl_insumos_almacen.id_dependencia=$ladependencia;");
              $repinsalmacen=ejecutar($insualmacen);
              $datainsalmac=assoc_a($repinsalmacen);
              $enexistencia=$datainsalmac['cantidad'];
              $lanuevaexistencia=$enexistencia+$loqhaypedido;
              $idinsumoalmacen=$datainsalmac['id_insumo_almacen'];
              //actualizo tbl_insumos_almacen
              $actinsalmacen=("update tbl_insumos_almacen set cantidad=$lanuevaexistencia where
                      id_insumo=$elidinsumo and id_dependencia=$ladependencia and id_insumo_almacen=$idinsumoalmacen;");
              $repactinsalmacen=ejecutar($actinsalmacen);

              ///REGISTRAR MOVIMIENTO DE INVENTARIO franklin monsalve
              $Movimiento='DEVOLUCION';///que tipo de movimiento se esta haciendo
              $TipoMovimiento=1;//Es una entrada(1) o una salida(2)
              $Descripcion="DEVOLUCION SEGUN PROCESO:$idproceso";//como se puede comprovar el movimiento
              $RegistroMovimientossql=("INSERT INTO tbl_insumos_movimientos (id_insumo,id_dependencia,id_proveedor,movimiento,tipo_movimiento,precio_unitario,cantida,cantidad_almacen,cantida_actual,id_admin,nota_movimiento)
                                VALUES ('$elidinsumo','$ladependencia','$proveedor','$Movimiento','$TipoMovimiento','$PrecioUnidad','$loqhaypedido','$enexistencia','$lanuevaexistencia','$elid','$Descripcion');");
              $RegistroMov=ejecutar($RegistroMovimientossql);
              //fin REGISTRAR MOVIMIENTO DE INVENTARIO


              //actualizo gastos_t_b
              $actugtb=("update gastos_t_b set monto_aceptado='0',monto_pagado='0' where id_gasto_t_b=$elidengstb;");
              $repactugtb=ejecutar($actugtb);
          }
            //busco en coberturas_t_b la cobertura afectada
           $cobeafectada=("select coberturas_t_b.monto_actual from coberturas_t_b where coberturas_t_b.id_cobertura_t_b=$lacobertura;");
           $repcobeafectada=ejecutar($cobeafectada);
           $datcoberafectada=assoc_a($repcobeafectada);
           $loqtactual=$datcoberafectada['monto_actual'];
           $elnuevomontactual=$loqtactual+$montocargado;
           //se actualiza la cobertura
           $actualicobert=("update coberturas_t_b set monto_actual='$elnuevomontactual' where id_cobertura_t_b=$lacobertura;");
           $repactualicobert=ejecutar($actualicobert);
           //actualizo el proceso a Proceso Anulado(14),nu_planilla Quitar Planilla
           $actuproceso=("update procesos set id_estado_proceso=14,nu_planilla='',comentarios='$comentanular' where id_proceso=$idproceso;");
           $repactuproceso=ejecutar($actuproceso);
           //se registra el log
           $mensaje="El usuario $elus ha anulado el proceso NO.$idproceso de una orden de medicamentos de Emergencia o Hospitalizacion $planilla";
           $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip)
            values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
            $repactualizoellog=ejecutar($actualizoellog);
          $mensaje="El proceso $idproceso fue anulado exitosamente!!!";

        }
    }

}//fin else comprovar proceso
?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>
 <tr>
    <td colspan=8 class="titulo_seccion"><?echo $mensaje?></td>
   </tr>
</table>
