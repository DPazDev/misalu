<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$clave=$_REQUEST['clave'];
$numpre=$_REQUEST['numpre'];
$comentario=strtoupper($_REQUEST['comentario']);
$cuadro_m=strtoupper($_REQUEST['cuadro_m']);
$dateFieldfe=$_REQUEST['dateFieldfe'];

$admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);



if  ($clave=='0'){
    ?>
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=6 class="titulo_seccion">No Se Actualizo con Exito  el Numero de Clave  y Fecha Relacion Ente Privado La Clave no puede ser 0 </td>	</tr>	
    </table>
	<?php
    }
    else
    {
	

/* **** busco el id_cliente y procesos que tengan este numero de planilla o presupuesto registrado **** */
$q_cliente=("select 
                                procesos.id_proceso,
                                procesos.id_beneficiario,
                                clientes.nombres,
                                clientes.apellidos,
                                clientes.cedula,
                                admin.nombres as nomadmin,
                                admin.apellidos as   apeadmin,
                                entes.nombre
                        from 
                                procesos,
                                titulares,
                                clientes,
                                admin,
                                entes
                        where
                                procesos.no_clave='$clave' and 
                                procesos.id_titular=titulares.id_titular and 
                                titulares.id_cliente=clientes.id_cliente and
                                procesos.id_admin=admin.id_admin and 
titulares.id_ente=entes.id_ente");
$r_cliente=ejecutar($q_cliente);
$num_filas=num_filas($r_cliente);

if  ($num_filas==0)

{


	/* **** Actualizo la clave y fecha ente priv ****  */
$mod_proceso="update 
                                    procesos 
                            set 
									id_estado_proceso='7',
                                    no_clave='$clave',
									fecha_ent_pri='$dateFieldfe',
									comentarios='$comentario'
                            where 
                                    procesos.nu_planilla='$numpre'";
$fmod_proceso=ejecutar($mod_proceso);

$mod_procesog="update 
                                    gastos_t_b 
							set 
									enfermedad='$cuadro_m'
							from 
									procesos
                            
                            where 
                                    gastos_t_b.id_proceso=procesos.id_proceso and
									procesos.nu_planilla='$numpre'";
$fmod_procesog=ejecutar($mod_procesog);

/* **** Se registra lo que hizo el usuario**** **/


$log="ACTUALIZO LOS PROCESOS   con  numero de planilla  $numpre  le colo el numero de clave $clave y fecha_ente_pri  $dateFieldfe ";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
    ?>
    
        <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=6 class="titulo_seccion">Se Actualizo con Exito  el Numero de Clave  y Fecha Relacion Ente Privado
<?php $url="'views01/ifpresupuestop.php?proceso=$numpre&si=1&ente=$ente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Finiquito Presupuesto Ente Privado </a>
</td>	</tr>	
    </table>
    
  <?php  }
  else
  {

?>
<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=7 class="titulo_seccion">Numero de Clave Asignada a los Sig. Procesos. </td>	</tr>	
	<tr>
		<td class="tdtitulos">Proceso</td>
        <td class="tdtitulos">Titular</td>
        <td class="tdtitulos">C&eacute;dula</td>
        <td class="tdtitulos">Beneficiario</td>
        <td class="tdtitulos">C&eacute;dula</td>
        <td class="tdtitulos">Ente</td>
        <td class="tdtitulos">Analista</td>
        </tr>	
        <tr>		<td colspan=7 class="tdtitulos"><hr></hr> </td>	</tr>	
	<tr>
        
        <?php 

		while($f_cliente=asignar_a($r_cliente,NULL,PGSQL_ASSOC)){
            
$q_beneficiario=("select 
                                    * 
                            from 
                                    beneficiarios,
                                    clientes 
                            where 
                                    beneficiarios.id_cliente=clientes.id_cliente and 
                                    beneficiarios.id_beneficiario=$f_cliente[id_beneficiario]");
$r_beneficiario=ejecutar($q_beneficiario);
$f_beneficiario=asignar_a($r_beneficiario);
?>        
 <tr>		<td colspan=7 class="tdtitulos"><hr></hr> </td>	</tr>	
        	<tr>
		<td class="tdtitulos"><?php echo $f_cliente[id_proceso]?></td>
        <td class="tdtitulos"><?php echo "$f_cliente[nombres] $f_cliente[apellidos] "?></td>
        <td class="tdtitulos"><?php echo $f_cliente[cedula]?></td>
        <td class="tdtitulos"><?php echo "$f_beneficiario[nombres] $f_beneficiario[apellidos] "?></td>
        <td class="tdtitulos"><?php echo $f_beneficiario[cedula] ?></td>
        <td class="tdtitulos"><?php echo $f_cliente[nombre] ?></td>
        <td class="tdtitulos"><?php echo "$f_cliente[nomadmin] $f_cliente[apeadmin] "?></td>
        </tr>
        
        <?php
        }
        ?>
</table>


<?php
}
}

?>
