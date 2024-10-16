<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$clave=$_REQUEST['clave'];

if  ($clave==0){
    
    
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
    ?>
    
        <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=6 class="titulo_seccion">No esta  Asignado el Numero de Clave </td>	</tr>	
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
