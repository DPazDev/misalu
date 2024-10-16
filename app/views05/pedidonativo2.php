<?
include ("../../lib/jfunciones.php");
sesion();
$proveedorid=$_POST['dependencia'];
$arregloarticulos=$_POST['arreglo'];
$quienautoriza=$_POST['loautoriza'];
$tipocliedona=$_POST['eldichoso'];
$comentario=$_POST['comentario'];
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
       tbl_insumos.insumo,tbl_laboratorios.laboratorio,
       tbl_insumos_almacen.monto_unidad_publico,tbl_insumos_almacen.cantidad 
from
       tbl_insumos,tbl_laboratorios,tbl_insumos_almacen,tbl_tipos_insumos
where
       tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
       tbl_insumos_almacen.id_dependencia=$proveedorid and
       tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
       tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and
       ($otracaden) order by insumo");
$repbuscararti=ejecutar($buscararticulos);
$loqhay=num_filas($repbuscararti);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

     <tr> 	 
         <td colspan=4 class="titulo_seccion">Cargar art&iacute;culo(s)</td>  
     </tr>
</table>	 
 <table class="tabla_citas"  cellpadding=0 cellspacing=0> 
<br>
     <tr>
      <td class="tdtitulos">Nombres del art&iacute;culo:</td>
	  <td class="tdcampos"> <select id="artiprovee" class="campos"  style="width: 310px;" onChange="ponerprecio1()">
			        <option value=""></option>
           <?php  while($artprovee=asignar_a($repbuscararti,NULL,PGSQL_ASSOC)){?>
						<option   value="<?php echo "$artprovee[id_insumo]@$artprovee[cantidad]"?>"> <?php echo "$artprovee[insumo]"?></option>
			      <?php
			             }
		              ?>
		              </select></td>  
     </tr>
	<tr>
	    <td class="tdtitulos">Cantidad en existencia:</td>
	    <td class="tdcampos"><input type="text" id="cantiexistencia" ></td>
	 </tr>   
	 <tr>
	    <td class="tdtitulos">Cantidad a donar:</td>
	    <td class="tdcampos"><input type="text" id="cantidadonar" ><label title="Cargar art&iacute;culo al pedido" class="boton" style="cursor:pointer" onclick="PDonativo()" >Procesar</label></td>
	 </tr>  
</table>	
<input type='hidden' id='ladepensaliente' value='<? echo $proveedorid;?>'> 
<input type='hidden' id='elautorizo' value='<? echo $quienautoriza;?>'>
<input type='hidden' id='elcomentario' value='<? echo $comentario;?>'> 
<input type='hidden' id='eltipocliente' value='<? echo $tipocliedona;?>'>

	 
<img alt="spinner" id="spinner" src="../public/images/spinner.gif" style="display:none;" />
<div id="totalarticulospedidos" ></div>
