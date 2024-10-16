<?
include ("../../lib/jfunciones.php");
sesion();
$proveedorid=$_POST['elprovee'];
$elid=$_SESSION['id_usuario_'.empresa];
$_SESSION['elprovedor']=$proveedorid;
$pedidodepen=$_POST['espedidodepen'];
$midependencia=$_POST['misdepen'];
$_SESSION['depesaliente']=$midependencia;
$_SESSION['existepedido']=0;
 if (empty($pedidodepen)){
$buscarlprove=("select clinicas_proveedores.nombre from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and 
proveedores.id_proveedor=$proveedorid;");
$repbusclprove=ejecutar($buscarlprove);
$datdelprov=assoc_a($repbusclprove);
$nombrepp=$datdelprov['nombre'];
$mensaje11='para el proveedor';
//para que pueda ver todos los articulos cambio la variable a 1 ya que es el almacen central
$proveedorid=2;
$querycan="";
$quecantid="";
$tabla1="";
}else{
	 $buscarladepen=("select tbl_dependencias.dependencia from tbl_dependencias where id_dependencia=$proveedorid");
	 $repbusclprove=ejecutar($buscarladepen);
     $datdelprov=assoc_a($repbusclprove); 
	$nombrepp=$datdelprov['dependencia']; 
	$mensaje11='para la dependencia';
	$_SESSION['pedidodepen']=1;
      $querycan="and tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
       tbl_insumos_almacen.id_dependencia=$proveedorid and tbl_insumos_almacen.cantidad>0 ";
       $tabla1=",tbl_insumos_almacen";
       $seleccan=",tbl_insumos_almacen.cantidad";
	}
$arregloarticulos=$_POST['arreglo'];
$tok = explode(',',$arregloarticulos); 
$son=1;
$_SESSION['matriz']=array();
$_SESSION['matrizt']=array();
foreach ( $tok as $tok ) 
{ 
   if($tok>0){	
   $caja[$son]=$tok;
   $son++;
  }

}
for ($i=1;$i<$son;$i++){
 if($i<=1){
    $otracaden="tbl_insumos.id_tipo_insumo=$caja[$i]";
  }else{
        $cadena="or tbl_insumos.id_tipo_insumo=$caja[$i]";
        $otracaden="$otracaden $cadena"; 
       }
}
$buscararticulos=("select tbl_insumos.id_insumo,tbl_insumos.id_tipo_insumo,   
       tbl_insumos.insumo,tbl_laboratorios.laboratorio
from
       tbl_insumos,tbl_laboratorios,tbl_tipos_insumos$tabla1
where
       tbl_insumos.activo=1 and
       tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
       tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and
       ($otracaden) $querycan  order by insumo");
echo $buscararticulos;
$repbuscararti=ejecutar($buscararticulos);
$loqhay=num_filas($repbuscararti);
if ($loqhay==0){
     
	echo"
      <table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
<br>
     <tr> 
	 
         <td colspan=4 class=\"titulo_seccion\">No hay art&iacute;culos cargados $mensaje11 $nombrepp</td>  
     </tr>
</table>	 
     ";
	}else{
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>
     <tr> 
	 
         <td colspan=4 class="titulo_seccion">Cargar art&iacute;culo(s) <?echo "$mensaje11 $nombrepp";?></td>  
     </tr>
</table>	 
 <table class="tabla_citas"  cellpadding=0 cellspacing=0> 
<br>
     <tr>
          <td class="tdtitulos">C&oacute;digo de barra:</td>
          <td class="tdcampos"><input class="campos" type="text" id="cobbarra" size=32></td>
     </tr> 
     <tr>
   
      <td class="tdtitulos">Nombres del art&iacute;culo:</td>
	  <td class="tdcampos"> <select id="artiprovee" class="campos"  style="width: 310px;" onChange="ponerprecio()">
			        <option value=""></option>
           <?php  while($artprovee=asignar_a($repbuscararti,NULL,PGSQL_ASSOC)){
                              
                              if((($elid==2)or($elid==1))and (empty($pedidodepen))){
								   $buscarcanti=("select tbl_insumos_almacen.cantidad from tbl_insumos_almacen where 
tbl_insumos_almacen.id_insumo=$artprovee[id_insumo] and tbl_insumos_almacen.id_dependencia=89");
     
								   $repbuscarcanti=ejecutar($buscarcanti);
								   $cantascantidad=num_filas($repbuscarcanti);   
									if($cantascantidad==0){
										$mostarcan="";
										}else{
											$datacuancantid=assoc_a($repbuscarcanti);
											$lascanitda=$datacuancantid['cantidad'];
                                            $mostarcan="----$lascanitda"; 
											}
                                   
                              }else{
                                    $mostarcan="";
                                  }?>
			<option   value="<?php echo "$artprovee[id_insumo]@$artprovee[monto_unidad_publico]|$artprovee[cantidad]"?>"> <?php echo "$artprovee[insumo]--$artprovee[laboratorio]$mostarcan"?></option>
			      <?php
			             }
		              ?>
		              </select></td>  
     </tr>
	<?if($_SESSION['pedidodepen']==0){
	echo" <tr>
	    <td class=\"tdtitulos\">Precio actual:</td>
	    <td class=\"tdcampos\"><input class=\"campos\" type=\"text\" id=\"precioarti\" ></td>
	 </tr>"; 
	}else{
		echo"<input type=\"hidden\" id=\"precioarti\" >";
		}?>  
	<?if($_SESSION['pedidodepen']==1)
	echo" <tr>
	    <td class=\"tdtitulos\">Cantidad en existencia:</td>
	    <td class=\"tdcampos\"><input class=\"campos\" type=\"text\" id=\"canarti\" disabled></td>
	 </tr>"; 
	?>  
	  <tr>
	    <td class="tdtitulos">Cantidad a pedir:</td>
	    <td class="tdcampos"><input type="text" class="campos" id="cantidapedido" ><label title="Cargar art&iacute;culo al pedido" class="boton" style="cursor:pointer" onclick="PProve()" >Procesar</label></td>
	 </tr>  
</table>	 	 
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id="totalarticulospedidos" style="display:none"></div>
<?}?>
