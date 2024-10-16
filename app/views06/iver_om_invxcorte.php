<?php
include ("../../lib/jfunciones.php");
sesion();

$id_insumo=$_REQUEST[id_insumo];
$insumo=$_REQUEST[insumo];
$tipo_insumo=$_REQUEST[tipo_insumo];
$textfechainicio=$_REQUEST[fechainicio];
$textfechafinal=$_REQUEST[fechafinal];
$id_dependencia=$_REQUEST[id_dependencia];
            
             $q_inventariodoa= "
                        select 
                                procesos.id_proceso,
                                procesos.fecha_recibido,
                                procesos.id_beneficiario,
                                clientes.nombres,
                                clientes.apellidos,
                                clientes.cedula,
                                entes.nombre,
                                gastos_t_b.unidades,
                                procesos.id_estado_proceso 
                        from 
                                gastos_t_b,
                                procesos,
                                tbl_admin_dependencias,
                                clientes,
                                titulares,
                                entes
                        where 
                                gastos_t_b.id_insumo=$id_insumo and
                                gastos_t_b.id_proceso=procesos.id_proceso and 
                                procesos.id_estado_proceso!=14 and
                                procesos.fecha_recibido>='$textfechainicio' and 
                                procesos.fecha_recibido<='$textfechafinal' and
                                procesos.id_admin=tbl_admin_dependencias.id_admin and
                                tbl_admin_dependencias.id_dependencia=$id_dependencia and
                                procesos.id_titular=titulares.id_titular and
                                titulares.id_ente=entes.id_ente and
                                titulares.id_cliente=clientes.id_cliente;";

$r_inventariodoa = ejecutar($q_inventariodoa);
?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
  <tr>
    <td colspan=8 class="titulo_seccion">Relacion de desde <?php echo $textfechainicio ?> hasta <?php echo $textfechafinal ?> Medicamento <?php echo $insumo ?> </td>
   </tr>
   <tr>
    
   </tr>
  <tr>
    <td class="tdcamposc">Procesos</td>
    <td class="tdcamposc">Fecha</td>
    <td class="tdcamposc">Ente</td>
    <td class="tdcamposc">Titular</td>
    <td class="tdcamposc">Cedula Titular </td>
    <td class="tdcamposc">Beneficiario</td>
    <td class="tdcamposc">Cedula Beneficiario</td>
    <td class="tdcamposc">Cantidad</td>
  </tr>
<?php
		
        $cantidadinvdoa=0;
		while($f_inventariodoa=pg_fetch_assoc($r_inventariodoa)){
$totalcantidad=$totalcantidad + $f_inventariodoa[unidades];
if ($f_inventariodoa[id_beneficiario]>0){
	$q_clienteb=("select 
                                                clientes.id_cliente,
                                                clientes.apellidos,
                                                clientes.nombres,
                                                clientes.cedula 
                                        from 
                                                clientes,
                                                beneficiarios
                                        where 
                                                clientes.id_cliente=beneficiarios.id_cliente and
                                                beneficiarios.id_beneficiario=$f_inventariodoa[id_beneficiario]");
	$r_clienteb=ejecutar($q_clienteb);
    $f_clienteb=asignar_a($r_clienteb);   
    
    }
    
?>
  <tr>
    <td class="tdcamposc"><?php echo $f_inventariodoa[id_proceso]?></td>
    <td class="tdcamposc"><?php echo $f_inventariodoa[fecha_recibido]?></td>
    <td class="tdcamposc"><?php echo $f_inventariodoa[nombre]?></td>
    <td class="tdcamposc"><?php echo "$f_inventariodoa[nombres] $f_inventariodoa[apellidos]"?></td>
    <td class="tdcamposc"><?php echo $f_inventariodoa[cedula]?></td>
    <td class="tdcamposc"><?php echo"$f_clienteb[nombres] $f_clienteb[apellidos]"?></td>
    <td class="tdcamposc"><?php echo $f_clienteb[cedula]?></td>
    <td class="tdcamposc"><?php echo $f_inventariodoa[unidades]?></td>
  </tr>
<?php
    }

	?>
  <tr>
    <td colspan=8 class="tdcamposc"><hr></hr> </td>
   </tr>
  <tr>
    <td class="tdcamposc"></td>
    <td class="tdcamposc"></td>
    <td class="tdcamposc"></td>
    <td class="tdcamposc"></td>
    <td class="tdcamposc"></td>
    <td class="tdcamposc"></td>
    <td class="tdcamposc">Total</td>
    <td class="tdcamposc"><?php echo $totalcantidad?></td>
  </tr>


</table>