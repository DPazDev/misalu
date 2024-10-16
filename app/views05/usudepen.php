<?php
include ("../../lib/jfunciones.php");
sesion();
$busdepen=("select id_dependencia,dependencia from tbl_dependencias order by dependencia;");
$repbusdepen=ejecutar($busdepen);
$cuantdepen=num_filas($repbusdepen);
$admines=("select nombres,apellidos,id_admin from admin order by nombres;");
$repadmines=ejecutar($admines);
$sucursales=("select id_sucursal,sucursal from sucursales order by sucursal;");
$repsucursal=ejecutar($sucursales);
$r=$_POST['text'];
if ($r>0){
	$adminmodif=("select tbl_admin_dependencias.id_admin_dependencia,
       tbl_admin_dependencias.id_dependencia,
       tbl_admin_dependencias.id_admin,
       tbl_admin_dependencias.activar,
       admin.nombres,admin.apellidos,
       tbl_dependencias.dependencia
from 
       admin,tbl_dependencias,tbl_admin_dependencias
where
       tbl_admin_dependencias.id_admin_dependencia='$r' and
       tbl_admin_dependencias.id_admin=admin.id_admin and
       tbl_admin_dependencias.id_dependencia=tbl_dependencias.id_dependencia;");
	$resuladminmodif=ejecutar($adminmodif);   
	$dataadmodif=assoc_a($resuladminmodif);
}	
    if ($cuantdepen==0){
	   echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
            <br>
           <tr> 
			<td colspan=4 class=\"titulo_seccion\">No existe ninguna dependencia creada!!</td>  
          </tr>
          </table>";	
	}else{?>
	   <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
         <br>	     
            <tr>
               <td class="tdtitulos">Nombre del usuario:</td>
               <td class="tdcampos"><select id="nomusu" class="campos"  style="width: 230px;" >
			                 <?if ($r>0){
								   echo"<option value=\"$dataadmodif[id_admin]\">$dataadmodif[nombres] $dataadmodif[apellidos]</option>"; 
								 }else{?>
                              <option value="0"></option>
							<?}?>  
			                   <?php  while($usuarios=asignar_a($repadmines,NULL,PGSQL_ASSOC)){?>
			                       <option value="<?php echo $usuarios[id_admin]?>"> <?php echo "$usuarios[nombres] $usuarios[apellidos]"?></option>
			      <?php
			             }
		              ?>
		              </select></td> 
	       </tr>  
	       <tr>
               <td class="tdtitulos">Nombre de la dependencia:</td>
               <td class="tdcampos"><select id="nomdep" class="campos"  style="width: 230px;" >
			            <?if ($r>0){
								   echo"<option value=\"$dataadmodif[id_dependencia]\">$dataadmodif[dependencia]</option>";    
						}else{?>		   
                              <option value="0"></option>
						<?}?>  
			                   <?php  while($dependecias=asignar_a($repbusdepen,NULL,PGSQL_ASSOC)){?>
			                       <option value="<?php echo $dependecias[id_dependencia]?>"> <?php echo "$dependecias[dependencia]"?></option>
			      <?php
			             }
		              ?>
		              </select></td> 
	       </tr>     
		   <tr>
               <td class="tdtitulos">Permisolog&iacute;a:</td>
               <td class="tdcampos">
			      <?if ($r>0){
					   if($dataadmodif[activar]==1 ) {
						echo"<input type=\"radio\" name=\"group1\" id=\"op1\" value=\"1\" checked>Pedidos y Entregas
<input type=\"radio\" name=\"group1\" id=\"op2\" value=\"2\" >Entregas
<input type=\"radio\" name=\"group1\" id=\"op3\" value=\"3\" >Pedidos
<input type=\"radio\" name=\"group1\" id=\"op4\" value=\"4\" > Desactivar";   
                       }else{
						             if($dataadmodif[activar]==2 ) {
										echo"
 <input type=\"radio\" name=\"group1\" id=\"op1\" value=\"1\" >Pedidos y Entregas 
<input type=\"radio\" name=\"group1\" id=\"op2\" value=\"2\"  checked>Entregas
<input type=\"radio\" name=\"group1\" id=\"op3\" value=\"3\" >Pedidos
<input type=\"radio\" name=\"group1\" id=\"op4\" value=\"4\" > Desactivar"; 
									}else{
										         if($dataadmodif[activar]==3) {
													echo"
                                                     <input type=\"radio\" name=\"group1\" id=\"op1\" value=\"1\" >Pedidos y Entregas 
<input type=\"radio\" name=\"group1\" id=\"op2\" value=\"2\" >Entregas
													<input type=\"radio\" name=\"group1\" id=\"op3\" value=\"3\" checked>Pedidos
<input type=\"radio\" name=\"group1\" id=\"op4\" value=\"4\" > Desactivar"; 
												}else{
															echo"<input type=\"radio\" name=\"group1\" id=\"op1\" value=\"1\" >Pedidos y Entregas 
<input type=\"radio\" name=\"group1\" id=\"op2\" value=\"2\" >Entregas
<input type=\"radio\" name=\"group1\" id=\"op3\" value=\"3\" >Pedidos
<input type=\"radio\" name=\"group1\" id=\"op4\" value=\"4\" checked> Desactivar ";
													       } 
										      }	 
						         }
					}else{   
                   ?>   
		           <input type="radio" name="group1" id="op1" value="1">Pedidos y Entregas
                   <input type="radio" name="group1" id="op2" value="2" >Entregas
		           <input type="radio" name="group1" id="op3" value="3" >Pedidos
                   <input type="radio" name="group1" id="op4" value="4"> Desactivar 
	       </td> 
	       </tr>        
		  <?}?>   
		   <input type="hidden"  id="modif" value="<?echo $r;?>">  
	       <tr>   
		 <td  title="Registrar usuarios a dependencia"><label class="boton" style="cursor:pointer" onclick="usudepen()" >Guardar </label></td>   
	       </tr>      
       </table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
	<?}?>
        <div id="usuendepen"></div> 
