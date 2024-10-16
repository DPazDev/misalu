<?
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$quienloda=$_POST['elqentrega'];
$quienlorecibe=$_POST['elqrecibe'];
$elcustodio=$_POST['elcustodio'];
$arregloarticulos=$_POST['arreglo'];
$tok = explode(',',$arregloarticulos); 
$son=1;
$_SESSION['matriz']=array();
$_SESSION['matrizt']=array();

$_SESSION['quienda']=$quienloda;
$_SESSION['quienrecib']=$quienlorecibe;
$_SESSION['custodio']=$elcustodio;

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
       tbl_insumos.insumo,tbl_laboratorios.laboratorio,tbl_insumos_almacen.cantidad
from
       tbl_insumos,tbl_laboratorios,tbl_insumos_almacen
where
       tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
       tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and
       ($otracaden) and tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
       tbl_insumos_almacen.id_dependencia=$quienloda order by insumo");
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
      <td class="tdtitulos">Nombres del art&iacute;culo:</td>
	  <td class="tdcampos"> <select id="artiprovee" class="campos"  style="width: 310px;" onChange="ponerprecio()">
			        <option value=""></option>
           <?php  while($artprovee=asignar_a($repbuscararti,NULL,PGSQL_ASSOC)){
                              
                              if((($elid==60)or($elid==1))and (empty($pedidodepen))){
								   $buscarcanti=("select tbl_insumos_almacen.cantidad where 
tbl_insumos_almacen.id_insumo=$artprovee[id_insumo] and tbl_insumos_almacen.id_dependencia=2");  
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
      <tr>
	    <td class="tdtitulos">Cantidad a despachar:</td>
	    <td class="tdcampos"><input type="text" class="campos" id="cantidapedido" ><label title="Cargar art&iacute;culo al pedido" class="boton" style="cursor:pointer" onclick="PProveOB()" >Procesar</label></td>
	 </tr>  
</table>	 	 
<div id="totalarticulospedidos" style="display:none"><img src="../public/images/spinner.gif" /><label style="color: #0000FF">Cargando…</label></div>
<?}?>
