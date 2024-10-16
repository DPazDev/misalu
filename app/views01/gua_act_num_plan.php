<?php
include ("../../lib/jfunciones.php");

$nu_planilla =$_REQUEST['nu_planilla'];
$ant_planilla =$_REQUEST['ant_planilla'];

$admin= $_SESSION['id_usuario_'.empresa];

$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** verificar numero de presupuesto o planilla **** */

if ($nu_planilla>0){
    
/* **** busco el id_cliente y procesos que tengan este numero de planilla o presupuesto registrado **** */
$q_clientet=("select 
                            procesos.id_titular,
                            count(procesos.id_titular) 
                    from 
                            procesos 
                    where 
                            nu_planilla='$ant_planilla' 
                    group by 
                            procesos.id_titular");
$r_clientet=ejecutar($q_clientet);
$num_filas=num_filas($r_clientet);

$q_clienteb=("select 
                            procesos.id_beneficiario,
                            count(procesos.id_beneficiario) 
                    from 
                            procesos 
                    where 
                            nu_planilla='$ant_planilla' 
                    group by 
                            procesos.id_beneficiario");
$r_clienteb=ejecutar($q_clienteb);
$num_filasb=num_filas($r_clienteb);


	
                
                if ($num_filas>1 || $num_filasb>1)
                {
                    
              
              
              /* **** busco el id_cliente y procesos que tengan este numero de planilla o presupuesto registrado **** */
$q_cliente=("select 
                                procesos.id_proceso,
                                procesos.id_titular,
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
                                procesos.nu_planilla='$ant_planilla' and 
                                procesos.id_titular=titulares.id_titular and 
                                titulares.id_cliente=clientes.id_cliente and
                                procesos.id_admin=admin.id_admin and 
titulares.id_ente=entes.id_ente");
$r_cliente=ejecutar($q_cliente);
?>
<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=7 class="titulo_seccion">
No Se Actualizo el Proceso ya que el Numero de Planilla o Presupuesto  esta Asignado al Siguiente Usuario Verificar a Quien le Pertenece  la Planilla y Asignarla a un Solo Usuario.
</td>	</tr>	
	<tr>
		<td class="tdtitulos">Proceso</td>
        <td class="tdtitulos">Titular</td>
        <td class="tdtitulos">C&eacute;dula</td>
        <td class="tdtitulos">Beneficiario</td>
        <td class="tdtitulos">C&eacute;dula</td>
        <td class="tdtitulos">Ente</td>
        <td class="tdtitulos">Analista</td>
        </tr>	
        <tr>		<td colspan=6 class="tdtitulos"><hr></hr> </td>	</tr>	
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
        
        ?>
</table>


<?php
}
           
                    }
                    
                    else
                    {
                        
                       $q_cliente=("select 
                                procesos.id_proceso,
                                procesos.id_titular,
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
                                procesos.nu_planilla='$nu_planilla' and 
                                procesos.id_titular=titulares.id_titular and 
                                titulares.id_cliente=clientes.id_cliente and
                                procesos.id_admin=admin.id_admin and 
titulares.id_ente=entes.id_ente");
$r_cliente=ejecutar($q_cliente);
$num_filas=num_filas($r_cliente); 

if ($num_filas==0){
	/* **** Actualizo el numero de Planilla en los procesos ****  */
$mod_proceso="update 
                                    procesos 
                            set 
                                    nu_planilla='$nu_planilla'
                            where 
                                    procesos.nu_planilla='$ant_planilla'";
$fmod_proceso=ejecutar($mod_proceso);

/* **** Se registra lo que hizo el usuario**** **/


$log="ACTUALIZO LOS PROCESOS   QUE TENIAN EL NUMERO DE PLANILLA $ant_planilla al numero $nu_planilla";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion">Los Procesos se Actualizaron con exito al Numero Planilla<?php echo $nu_planilla?> </td>	
</tr>	

	</table>
<?php
}
else
{
    ?>
    <table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=7 class="titulo_seccion">
No Se Actualizo el Proceso ya que el Numero de Planilla o Presupuesto  esta Asignado al Siguiente Usuario Verificar a Quien le Pertenece  la Planilla y Asignarla a un Solo Usuario.
</td>	</tr>	
	<tr>
		<td class="tdtitulos">Proceso</td>
        <td class="tdtitulos">Titular</td>
        <td class="tdtitulos">C&eacute;dula</td>
        <td class="tdtitulos">Beneficiario</td>
        <td class="tdtitulos">C&eacute;dula</td>
        <td class="tdtitulos">Ente</td>
        <td class="tdtitulos">Analista</td>
        </tr>	
        <tr>		<td colspan=6 class="tdtitulos"><hr></hr> </td>	</tr>	
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

/* **** fin de verificar si el proceso se le actualiza un numero de planilla**** */
}
}
?>



