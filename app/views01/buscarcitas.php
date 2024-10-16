<?php
include ("../../lib/jfunciones.php");
sesion();
$cedula=$_REQUEST['cedula'];
$fechainicio=$_REQUEST['fechainicio'];
$fechafin=$_REQUEST['fechafin'];
$proveedor=$_REQUEST['proveedor'];
$proveedorc=$_REQUEST['proveedorc'];
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

if ($f_admin[id_ente]>0){
    $condicionente="and entes.id_ente=$f_admin[id_ente]";
    }
/* **** busco si es titular **** */
$q_cliente=("select 
                            clientes.nombres,
                            clientes.apellidos,
                            clientes.telefono_hab,
                            clientes.telefono_otro,
                            titulares.id_titular,
                            entes.nombre,
                            estados_clientes.estado_cliente 
                    from 
                            estados_clientes,
                            clientes,
                            titulares,
                            entes,
                            estados_t_b 
                    where 
                            clientes.cedula='$cedula' and 
                            clientes.id_cliente=titulares.id_cliente and 
                            titulares.id_ente=entes.id_ente 
                             $condicionente and 
                            estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
                            estados_t_b.id_beneficiario=0 and 
                            estados_t_b.id_titular=titulares.id_titular");
$r_cliente=ejecutar($q_cliente);
$num_filas=num_filas($r_cliente);
$titular=1;

/* **** busco si es beneficiario **** */
if ($num_filas == 0) { 
$q_clienteb=("select 
                                clientes.nombres,
                                clientes.apellidos,
                                clientes.telefono_hab,
                                clientes.telefono_otro,
                                beneficiarios.id_titular,
                                beneficiarios.id_beneficiario,
                                estados_clientes.estado_cliente 
                        from 
                                estados_clientes,
                                clientes,
                                beneficiarios,
                                estados_t_b,
                                titulares,
                                entes
                        where 
                                clientes.cedula='$cedula' and 
                                clientes.id_cliente=beneficiarios.id_cliente and 
                                estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
                                estados_t_b.id_beneficiario>0 and 
                                estados_t_b.id_beneficiario=beneficiarios.id_beneficiario and
                                beneficiarios.id_titular=titulares.id_titular and
                                titulares.id_ente=entes.id_ente
                                $condicionente ");
$r_clienteb=ejecutar($q_clienteb) ;
$num_filasb=num_filas($r_clienteb);
$titular=0;
}

if ($num_filas==0 and $num_filasb==0){

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<tr> <td colspan=4 class="titulo_seccion">El Numero de Cedula no existe  o no tiene ninguna cita asignada</td>
      </tr>
<?php 

if ($f_admin[id_ente]>0){
    
    }
    else
    {
        ?>
    
	<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><a href="#" OnClick="reg_cita();" class="boton">Registrar Citas</a></td>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><a href="#" OnClick="ir_principal();" class="boton">salir</a></td>
		<td class="tdtitulos"></td>
	</tr>
    <?php 
    }
    ?>
</table>
<?php
}
else
{
?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<?php if ($titular==1) {
	
/* ***** repita para buscar al titular **** */
while($f_cliente=asignar_a($r_cliente,NULL,PGSQL_ASSOC)){

?>
<tr> <td colspan=9 class="titulo_seccion">  <?php echo  "Datos de Este Cliente como Titular"?> <input  type="hidden" size="10"  name="id_cobertura_t_b" class="campos" maxlength="10" value=<?php echo $f_cobertura[id_cobertura_t_b]; ?>></td></tr>

<tr>
		<td colspan=2 class="tdtitulos">Nombres y Apellidos del Titular</td>
		<td colspan=2  class="tdcampos"><?php echo $f_cliente[nombres]?> <?php echo $f_cliente[apellidos]?></td>
		<td colspan=1 class="tdtitulos">Ente</td>
                <td colspan=3 class="tdcampos"><?php echo $f_cliente[nombre]?></td
</tr>
<tr>
				<td colspan=1  class="tdtitulos">Telefono 1</td>
                <td colspan=1  class="tdcampos"><?php echo $f_cliente[telefono_hab]?></td>
				<td colspan=1 class="tdtitulos">Telefono 2</td>
                <td colspan=1 class="tdcampos"><?php echo $f_cliente[telefono_otro]?></td>
	        <td colspan=2 class="tdtitulos">Estado</td>
                <td colspan=2 class="tdcamposr">	<?php echo $f_cliente[estado_cliente]?></td>
</tr>		
	
<?php
/* **** buscar citas de titulares **** */

if ($proveedorc>0 || $proveedorc=="*")
{
    
            if ($proveedorc=="*")
{
    
    $var_proveedor=" ";	
}
else
{
    $var_proveedor=" gastos_t_b.id_proveedor='$proveedorc' and ";	
    }  
    
    $q_citat=("select 
                        procesos.nu_planilla,	
                        procesos.fecha_recibido,
                        procesos.id_estado_proceso,
                        gastos_t_b.id_proceso, 
                        gastos_t_b.id_proveedor,
                        gastos_t_b.id_servicio,
                        gastos_t_b.id_tipo_servicio,
                        gastos_t_b.fecha_cita,
                        servicios.servicio,
                        clinicas_proveedores.nombre,
                        count(procesos.id_proceso)
                from 
                        procesos,
                        gastos_t_b,
                        proveedores,
                        clinicas_proveedores,
                        estados_procesos,
                        titulares,
                        clientes,
                        servicios
                where 
                        gastos_t_b.id_proceso=procesos.id_proceso and 
                        procesos.id_titular=titulares.id_titular and 
                        titulares.id_cliente=clientes.id_cliente and 
                        clientes.cedula='$cedula' and 
                        procesos.id_beneficiario=0 and 
                        procesos.id_titular=$f_cliente[id_titular] and 
                        procesos.id_estado_proceso=estados_procesos.id_estado_proceso and 
                        gastos_t_b.id_proveedor=proveedores.id_proveedor and 
                        proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor and
                        $var_proveedor
                        gastos_t_b.fecha_cita>='$fechainicio' and 
                        gastos_t_b.fecha_cita<='$fechafin' and 
                        gastos_t_b.id_servicio=servicios.id_servicio
                group by
                        procesos.nu_planilla,
                        procesos.id_estado_proceso,
                        procesos.fecha_recibido,
                        gastos_t_b.id_proceso, 
                        gastos_t_b.id_proveedor,           
                        gastos_t_b.id_servicio,
                        gastos_t_b.id_tipo_servicio,
                        gastos_t_b.fecha_cita,
                        servicios.servicio,
                        clinicas_proveedores.nombre
 ");
    
    }
    else
    {
        
        if ($proveedor=="*" || $proveedor=="0")
{
    
    $var_proveedor=" ";	
}
else
{
    $var_proveedor=" gastos_t_b.id_proveedor='$proveedor' and ";	
    }   

$q_citat=("select 
                        gastos_t_b.id_proveedor,
                        gastos_t_b.nombre,
                        gastos_t_b.descripcion,
                        gastos_t_b.enfermedad,
                        procesos.id_proceso,
                        procesos.id_estado_proceso,
                        procesos.fecha_recibido,
                        estados_procesos.estado_proceso,
                        gastos_t_b.fecha_cita,
                        s_p_proveedores.direccion_prov,
                        s_p_proveedores.nomina,
                        personas_proveedores.nombres_prov,
                        personas_proveedores.apellidos_prov,
                        sucursales.sucursal,
                        especialidades_medicas.especialidad_medica
                from 
                        procesos,
                        gastos_t_b,
                        proveedores,
                        s_p_proveedores,
                        personas_proveedores,
                        estados_procesos,
                        sucursales,
                        titulares,
                        clientes ,
                        especialidades_medicas
                where 
                        gastos_t_b.id_proceso=procesos.id_proceso and 
                        procesos.id_titular=titulares.id_titular and 
                        titulares.id_cliente=clientes.id_cliente and 
                        clientes.cedula='$cedula' and 
                        procesos.id_beneficiario=0 and 
                        procesos.id_titular=$f_cliente[id_titular] and 
                        procesos.id_estado_proceso=estados_procesos.id_estado_proceso and 
                        gastos_t_b.id_proveedor=proveedores.id_proveedor and 
                        $var_proveedor 
                        proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and 
                        s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and 
                        s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica and
                        gastos_t_b.fecha_cita>='$fechainicio' and 
                        gastos_t_b.fecha_cita<='$fechafin' and 
                        s_p_proveedores.id_sucursal=sucursales.id_sucursal");
                        
        }
$r_citat=ejecutar($q_citat) ;
?>
<tr> <td colspan=9 class="titulo_seccion">  Citas Medicas</td></tr>
<tr>
				<td class="tdcamposc">Orden</td>
				<td class="tdcamposc">Fecha Emision</td>
                <td class="tdcamposc">Proveedor</td>
                <?php  if ($proveedorc>0 || $proveedorc=="*")
            {
                ?>
				<td class="tdcamposc">Servicio</td>
                <?php
                }
                else
                {
                    ?>
                <td class="tdcamposc">Especialidad</td>
                
                          <?php
                }
                ?>
                         <?php  if ($proveedorc>0 || $proveedorc=="*")
            {
                ?>
				<td class="tdcamposc">Planilla</td>
                <?php
                }
                else
                {
                    ?>
                <td class="tdcamposc">Sucursal</td>
                <?php
                }
                ?>
                <td class="tdcamposc">Fecha Cita</td>
                     <?php  if ($proveedorc>0 || $proveedorc=="*")
            {
                ?>
                    <td class="tdcamposc"></td>
                      <?php
                }
                else
                {
                    ?>
                <td class="tdcamposc">Diagnostico</td>
                <?php
                }
                ?>    
                <?php
                if ($f_admin[id_ente]==0){
                ?>    
                <td class="tdcamposc">Nomina</td>
                 <?php
                }
                ?>
				<td class="tdcamposc">Estado de la Orden</td>
     
        </tr>

<?php

while($f_citat=asignar_a($r_citat,NULL,PGSQL_ASSOC)){

?>
<tr>
				<td colspan=9 class="tdcamposcc"><hr></hr> </td>
</tr>
<tr>
				<td class="tdcamposcc"><?php echo $f_citat[id_proceso] ?> </td>
				<td class="tdcamposcc"><?php echo $f_citat[fecha_recibido] ?> </td>
                <td class="tdcamposcc"><?php echo "$f_citat[nombres_prov] $f_citat[apellidos_prov] $f_citat[nombre]"?> </td>
                  <td class="tdcamposcc"><?php echo "$f_citat[especialidad_medica] $f_citat[servicio]"?> </td>
                <td class="tdcamposcc"><?php echo "$f_citat[sucursal] $f_citat[nu_planilla] "?></td>
                <td class="tdcamposcc"><?php echo $f_citat[fecha_cita]?> </td>
                <td class="tdcamposcc"><?php echo "$f_citat[enfermedad]"?></td>
              <?php
                if ($f_admin[id_ente]==0){
                ?>   
				<td class="tdcamposcc"><?php if ($f_citat[nomina]==0) { echo "No"; }
																		else { echo "Si"; }?></td>
                <td class="tdcamposcc"><?php echo $f_citat[estado_proceso]?></td>
                <?php
                }
                else
                {
                ?>
                
				<td class="tdcamposcc"><?php 
                                                            if  ($f_citat[id_estado_proceso]==14){
                                                                
                                                                echo "ANULADO";
                                                            }
                                                            else
                                                            {
                                                            }?></td>
                <?php 
                }
                ?>
        </tr>
		


<?php
}
?>
			
<?php
/* **** fin de buscar citas titulares ***** */

 }

$q_clienteb=("select
                            clientes.nombres,
                            clientes.apellidos,
                            beneficiarios.id_titular,
                            beneficiarios.id_beneficiario,
                            estados_clientes.estado_cliente 
                    from 
                            estados_clientes,
                            clientes,
                            beneficiarios,
                            estados_t_b,
                            titulares,
                            entes
                    where 
                            clientes.cedula='$cedula' and 
                            clientes.id_cliente=beneficiarios.id_cliente and 
                            estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
                            estados_t_b.id_beneficiario>0 and 
                            estados_t_b.id_beneficiario=beneficiarios.id_beneficiario and
                            beneficiarios.id_titular=titulares.id_titular and
                            titulares.id_ente=entes.id_ente
                            $condicionente");
$r_clienteb=ejecutar($q_clienteb);
$num_filasb=num_filas($r_clienteb);

if ($num_filasb==0){
}
else
{

while($f_clienteb=asignar_a($r_clienteb,NULL,PGSQL_ASSOC)){

$q_clientet=("select 
                            clientes.id_cliente,
                            clientes.nombres,
                            clientes.apellidos,
                            clientes.cedula,
                            estados_clientes.estado_cliente,
                            titulares.id_titular,
                            titulares.id_ente,
                            entes.nombre
                    from 
                            clientes,
                            titulares,
                            estados_t_b,
                            estados_clientes,
                            entes
                    where 
                            titulares.id_titular=$f_clienteb[id_titular] and 
                            clientes.id_cliente=titulares.id_cliente and
                            titulares.id_titular=estados_t_b.id_titular and 
                            estados_t_b.id_beneficiario=0 and
                            estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
                            titulares.id_ente=entes.id_ente");

$r_clientet=ejecutar($q_clientet) or mensaje(ERROR_BD);
$f_clientet=asignar_a($r_clientet);

?>
<tr> <td colspan=9 class="titulo_seccion">  <?php echo "Este Cliente Tambien es Beneficiario"?></td></tr>


<tr>
                <td colspan=2 class="tdtitulos">Nombres y Apellidos del titular</td>
                <td colspan=2 class="tdcampos"><?php echo $f_clientet[nombres]?> <?php echo $f_clientet[apellidos]?></td>
                <td colspan=1 class="tdtitulos">Cedula Titular</td>
                <td colspan=1 class="tdcampos"><?php echo $f_clientet[cedula]?></td>
				  <td colspan=1 class="tdtitulos">Estado</td>
                <td colspan=1 class="campos1"><?php echo $f_clientet[estado_cliente]?></td>
        </tr>


        <tr>
         <td colspan=2 class="tdtitulos">Ente</td>
                <td colspan=6 class="tdcampos"><?php echo $f_clientet[nombre]?></td>
              
        </tr>

<tr>
                <td colspan=2 class="tdtitulos">Nombres y Apellidos del Beneficiario</td>
                <td colspan=2 class="tdcampos" ><?php echo $f_clienteb[nombres]?> <?php echo $f_clienteb[apellidos]?></td>
				 <td colspan=1 class="tdtitulos"></td>
                <td colspan=1 class="tdcamposr"></td>
                <td colspan=1 class="tdtitulos">Estado</td>
                <td colspan=1 class="tdcamposr"><?php echo $f_clienteb[estado_cliente]?></td>
        </tr>

<?php

if ($proveedorc>0 || $proveedorc=="*")
{
    
            if ($proveedorc=="*")
{
    
    $var_proveedor="";	
}
else
{
    $var_proveedor="gastos_t_b.id_proveedor='$proveedorc' and";	
    }  

$q_citab=("select  
                        procesos.nu_planilla,	
                        procesos.fecha_recibido,
                        procesos.id_estado_proceso,
                        gastos_t_b.id_proceso, 
                        gastos_t_b.id_proveedor,
                        gastos_t_b.id_servicio,
                        gastos_t_b.id_tipo_servicio,
                        gastos_t_b.fecha_cita,
                        servicios.servicio,
                        clinicas_proveedores.nombre,
                        count(procesos.id_proceso)
                from 
                        procesos,
                        gastos_t_b,
                        proveedores,
                        clinicas_proveedores,
                        estados_procesos,
                        beneficiarios,
                        clientes,
                        servicios
                where 
                        gastos_t_b.id_proceso=procesos.id_proceso and 
                        procesos.id_beneficiario=beneficiarios.id_beneficiario and 
                        beneficiarios.id_cliente=clientes.id_cliente and 
                        clientes.cedula='$cedula' and 
                        procesos.id_beneficiario=$f_clienteb[id_beneficiario] and 
                        procesos.id_estado_proceso=estados_procesos.id_estado_proceso and 
                        gastos_t_b.id_proveedor=proveedores.id_proveedor and 
                        $var_proveedor
                        proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor and 
                        gastos_t_b.fecha_cita>='$fechainicio' and 
                        gastos_t_b.fecha_cita<='$fechafin' and  
                        gastos_t_b.id_servicio=servicios.id_servicio
          group by
                        procesos.nu_planilla,
                        procesos.id_estado_proceso,
                        procesos.fecha_recibido,
                        gastos_t_b.id_proceso, 
                        gastos_t_b.id_proveedor,           
                        gastos_t_b.id_servicio,
                        gastos_t_b.id_tipo_servicio,
                        gastos_t_b.fecha_cita,
                        servicios.servicio,
                        clinicas_proveedores.nombre");
                        
                        
                         }
    else
    {
        
        if ($proveedor=="*" || $proveedor=="0")
{
    
    $var_proveedor=" ";	
}
else
{
    $var_proveedor="gastos_t_b.id_proveedor='$proveedor' and";	
    }   
    
    
$q_citab=("select 
                        procesos.id_titular,
                        procesos.id_beneficiario,
                        gastos_t_b.id_proveedor,
                        gastos_t_b.nombre,
                        gastos_t_b.descripcion,
                        gastos_t_b.enfermedad,
                        procesos.id_proceso,
                        procesos.fecha_recibido,
                        procesos.id_estado_proceso,
                        estados_procesos.estado_proceso,
                        gastos_t_b.fecha_cita,
                        s_p_proveedores.direccion_prov,
                        s_p_proveedores.nomina,
                        personas_proveedores.nombres_prov,
                        personas_proveedores.apellidos_prov,
                        sucursales.sucursal,
                        especialidades_medicas.especialidad_medica
                from 
                        procesos,
                        gastos_t_b,
                        proveedores,
                        s_p_proveedores,
                        personas_proveedores,
                        estados_procesos,
                        sucursales,
                        beneficiarios,
                        clientes,
                        especialidades_medicas
                where 
                        gastos_t_b.id_proceso=procesos.id_proceso and 
                        procesos.id_beneficiario=beneficiarios.id_beneficiario and 
                        beneficiarios.id_cliente=clientes.id_cliente and 
                        clientes.cedula='$cedula' and 
                        procesos.id_beneficiario=$f_clienteb[id_beneficiario] and 
                        procesos.id_estado_proceso=estados_procesos.id_estado_proceso and 
                        gastos_t_b.id_proveedor=proveedores.id_proveedor and 
                        $var_proveedor
                        proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and 
                        s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and 
                        s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica and 
                        gastos_t_b.fecha_cita>='$fechainicio' and 
                        gastos_t_b.fecha_cita<='$fechafin' and  
                        s_p_proveedores.id_sucursal=sucursales.id_sucursal");
                        
        }
                        
                        
$r_citab=ejecutar($q_citab) or mensaje(ERROR_BD);

?>


<tr> <td colspan=9 class="titulo_seccion">  Citas Medicas</td></tr>
<tr>
				<td class="tdcamposc">Orden</td>
				<td class="tdcamposc">Fecha Emision</td>
                <td class="tdcamposc">Proveedor</td>
                <?php  if ($proveedorc>0 || $proveedorc=="*")
            {
                ?>
				<td class="tdcamposc">Servicio</td>
                <?php
                }
                else
                {
                    ?>
                <td class="tdcamposc">Especialidad</td>
                
                          <?php
                }
                ?>
                         <?php  if ($proveedorc>0 || $proveedorc=="*")
            {
                ?>
				<td class="tdcamposc">Planilla</td>
                <?php
                }
                else
                {
                    ?>
                <td class="tdcamposc">Sucursal</td>
                <?php
                }
                ?>
                <td class="tdcamposc">Fecha Cita</td>
                     <?php  if ($proveedorc>0 || $proveedorc=="*")
            {
                ?>
                    <td class="tdcamposc"></td>
                      <?php
                }
                else
                {
                    ?>
                <td class="tdcamposc">Diagnostico</td>
                <?php
                }
                ?>    
                <?php
                if ($f_admin[id_ente]==0){
                ?>    
                <td class="tdcamposc">Nomina</td>
                 <?php
                }
                ?>
				<td class="tdcamposc">Estado de la Orden</td>
     
        </tr>

<?php

while($f_citab=asignar_a($r_citab,NULL,PGSQL_ASSOC)){

?>
<tr>
				<td colspan=9 class="tdcamposcc"><hr></hr> </td>
</tr>

<tr>
				<td class="tdcamposcc"><?php echo $f_citab[id_proceso]?> </td>
                <td class="tdcamposcc"><?php echo $f_citab[fecha_recibido]?></td>
                <td class="tdcamposcc"><?php echo "$f_citab[nombres_prov] $f_citab[apellidos_prov]"?> </td>
                <td class="tdcamposcc"><?php echo "$f_citab[especialidad_medica]"?> </td>
                <td class="tdcamposcc"><?php echo $f_citab[sucursal]?></td>
                <td class="tdcamposcc"><?php echo $f_citab[fecha_cita]?> </td>
                <td class="tdcamposcc"><?php echo $f_citab[enfermedad]?></td>
              <?php
                if ($f_admin[id_ente]==0){
                ?>   
				<td class="tdcamposcc"><?php if ($f_citab[nomina]==0) { echo "No"; }
																		else { echo "Si"; }?></td>
                <td class="tdcamposcc"><?php echo $f_citab[estado_proceso]?></td>
                <?php
                }
                else
                {
                ?>
                
				<td class="tdcamposcc"><?php 
                                                            if  ($f_citab[id_estado_proceso]==14){
                                                                
                                                                echo "ANULADO";
                                                            }
                                                            else
                                                            {
                                                            }?></td>
                <?php 
                }
                ?>
        </tr>

<?php
}
?>
<?php

}

}

?>

<?php
}
else
{
if ($titular==0)
{

/* **** repita para beneficiario **** */

while($f_clienteb=asignar_a($r_clienteb,NULL,PGSQL_ASSOC)){

$q_clientet=("select 
                            clientes.telefono_hab,
                            clientes.telefono_otro,
                            clientes.id_cliente,
                            clientes.nombres,
                            clientes.apellidos,
                            clientes.cedula,
                            estados_clientes.estado_cliente,
                            titulares.id_titular,
                            titulares.id_ente,
                            entes.nombre 
                    from
                            clientes,
                            titulares,
                            estados_t_b,
                            estados_clientes,
                            entes
                where 
                            titulares.id_titular=$f_clienteb[id_titular] and 
                            clientes.id_cliente=titulares.id_cliente and 
                            titulares.id_titular=estados_t_b.id_titular and 
                            estados_t_b.id_beneficiario=0 and 
                            estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
                            titulares.id_ente=entes.id_ente");

$r_clientet=ejecutar($q_clientet) or mensaje(ERROR_BD);
$f_clientet=asignar_a($r_clientet);

?>
<tr> <td colspan=9 class="titulo_seccion">  <?php echo  "Datos del Cliente como Beneficiario"?><input  type="hidden" size="10"  name="id_cobertura_t_b" class="campos" maxlength="10" value=<?php echo $f_cobertura[id_cobertura_t_b]; ?>></td></tr>

<tr>
                <td colspan=2 class="tdtitulos">Nombres y Apellidos del titular</td>
                <td colspan=2 class="tdcampos"><?php echo $f_clientet[nombres]?> <?php echo $f_clientet[apellidos]?></td>
                <td colspan=1 class="tdtitulos">Cedula Titular</td>
                <td colspan=1 class="tdcampos"><?php echo $f_clientet[cedula]?></td>
				<td colspan=1 class="tdtitulos">Estado</td>
                <td colspan=1 class="tdcamposr"><?php echo $f_clientet[estado_cliente]?></td>
        </tr>


        <tr>
				<td colspan=2 class="tdtitulos">Ente</td>
                <td colspan=2 class="tdcampos"><?php echo $f_clientet[nombre]?></td>
				<td colspan=1 class="tdtitulos">Telefono</td>
                <td colspan=2 class="tdcampos"><?php echo $f_clientet[telefono_hab]?></td>
				
                
        </tr>

<tr>
                <td colspan=2 class="tdtitulos">Nombres y Apellidos del Beneficiario</td>
                <td colspan=2 class="tdcampos"><?php echo $f_clienteb[nombres]?> <?php echo $f_clienteb[apellidos]?></td>
				<td colspan=1 class="tdtitulos">Telefono</td>
                <td colspan=1 class="tdcampos"><?php echo $f_clienteb[telefono_hab]?></td>
                <td colspan=1 class="tdtitulos">Estado</td>
                <td colspan=1 class="tdcamposr"><?php echo $f_clienteb[estado_cliente]?></td>
        </tr>


<?php

if ($proveedorc>0 || $proveedorc=="*")
{
    
            if ($proveedorc=="*")
{
    
    $var_proveedor=" ";	
}
else
{
    $var_proveedor="gastos_t_b.id_proveedor='$proveedorc' and";	
    }  

$q_citab=("select  
                        procesos.nu_planilla,	
                        procesos.fecha_recibido,
                        procesos.id_estado_proceso,
                        gastos_t_b.id_proceso, 
                        gastos_t_b.id_proveedor,
                        gastos_t_b.id_servicio,
                        gastos_t_b.id_tipo_servicio,
                        gastos_t_b.fecha_cita,
                        servicios.servicio,
                        clinicas_proveedores.nombre,
                        count(procesos.id_proceso)
                from 
                        procesos,
                        gastos_t_b,
                        proveedores,
                        clinicas_proveedores,
                        estados_procesos,
                        beneficiarios,
                        clientes,servicios
                where 
                        gastos_t_b.id_proceso=procesos.id_proceso and 
                        procesos.id_beneficiario=beneficiarios.id_beneficiario and 
                        beneficiarios.id_cliente=clientes.id_cliente and 
                        clientes.cedula='$cedula' and 
                        procesos.id_beneficiario=$f_clienteb[id_beneficiario] and 
                        procesos.id_estado_proceso=estados_procesos.id_estado_proceso and 
                        gastos_t_b.id_proveedor=proveedores.id_proveedor and 
                        proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor and 
                        gastos_t_b.fecha_cita>='$fechainicio' and 
                        gastos_t_b.fecha_cita<='$fechafin' and 
 gastos_t_b.id_servicio=servicios.id_servicio
          group by
                        procesos.nu_planilla,
                        procesos.id_estado_proceso,
                        procesos.fecha_recibido,
                        gastos_t_b.id_proceso, 
                        gastos_t_b.id_proveedor,           
                        gastos_t_b.id_servicio,
                        gastos_t_b.id_tipo_servicio,
                        gastos_t_b.fecha_cita,
                        servicios.servicio,
                        clinicas_proveedores.nombre;

select  
                        procesos.nu_planilla,	
                        procesos.fecha_recibido,
                        procesos.id_estado_proceso,
                        gastos_t_b.id_proceso, 
                        gastos_t_b.id_proveedor,
                        gastos_t_b.id_servicio,
                        gastos_t_b.id_tipo_servicio,
                        gastos_t_b.fecha_cita,
                        servicios.servicio,
                        clinicas_proveedores.nombre,
                        count(procesos.id_proceso)
                from 
                        procesos,
                        gastos_t_b,
                        proveedores,
                        clinicas_proveedores,
                        estados_procesos,
                        beneficiarios,
                        clientes,
                        servicios
                where 
                        gastos_t_b.id_proceso=procesos.id_proceso and 
                        procesos.id_beneficiario=beneficiarios.id_beneficiario and 
                        beneficiarios.id_cliente=clientes.id_cliente and 
                        clientes.cedula='$cedula' and 
                        procesos.id_beneficiario=$f_clienteb[id_beneficiario] and 
                        procesos.id_estado_proceso=estados_procesos.id_estado_proceso and 
                        gastos_t_b.id_proveedor=proveedores.id_proveedor and 
                        $var_proveedor
                        proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor and 
                        gastos_t_b.fecha_cita>='$fechainicio' and 
                        gastos_t_b.fecha_cita<='$fechafin' and
                        gastos_t_b.id_servicio=servicios.id_servicio
          group by
                        procesos.nu_planilla,
                        procesos.id_estado_proceso,
                        procesos.fecha_recibido,
                        gastos_t_b.id_proceso, 
                        gastos_t_b.id_proveedor,           
                        gastos_t_b.id_servicio,
                        gastos_t_b.id_tipo_servicio,
                        gastos_t_b.fecha_cita,
                        servicios.servicio,
                        clinicas_proveedores.nombre");
                        
                        
                         }
    else
    {
        
        if ($proveedor=="*" || $proveedor=="0")
{
    
    $var_proveedor="";	
}
else
{
    $var_proveedor="gastos_t_b.id_proveedor='$proveedor' and";	
    }  

$q_citab=("select 
                        procesos.id_titular,
                        procesos.id_beneficiario,
                        gastos_t_b.id_proveedor,
                        gastos_t_b.nombre,
                        gastos_t_b.descripcion,
                        gastos_t_b.enfermedad,
                        procesos.id_proceso,
                        procesos.fecha_recibido,
                        procesos.id_estado_proceso,
                        estados_procesos.estado_proceso,
                        gastos_t_b.fecha_cita,
                        s_p_proveedores.direccion_prov,
                        s_p_proveedores.nomina,
                        personas_proveedores.nombres_prov,
                        personas_proveedores.apellidos_prov,
                        sucursales.sucursal,
                        especialidades_medicas.especialidad_medica 
                    from 
                        procesos,
                        gastos_t_b,
                        proveedores,
                        s_p_proveedores,
                        personas_proveedores,
                        estados_procesos,
                        sucursales,
                        beneficiarios,
                        clientes,
                        especialidades_medicas
                    where 
                        gastos_t_b.id_proceso=procesos.id_proceso and 
                        procesos.id_beneficiario=beneficiarios.id_beneficiario and 
                        beneficiarios.id_cliente=clientes.id_cliente and 
                        clientes.cedula='$cedula' and 
                        procesos.id_beneficiario=$f_clienteb[id_beneficiario] and 
                        procesos.id_estado_proceso=estados_procesos.id_estado_proceso and 
                        gastos_t_b.id_proveedor=proveedores.id_proveedor and 
                        $var_proveedor
                        proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and 
                        s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor  and 
                        s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica and 
                        gastos_t_b.fecha_cita>='$fechainicio' and 
                        gastos_t_b.fecha_cita<='$fechafin' and 
                        s_p_proveedores.id_sucursal=sucursales.id_sucursal");
}
$r_citab=ejecutar($q_citab) or mensaje(ERROR_BD);

?>


<tr> <td colspan=9 class="titulo_seccion">  Citas Medicas</td></tr>
<tr>
				<td class="tdcamposc">Orden</td>
				<td class="tdcamposc">Fecha Emision</td>
                <td class="tdcamposc">Proveedor</td>
                <?php  if ($proveedorc>0 || $proveedorc=="*")
            {
                ?>
				<td class="tdcamposc">Servicio</td>
                <?php
                }
                else
                {
                    ?>
                <td class="tdcamposc">Especialidad</td>
                
                          <?php
                }
                ?>
                         <?php  if ($proveedorc>0 || $proveedorc=="*")
            {
                ?>
				<td class="tdcamposc">Planilla</td>
                <?php
                }
                else
                {
                    ?>
                <td class="tdcamposc">Sucursal</td>
                <?php
                }
                ?>
                <td class="tdcamposc">Fecha Cita</td>
                     <?php  if ($proveedorc>0 || $proveedorc=="*")
            {
                ?>
                    <td class="tdcamposc"></td>
                      <?php
                }
                else
                {
                    ?>
                <td class="tdcamposc">Diagnostico</td>
                <?php
                }
                ?>    
                <?php
                if ($f_admin[id_ente]==0){
                ?>    
                <td class="tdcamposc">Nomina</td>
                 <?php
                }
                ?>
				<td class="tdcamposc">Estado de la Orden</td>
     
        </tr>

<?php

while($f_citab=asignar_a($r_citab,NULL,PGSQL_ASSOC)){

?>
<tr>
				<td colspan=9 class="tdcamposcc"><hr></hr> </td>
</tr>
<tr>
				<td class="tdcamposcc"><?php echo $f_citab[id_proceso]?> </td>
                <td class="tdcamposcc"><?php echo $f_citab[fecha_recibido]?></td>
                <td class="tdcamposcc"><?php echo "$f_citab[nombres_prov] $f_citab[apellidos_prov] $f_citab[nombre]"?> </td>
                <td class="tdcamposcc"><?php echo "$f_citab[especialidad_medica] $f_citab[servicio]"?> </td>
                <td class="tdcamposcc"><?php echo "$f_citab[sucursal] $f_citab[nu_planilla]"?></td>
                <td class="tdcamposcc"><?php echo $f_citab[fecha_cita]?> </td>
                <td class="tdcamposcc"><?php echo $f_citab[enfermedad]?></td>
                   <?php
                if ($f_admin[id_ente]==0){
                ?>   
				<td class="tdcamposcc"><?php if ($f_citab[nomina]==0) { echo "No"; }
																		else { echo "Si"; }?></td>
                <td class="tdcamposcc"><?php echo $f_citab[estado_proceso]?></td>
                <?php
                }
                else
                {
                ?>
                
				<td class="tdcamposcc"><?php 
                                                            if  ($f_citab[id_estado_proceso]==14){
                                                                
                                                                echo "ANULADO";
                                                            }
                                                            else
                                                            {
                                                            }?></td>
                <?php 
                }
                ?>
        </tr>
		
		
<?php
}
?>

<?php

}
?>
			
</table>


<?php
}
}
?>

<?php
}
?>

