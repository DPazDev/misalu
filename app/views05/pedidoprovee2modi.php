<?
include ("../../lib/jfunciones.php");
include ("arreglosN.php");
sesion();
$arregloarticulos=$_POST['arreglo'];
$elidpedidoes=$_POST['idpedido'];
$_SESSION['pasopedido']=1;
$buscarprovee=("select tbl_ordenes_pedidos.id_dependencia from tbl_ordenes_pedidos where id_orden_pedido=$elidpedidoes;");
$repbuscarprovee=ejecutar($buscarprovee);
$databuscarprovee=assoc_a($repbuscarprovee);
$proveedorid=$_POST['depenes'];
$tok = explode(',',$arregloarticulos); 
$son=1;
$_SESSION['control']=0;
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
       tbl_insumos,tbl_laboratorios,tbl_tipos_insumos
where
       tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
       tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo and
       ($otracaden) and tbl_insumos.activo=1 order by insumo");
$repbuscararti=ejecutar($buscararticulos);
$loqhay=num_filas($repbuscararti);
//llamamos a la funcion primero para cargar los articulos existentes en el pedido, le pasamo el parametro
//el numero del pedido
$_SESSION['matriz']=primero($elidpedidoes);
$lamatix=$_SESSION['matriz'];
$cuantomatriz=count($_SESSION['matriz']);
$_SESSION['existepedido']=$elidpedidoes;
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
	          <td colspan=4 class="titulo_seccion">Cargar de art&iacute;culo(s) </td>  
     </tr>
</table>	 
 <table class="tabla_citas"  cellpadding=0 cellspacing=0> 
<br>
     <tr>
      <td class="tdtitulos">Nombres del art&iacute;culo:</td>
	  <td class="tdcampos"> <select id="artiprovee" class="campos"  style="width: 310px;" onChange="ponerprecios1()">
			        <option value=""></option>
           <?php  while($artprovee=asignar_a($repbuscararti,NULL,PGSQL_ASSOC)){?>
						<option   value="<?php echo "$artprovee[id_insumo]@0"?>"> <?php echo "$artprovee[insumo]--$artprovee[laboratorio]"?></option>
			      <?php
			             }
		              ?>
		              </select></td>  
     </tr>	
	 <tr> 
	    <td class="tdtitulos">Precio actual:</td>
	    <td class="tdcampos"><input type="text" id="canarti" ></td>
	 </tr>	
	  <tr>
	    <td class="tdtitulos">Cantidad a pedir:</td>
	    <td class="tdcampos"><input type="text" id="cantidapedido" ><label title="Cargar art&iacute;culo al pedido" class="boton" style="cursor:pointer" onclick="PProve1()" >Procesar</label></td>
	 </tr>  	
</table>	
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />
<div id="temporalarti">
<br>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
       <br> 
         <td colspan=4 class="titulo_seccion">Art&iacute;culos cargados</td>    
     </tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
              <tr>
			     <th class="tdtitulos">Lin.</th>  
                 <th class="tdtitulos">Nombre del art&iacute;culo.</th>
                 <th class="tdtitulos">Laboratorio.</th>
                 <th class="tdtitulos">Cantidad.</th>
                 <th class="tdtitulos">Precio.</th>
                 <th class="tdtitulos">Sub-Total.</th>
              </tr>
            <?
			   $lin=1;
			   $canar=0;   
			   $subgener=0;
               for($i=0;$i<=$cuantomatriz;$i++){
                          $nom=$lamatix[$i][0];
                          $lab=$lamatix[$i][1];
                          $cant=$lamatix[$i][2];
                          $prec=$lamatix[$i][3];
                          $subt=$lamatix[$i][4];
						  $idart=$lamatix[$i][5];  
						  $canar=$canar+$cant;  
						  $subgener=$subgener+$subt;  
                   echo"<tr>";
						if(!empty($nom)){
                         echo"<td class=\"tdcampos\">$lin</td> ";
                         }else{$lin--;}
				echo"<td class=\"tdcampos\">$nom</td>  
                          <td class=\"tdcampos\">$lab</td> 
                          <td class=\"tdcampos\">$cant</td>  
                          <td class=\"tdcampos\">$prec</td> 
                          <td class=\"tdcampos\">$subt</td>   
                        </tr>
                        
                       ";
							 
				$lin++;	   
               }
                
				         echo"<tr>
                            <td class=\"tdcampos\"></td>  
                            <td class=\"tdcampos\"></td>  
                           <th>Totales:</th>  
							<th>$canar</th>  
                            <td class=\"tdcampos\"></td>   
                             <th>$subgener</th>  
                           </tr>
						 ";  
				
            ?>
            <br>
			</table>
</div>
<div id="totalarticulospedidos" style="display:none"><img src="../public/images/spinner.gif" /><label style="color: #0000FF">Cargando…</label></div>
<hr>
