<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$codigo=$_POST['laopcion'];
$busqueda=$_POST['pabuscar'];
$tipoinsumo=$_POST['insumoid'];
if($tipoinsumo==0){
  $querytipinsumo="";
}else{
     $querytipinsumo="tbl_insumos.id_tipo_insumo=$tipoinsumo and";
    }
if($busqueda=='d'){
  if($codigo==0){
    $todas='si'; 
     $querysucu="";
     $mensaje="Art&iacute;culo(s) asignado(s) a TODAS LAS DEPENDENCIAS";
  }else{
    $querysucu="and tbl_insumos_almacen.id_dependencia=$codigo";
     $nombrdepen=("select tbl_dependencias.dependencia from tbl_dependencias where tbl_dependencias.id_dependencia=$codigo;");
  $repnombredepen=ejecutar($nombrdepen);
  $datadepen=assoc_a($repnombredepen);
  $nomdepen=$datadepen['dependencia'];
  $mensaje="Art&iacute;culo(s) asignado(s) a la dependencia $nomdepen";
  }
   $query=("select tbl_insumos.id_insumo,tbl_insumos.insumo,tbl_insumos.id_tipo_insumo,
          tbl_laboratorios.laboratorio,tbl_tipos_insumos.tipo_insumo,tbl_insumos_almacen.id_dependencia,
         tbl_dependencias.dependencia,tbl_insumos_almacen.cantidad 
 from 
  tbl_insumos,tbl_laboratorios,tbl_insumos_almacen,tbl_tipos_insumos,tbl_dependencias
where
  tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
  tbl_insumos_almacen.id_dependencia=tbl_dependencias.id_dependencia and
  $querytipinsumo
  tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
  tbl_tipos_insumos.id_tipo_insumo=tbl_insumos.id_tipo_insumo $querysucu 
  and tbl_insumos_almacen.cantidad>0 order by
  tbl_insumos.insumo;");
  $repquery=ejecutar($query);
  $cuantos=num_filas($repquery);
 
}else{
     list($iddependencia,$idarticulo,$idtipoarti)=explode("-",$codigo);
     $tipo=gettype($idarticulo);
     $tipo1=gettype($iddependencia);
    
     if(($tipo=='string') and($tipo1=='string')){
      $query=("select tbl_insumos.id_insumo,tbl_insumos.insumo,tbl_insumos.id_tipo_insumo,
tbl_laboratorios.laboratorio,tbl_tipos_insumos.tipo_insumo,
tbl_insumos_almacen.id_dependencia,tbl_dependencias.dependencia from 
  tbl_insumos,tbl_laboratorios,tbl_insumos_almacen,tbl_tipos_insumos,
  tbl_dependencias
where
  tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
  tbl_insumos_almacen.id_dependencia=tbl_dependencias.id_dependencia and
  $querytipinsumo
  tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
  tbl_tipos_insumos.id_tipo_insumo=tbl_insumos.id_tipo_insumo and
  tbl_insumos.id_insumo=$idarticulo and
  tbl_insumos_almacen.id_dependencia=$iddependencia
  order by
  tbl_insumos.insumo;");
  $repquery=ejecutar($query);
  $mensaje="El art&iacute;culo con el c&oacute;digo $iddependencia-$idarticulo-$idtipoarti";
   $cuantos=num_filas($repquery);
  }else{
      $palabra=strtoupper($codigo);
      $query=("select tbl_insumos.id_insumo,tbl_insumos.insumo,tbl_insumos.id_tipo_insumo,
tbl_laboratorios.laboratorio,tbl_tipos_insumos.tipo_insumo,
tbl_insumos_almacen.id_dependencia,tbl_dependencias.dependencia,tbl_insumos_almacen.cantidad
 from 
  tbl_insumos,tbl_laboratorios,tbl_insumos_almacen,tbl_tipos_insumos,
  tbl_dependencias
where
  tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
  $querytipinsumo
  tbl_insumos_almacen.id_dependencia=tbl_dependencias.id_dependencia and
  tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
  tbl_tipos_insumos.id_tipo_insumo=tbl_insumos.id_tipo_insumo and
  tbl_insumos.insumo like('%$palabra%') 
  order by
  tbl_insumos.insumo;");
  $repquery=ejecutar($query);
  $mensaje="El art&iacute;culo con la combinacion de letras $palabra se encuentra en:";
  $cuantos=num_filas($repquery);   
      }
  }
if($cuantos<=0){
    echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class=\"titulo_seccion\">No hay informaci&oacute;n!!!!</td>
	</tr>	 
 </table>	";
   }else{ 
?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion"><?echo $mensaje ?></td>
	</tr>	 
 </table>	
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">C&oacute;digo.</th>
                 <th class="tdtitulos">Art&iacute;culo.</th>  
                 <th class="tdtitulos">Laboratorio.</th>
               <?if(($busqueda=='a') or ($todas=='si')){
                   echo "<th class=\"tdtitulos\">Dependencia.</th>";
                   }?>
		 <th class="tdtitulos">Tipo de art&iacute;culo.</th> 					 
                <th class="tdtitulos">Cantidad.</th>
            </tr>
            	 <?php 				
			    while($articulos=asignar_a($repquery,NULL,PGSQL_ASSOC)){				
				?>
			    <tr>
				   <td class="tdcampos"><?echo "$articulos[id_dependencia]-$articulos[id_insumo]-$articulos[id_tipo_insumo]";?></td>
				   <td class="tdcampos"><?echo $articulos['insumo'];?></td>
                                   <td class="tdcampos"><?echo $articulos['laboratorio'];?></td> 				  
                                    <?if(($busqueda=='a')or($todas=='si')){
                                     echo "<td class=\"tdcampos\"> $articulos[dependencia]</td>";
                                    }?>
                                   <td class="tdcampos"><?echo $articulos['tipo_insumo'];?></td>      				    
                                   <td class="tdcampos"><?echo $articulos['cantidad'];?></td>
				</tr>
			<?}
        }    ?>		
		 <tr>
            <? $url="'views05/bus_articulos2.php?laopcion=$codigo&pabuscar=$busqueda&insumoid=$tipoinsumo'"; ?>  
       <td>
	     <td colspan=4 class="tdcampos"><a href="javascript: imprimir(<?echo $url?>);" class="boton">Imprimir</a>
	   </td>   
        </tr>
</table>	
