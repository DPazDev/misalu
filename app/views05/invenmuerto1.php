<?
include ("../../lib/jfunciones.php");
sesion();
$proveedorid=$_POST['elprovee'];
$pedidodepen=$_POST['espedidodepen'];
$arregloarticulos=$_POST['arreglo'];
$_SESSION['elprovedor']=$proveedorid;
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
$buscararticulos=("select tbl_insumos.id_insumo,tbl_insumos.id_tipo_insumo,   								tbl_insumos.insumo,tbl_laboratorios.laboratorio,tbl_insumos_almacen.cantidad
                              from
                                tbl_insumos,tbl_laboratorios,tbl_insumos_almacen,tbl_tipos_insumos
                                where
                               tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
                               tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and
                              ($otracaden) and  tbl_insumos_almacen.id_dependencia=$proveedorid and
tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo 
and tbl_insumos_almacen.cantidad>0 order by insumo;");
$repbuscararti=ejecutar($buscararticulos);
$loqhay=num_filas($repbuscararti);
if ($loqhay==0){
	echo"
      <table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
     <tr> 
	 
         <td colspan=4 class=\"titulo_seccion\">No hay art&iacute;culos cargados</td>  
     </tr>
</table>	 
     ";
	}else{
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

     <tr> 
	 
         <td colspan=4 class="titulo_seccion">Cargar art&iacute;culo(s) al inventario muerto</td>  
     </tr>
</table>	 
 <table class="tabla_citas"  cellpadding=0 cellspacing=0> 
<br>
  
     <tr>
      <td class="tdtitulos">Nombres del art&iacute;culo:</td>
	  <td class="tdcampos"> <select id="artiprovee" class="campos"  style="width: 310px;" onChange="ponercanActual()">
			        <option value=""></option>
           <?php  while($artprovee=asignar_a($repbuscararti,NULL,PGSQL_ASSOC)){?>
						<option   value="<?php echo "$artprovee[id_insumo]@$artprovee[cantidad]"?>"> <?php echo "$artprovee[insumo]--$artprovee[laboratorio]"?></option>
			      <?php
			             }
		              ?>
		              </select></td>  
     </tr>
	  <tr>
	       <td class="tdtitulos">Existencia actual:</td>
	    <td class="tdcampos"><input type="text" class="campos" id="existenciactual" readonly></td>  
	  </tr>  
	  <tr>
	    <td class="tdtitulos">Cantidad a despachar:</td>
	    <td class="tdcampos"><input type="text" class="campos" id="cantidapedido" ><label title="Cargar art&iacute;culo al pedido" class="boton" style="cursor:pointer" onclick="PProveIM()" >Procesar</label></td>
	 </tr>  
</table>	 	 
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id="totalarticulospedidos" style="display:none"></div>
<?}?>
