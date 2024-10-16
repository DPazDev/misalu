<?php

include ("../../lib/jfunciones.php");
sesion();

$proceso=$_REQUEST['proceso'];

/* *** BUSCA EL USUARIO *** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$fechaactual=date("Y-m-d");
$hora=date("H:i:s");


/* *** BUSCA EL PROCESO *** */
$q_espera="select * from procesos where procesos.id_proceso='$proceso'";
$r_espera=ejecutar($q_espera);
$f_espera=asignar_a($r_espera);
$cant_registro=num_filas($r_espera);

/* *** VERIFICA SINO EXISTE EL PROCESO *** */
if($cant_registro<=0) {?>

<table  class="tabla_citas"    cellpadding=0 cellspacing=0>
<tr> <td colspan=4 class="titulo_seccion">LA ORDEN NO EXISTE</td></tr>


<?php } 



else {



// COMPRUEBA SI EL PROCESO FUE DONADO

if($f_espera['id_estado_proceso']==18)
{?> 
     <table  class="tabla_citas"    cellpadding=0 cellspacing=0>
                        <tr> <td colspan=4 class="titulo_seccion">ESTA ORDEN YA FUE DONADA</td></tr>

<?php }

else {
	
// COMPRUBA SI TIENE FACTURA
$q_factura=("select * from tbl_facturas,tbl_procesos_claves,tbl_series where tbl_facturas.id_factura=tbl_procesos_claves.id_factura and tbl_procesos_claves.id_proceso=$proceso and tbl_facturas.id_serie=tbl_series.id_serie and tbl_facturas.id_estado_factura<>3");
$r_factura=ejecutar($q_factura);
$num_filasf=num_filas($r_factura);

if($num_filasf<=0 )
{


// CONSULTA EL ESTADO DE ORDEN APROBADO OPERADO O CANDIDATO//
if($f_espera['id_estado_proceso']==2 || $f_espera['id_estado_proceso']==7) {
	
	//BLOQUEO DEL PROCESO POR FECHA

$fecha_proceso=$f_espera['fecha_creado'];

$fecha_ve=date("Y-m-d",strtotime($fecha_proceso."+ 4 month"));
if($fechaactual>$fecha_ve) {?>
  
  <table  class="tabla_citas"    cellpadding=0 cellspacing=0>
                         <tr> <td colspan=4 class="titulo_seccion">La orden no puede ser Donada</td></tr>	
	<?php }
	
	else {
	
//CONSULTA DATOS DEL PROCESO	
$busqueda_pro=("select procesos.comentarios,admin.nombres,admin.apellidos,servicios.servicio,estados_procesos.id_estado_proceso,estados_procesos.estado_proceso,gastos_t_b.descripcion ,gastos_t_b.nombre,gastos_t_b.id_tipo_servicio,gastos_t_b.id_servicio,gastos_t_b.id_proveedor,gastos_t_b.monto_aceptado,procesos.comentarios,coberturas_t_b.id_cobertura_t_b,coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario from coberturas_t_b,gastos_t_b,procesos,servicios,estados_procesos,admin where coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_proceso='$proceso' and procesos.id_estado_proceso=estados_procesos.id_estado_proceso and gastos_t_b.id_servicio=servicios.id_servicio
and admin.id_admin=procesos.id_admin");
$ProcesoGeneral=ejecutar($busqueda_pro);
$RegGe=asignar_a($ProcesoGeneral);

$id_titular=$RegGe['id_titular'];

/* *** CONSULTA  SI ES TITULAR *** */
$titular=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,estados_clientes.estado_cliente,titulares.id_titular
from clientes,titulares,estados_t_b,estados_clientes
where
titulares.id_titular='$id_titular'
and clientes.id_cliente=titulares.id_cliente 
and titulares.id_titular=estados_t_b.id_titular 
and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente 
and estados_t_b.id_beneficiario=0");
$b_titular=ejecutar($titular);
$Regtitular=asignar_a($b_titular);


if($RegGe['id_beneficiario']>0) {
/* *** CONSULTA SI ES BENEFICIARIOS *** */
$id_beneficiario=$RegGe['id_beneficiario'];
$beneficiario=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,estados_clientes.estado_cliente,beneficiarios.id_beneficiario
from clientes,beneficiarios,estados_t_b,estados_clientes
where beneficiarios.id_beneficiario='$id_beneficiario'
and clientes.id_cliente=beneficiarios.id_cliente 
and beneficiarios.id_beneficiario=estados_t_b.id_beneficiario 
and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente 
and estados_t_b.id_beneficiario<>0");
$b_beneficiario=ejecutar($beneficiario);
$r_beneficiario=asignar_a($b_beneficiario);

$datos_beneficiario=true;
}

?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de Este Cliente como Titular"?> <input  type="hidden" size="10"  name="id_cobertura_t_b" class="campos" maxlength="10" ></td></tr>
        <tr>
                <td class="tdtitulos">Nombres y Apellidos del titular</td>
                <td class="tdcampos"><?php echo $Regtitular['nombres'] ?><?php echo $Regtitular['apellidos']?></td>
                <td class="tdtitulos">Cedula Titular</td>
                <td class="tdcampos"><?php echo $Regtitular['cedula']?></td>
        </tr>
           
         <tr>      
                <td class="tdtitulos">Estado del Cliente</td>
                <td class="tdcamposr"><?php echo $Regtitular['estado_cliente']?></td>
        </tr>

        
<?php       
if($datos_beneficiario==true) 
 {?>



  </tr>
           <tr>
	         	<td class="tdtitulos">Nombres y Apellidos del Beneficiario</td>
           		<td class="tdcampos"><?php echo $r_beneficiario['nombres']?> <?php echo $r_beneficiario[apellidos]?></td>
           		

	        </tr>		
      	
           <tr>
	         	<td class="tdtitulos">Estado del beneficiario</td>
           		<td class="tdcampos"><?php echo $r_beneficiario['estado_cliente']?> </td>
           		

	        </tr>		

<?php
}     
 

       
?>   
        <tr>
               <td class="tdtitulos"> Estado del Proceso</td>
               <td class="tdcampos"><?php echo $RegGe['estado_proceso']?></td>           
		         <td class="tdtitulos"> Comentarios Operador</td>
		         <td class="tdcampos"><textarea class="campos"  name="cooperador" cols=18 rows=3><?php echo $RegGe['comentarios']?></textarea> </td>        
         </tr>
        <tr>
                <td class="tdtitulos"> Analista</td>
                <td class="tdcampos"><?php echo $RegGe['nombres']?> <?php echo $RegGe['apellidos']?></td>        
        </tr>
        
        <tr>
				<td colspan=1 class="tdtitulos">Nombre del Gasto</td>
              	<td colspan=2 class="tdtitulos">Descripcion</td>
				<td colspan=1 class="tdtitulos">Gasto</td>
      </tr>	
		<?php
		
$monto_g=("select gastos_t_b.id_cobertura_t_b,gastos_t_b.id_gasto_t_b,gastos_t_b.descripcion as descrip,gastos_t_b.nombre,gastos_t_b.id_tipo_servicio,gastos_t_b.id_servicio,gastos_t_b.id_proveedor,gastos_t_b.enfermedad,gastos_t_b.monto_reserva,gastos_t_b.monto_aceptado,gastos_t_b.factura,procesos.fecha_recibido,procesos.comentarios from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_proceso='$proceso'");		
$b_monto_g=ejecutar($monto_g);
		
		while($r_monto_g=asignar_a($b_monto_g,NULL,PGSQL_ASSOC)){
		$totalmonto=$totalmonto+ $r_monto_g['monto_aceptado'];
		$descri=$r_monto_g[descrip];
		?>
	<tr>
				<td colspan=1 class="tdcampos"><?php echo $r_monto_g[nombre] ?></td>
				<td colspan=2 class="tdcampos"><?php echo $r_monto_g[descrip]?></td>
				<td colspan=1 class="tdcampos"><?php echo $r_monto_g[monto_aceptado]?></td>
		<?php
		}
		?>
	</tr>		
			<tr>
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=2 class="tdtitulos">Total</td>
				<td colspan=1 class="tdtitulos"><?php echo formato_montos($totalmonto);?></td>
	</tr>	

        
        
    <tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de la Orden de Atencion en Estado $RegGe[estado_proceso]"?></td> </tr>      
            
    <?php
    	//ESTADO DONATIVO
$f_donativo=("select * from estados_procesos where id_estado_proceso=18; ");
$b_donativo=ejecutar($f_donativo);
$a_donativo=asignar_a($b_donativo); 
  ?>           <tr> 
                 <td class="tdtitulos" colspan="1">ESTADO DEL PROCESO:</td>
                   <td class=""  colspan="1">
                <select id="estado_pro" style="width: 300px;" name="estado_proceso" class="campos" >
                <option   value="<?php echo $RegGe['id_estado_proceso']?>"> <?php echo $RegGe['estado_proceso']?></option>  
                <option   value="<?php echo $a_donativo['id_estado_proceso']?>"> <?php echo $a_donativo['estado_proceso']?></option>            
                </select>
               </td>
          </tr>
     
<?php   
$autorizados=("select * from tbl_responsables_donativos order by responsable;");
$repautorizados=ejecutar($autorizados);

?>


        <tr>
             <td class="tdtitulos" colspan="1">AUTORIZADO POR:</td>
	         <td class=""  colspan="1">
	           <select id="autorizados" class="campos"  style="width: 230px;" >
			        <option value=""></option>
              <?php while($losautoriz=asignar_a($repautorizados,NULL,PGSQL_ASSOC)){?>
					 <option value="<?php echo $losautoriz[id_responsable_donativo]?>"> 
										<?php echo "$losautoriz[responsable]"?>
						</option>
			      <?php } ?>
		</select>  
	</td> 
    </tr>   
    


</table>
		 
<table cellspacing="10" cellpadding="10" > 
         <td title="Imprimir Solititud Donativo"><label  class="boton"  onclick="solicituddonativo(<?php echo $donativo?>)" >Solicitud</label></td>   
    	 <td title="Enviar el proceso a Donativos"><label class="boton" onclick="en_donativo()">Enviar</label></td>   
		</tr>
</table>


<?php 
}//CIERRE DEL ELSE FECHA DEL PROCESO

}//CIERRE DEL IF ESTADO DE ORDEN APROB CANDIDATO

else {
        if($f_espera['id_estado_proceso']==14 || $f_espera['id_estado_proceso']==17 ) { ?>

                   <table  class="tabla_citas"    cellpadding=0 cellspacing=0>
                        <tr> <td colspan=4 class="titulo_seccion">LA ORDEN ESTA ANULADA</td></tr>

   <?php }else { ?>

                   <table  class="tabla_citas"    cellpadding=0 cellspacing=0>
                         <tr> <td colspan=4 class="titulo_seccion">ESTE PROCESO DEBE ESTAR CANDIDATO A PAGO O APROBADOR</td></tr>"; <?php }

}//CIERRE ELSE ESTADO DE ORDEN 

      }//CIERRE DE COMPROBACION DE FACTURA

else { ?>
		
 <table  class="tabla_citas"    cellpadding=0 cellspacing=0>
  <tr> <td colspan=4 class="titulo_seccion">ESTE PROCESO YA ESTA FACTURADO</td></tr>";
    <?php }
}//CIERRE COMPROBACION ESTADO DONATIVO

}//CIERRE ELSE PARA VER SI EXISTE PROCESO
?>


