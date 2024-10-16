<?php
include ("../../lib/jfunciones.php");
sesion();
require_once '../../lib/Excel/reader.php';

// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
$data->setOutputEncoding('CP1251');

/***
* if you want you can change 'iconv' to mb_convert_encoding:
* $data->setUTFEncoder('mb');
*
**/

/***
* By default rows & cols indeces start with 1
* For change initial index use:
* $data->setRowColOffset(0);
*
**/



/***
*  Some function for formatting output.
* $data->setDefaultFormat('%.2f');
* setDefaultFormat - set format for columns with unknown formatting
*
* $data->setColumnFormat(4, '%.3f');
* setColumnFormat - set format for column (apply only to number fields)
*
**/
$filename = 'ordentresj.xls';
if (file_exists($filename)) {
    $data->read($filename);
} else {
   echo "<script language=\"JavaScript\">\n";
      echo "alert('No existe el archivo!!');\n";
   echo "</script>";  
}

/*


 $data->sheets[0]['numRows'] - count rows
 $data->sheets[0]['numCols'] - count columns
 $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

 $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell
    
    $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
        if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
    $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format 
    $data->sheets[0]['cellsInfo'][$i][$j]['colspan'] 
    $data->sheets[0]['cellsInfo'][$i][$j]['rowspan'] 
*/
$fecha=date("Y-m-d");
$hora=date("H:i:s");
error_reporting(E_ALL ^ E_NOTICE);?>
<?
$comenta="Pedido generado  automaticamente";
/*Primero buscamos la data y el id del proveedor*/
 $buscarprovee=("select clinicas_proveedores.nombre from clinicas_proveedores,proveedores 
                          where proveedores.id_proveedor=923 and 
                           clinicas_proveedores.id_clinica_proveedor= proveedores.id_clinica_proveedor;");
$repuestabusprovee=ejecutar($buscarprovee);
$databusprovee=assoc_a($repuestabusprovee);
$nomprovee=$databusprovee['nombre'];
//Fin de la busquedad de la data y id del proveedor
/*Lo segundo es bucar cuantos pedidos se le ha realizado al proveedor*/
$buscarpedidos=("select tbl_ordenes_pedidos.no_orden_pedido 
from 
tbl_ordenes_pedidos 
where id_proveedor='923' order by tbl_ordenes_pedidos.no_orden_pedido
desc limit 1;");
$repbuscarpedidos=ejecutar($buscarpedidos);
$cuantoped=num_filas($repbuscarpedidos);
if($cuantoped>=1){
	$datnpedido=assoc_a($repbuscarpedidos);
	$npediactual=$datnpedido['no_orden_pedido'];
	$elmayor=$npediactual+1;
}else{
	 $elmayor=1;
	}
/*fin de la busquedad de los pedidos realizados al proveedor*/	
//guardar los datos principales en la tabla tbl_ordenes_pedidos//
$insertarpedido=("insert into tbl_ordenes_pedidos(id_dependencia,fecha_pedido,fecha_hora_creado,id_admin,comentarios,hora_pedido,no_orden_pedido, id_proveedor,estatus,id_dependencia_saliente) 
values(0,'$fecha','$fecha',$elid,upper('$comenta'),'$hora',$elmayor,923,1,0);");
$repinsetarpedido=ejecutar($insertarpedido);
//fin de insertar el valor en la tabla tbl_ordenes_pedidos//
//Buscar el elemento recien guarados en la tabla tbl_ordenes_pedidos//
$registroguardado=("select id_orden_pedido from tbl_ordenes_pedidos where no_orden_pedido=$elmayor and
                    id_proveedor=923;");
$repuestaregistroguardado=ejecutar($registroguardado);
$datareguardado=assoc_a($repuestaregistroguardado);
$idpedido=$datareguardado['id_orden_pedido'];
//Fin de la busquedad//
?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Art&iacute;culo.</th>
                 <th class="tdtitulos">Laboratorio.</th>
				 <th class="tdtitulos">Cantidad despachada.</th> 
              </tr>   
<?for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		//echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
		$idinsumo=$data->sheets[0]['cells'][$i][2];	//La posición [0][2] debe coincidir con la posición 1 de la hoja excel (en este caso seria la columna del código de barra)
		$idtipoinsumo=$data->sheets[0]['cells'][$i][3];//La posición [0][3] debe coincidir con la posición 2 de la hoja excel (en este caso seria la columna del nombre del producto)
        $idtipolabora=$data->sheets[0]['cells'][$i][4];//La posición [0][4] debe coincidir con la posición 3 de la hoja excel (en este caso seria la columna del precio)
        $labora=$data->sheets[0]['cells'][$i][5];//La posición [0][5] debe coincidir con la posición 3 de la hoja excel (en este caso seria la columna del precio)
        $tipoinsumo=$data->sheets[0]['cells'][$i][6];//La posición [0][6] debe coincidir con la posición 3 de la hoja excel (en este caso seria la columna del precio)
        $elinsumo=$data->sheets[0]['cells'][$i][7];//La posición [0][7] debe coincidir con la posición 3 de la hoja excel (en este caso seria la columna del precio)
        $lacantidad=$data->sheets[0]['cells'][$i][8];//La posición [0][8] debe coincidir con la posición 3 de la hoja excel (en este caso seria la columna del precio)
		$elcostopro=$data->sheets[0]['cells'][$i][9];//La posición [0][8] debe coincidir con la posición 3 de la hoja excel (en este caso seria la columna del precio)
        
	}
	 //Primero buscamos si existe el producto
	$buscarin=("select tbl_insumos.id_insumo from tbl_insumos where
tbl_insumos.id_insumo=$idinsumo;"); 
    $repbuscarin=ejecutar($buscarin);
	$cuntoinsu=num_filas($repbuscarin);
	if($cuntoinsu>=1){
		 $guardaproducto=("insert into tbl_insumos_ordenes_pedidos(id_orden_pedido,id_insumo,cantidad) values ($idpedido,$idinsumo,'$lacantidad') ;");  
     $repguardaproducto=ejecutar($guardaproducto);
	}else{
		
		}
		echo"
                <tr>
				   <td class=\"tdcampos\">$elinsumo</td>
				   <td class=\"tdcampos\">$labora</td> 				   
				   <td class=\"tdcampos\">$lacantidad</td>      				    
				</tr>
                ";	
}?>
</table>
