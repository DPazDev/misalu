<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date('Y-m-d H:i:s');
$sqlinsertar=("select tbl_insumos_almacen.id_insumo,count(*)from tbl_insumos_almacen,tbl_insumos where tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and (id_tipo_insumo=2 or id_tipo_insumo=1)  and cantidad=0 group by tbl_insumos_almacen.id_insumo;");
$reginsertar=ejecutar($sqlinsertar);
  while($inven=asignar_a($reginsertar,NULL,PGSQL_ASSOC)){
    $id_insumo=$inven['id_insumo'];
    $cantidad=$inven['cantidad'];
    $sqldependeciaF=("select * from tbl_insumos_almacen where id_dependencia=2 and id_insumo='$id_insumo';");
    $regConsulta=ejecutar($sqldependeciaF);
    $cuantos3=num_filas($regConsulta);
    if($cuantos3==0){
      echo"registro insumo $id_insumo<br>";

      $sqlINSERT=("INSERT INTO tbl_insumos_almacen (id_insumo,cantidad,monto_unidad_publico,monto_publico,fecha_hora_creado,id_dependencia,comentario)
					VALUES ($id_insumo,0,0,0,'$fecha',2,'PRECIOS ')");
          $regConsulta=ejecutar($sqlINSERT);
          echo"<br>";
    }else{  echo"-<br>";
    }
  }
?>
