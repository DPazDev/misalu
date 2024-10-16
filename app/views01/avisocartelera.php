<?php
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$ftodosaviso = $_REQUEST['todosaviso'];
$fdepartaviso = $_REQUEST['departaviso'];
$favisousu = $_REQUEST['usuarioaviso'];
$fechaviso = date("Y-m-d");
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Avisos informativos!!</td>          
	</tr>
</table>
<?php 
//buscar si el aviso es para todo el mundo!!
$buscaraviso = ("select admin.nombres,admin.apellidos,cartelera.aviso,cartelera.fecha_creado
from
admin,cartelera
where
admin.id_admin = cartelera.id_admin and
admin.activar  = '1' and
(cartelera.fechaini between '$fechaviso' and '$fechaviso' or
cartelera.fechafin between '$fechaviso' and '$fechaviso') and
cartelera.id_usuarios = 0 and
cartelera.id_departamento = 0");
$repbuscaraviso = ejecutar($buscaraviso);


$buscarusariodepartamento = ("select admin.id_departamento from admin where id_admin=$elid");
$repbuscarusuariodepartamento = ejecutar($buscarusariodepartamento);
$datodepartamento = assoc_a($repbuscarusuariodepartamento);
$eldepatamento = $datodepartamento['id_departamento'];
//buscar si el aviso es para un departamento en particular!!
$buscaravisosdepartamento = ("select admin.nombres,admin.apellidos,cartelera.aviso,cartelera.fecha_creado
from
admin,cartelera
where
admin.id_admin = cartelera.id_admin and
admin.activar  = '1' and
(cartelera.fechaini between '$fechaviso' and '$fechaviso' or
cartelera.fechafin between '$fechaviso' and '$fechaviso') and
admin.id_departamento = cartelera.id_departamento and 
cartelera.id_departamento = $eldepatamento and
cartelera.id_departamento > 0 and
cartelera.id_usuarios = 0");

$repavisosdepartamento = ejecutar($buscaravisosdepartamento);


//buscar si el aviso es para un usuario en particular!!
$buscaravisousuario = ("select admin.nombres,admin.apellidos,cartelera.aviso,cartelera.fecha_creado
from
admin,cartelera
where
admin.id_admin = cartelera.id_admin and
admin.activar  = '1' and
(cartelera.fechaini between '$fechaviso' and '$fechaviso' or
cartelera.fechafin between '$fechaviso' and '$fechaviso') and
cartelera.id_usuarios = $elid and
cartelera.id_departamento = $eldepatamento");
$repbusavisousuario = ejecutar($buscaravisousuario);
?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
                 <th class="tdtitulos">Aviso.</th>  
                 <th class="tdtitulos">Fecha.</th>  
                 <th class="tdtitulos">Informa.</th>  
			     
</tr>			     

<?php if(($ftodosaviso) >= 1 ){
     while($mparatodos=asignar_a($repbuscaraviso,NULL,PGSQL_ASSOC)){
     
?>     
    <tr>
         <td class="tdcampos" align="justify"><?echo $mparatodos['aviso']?></td>   
         <td class="tdcampos" align="justify"><?echo $mparatodos['fecha_creado']?></td>                 
         <td class="tdcampos" align="justify"><?echo "$mparatodos[nombres] $mparatodos[apellidos]"?></td>                 
   </tr> 		 
<?php }
 }
	  if(($fdepartaviso) >=1 ){
	       while($mparatodosndeparta=asignar_a($repavisosdepartamento,NULL,PGSQL_ASSOC)){
	?>		   
		<tr>
         <td class="tdcampos" align="justify"><?echo $mparatodosndeparta['aviso']?></td>   
         <td class="tdcampos" align="justify"><?echo $mparatodosndeparta['fecha_creado']?></td>                 
         <td class="tdcampos" align="justify"><?echo "$mparatodosndeparta[nombres] $mparatodosndeparta[apellidos]"?></td>                 
       </tr> 	   
			   
      <?php }
	    }
	       if(($favisousu) >=1 ){
	       while($mparausuario=asignar_a($repbusavisousuario,NULL,PGSQL_ASSOC)){
	   ?>	
	<tr>
         <td class="tdcampos" align="justify"><?echo $mparausuario['aviso']?></td>   
         <td class="tdcampos" align="justify"><?echo $mparausuario['fecha_creado']?></td>   
         <td class="tdcampos" align="justify"><?echo "$mparausuario[nombres] $mparausuario[apellidos]"?></td>   
       </tr> 	   
			   
      <?php } 
	 }
   


?>

</table>
