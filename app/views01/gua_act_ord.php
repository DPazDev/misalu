<?php
include ("../../lib/jfunciones.php");

$fechare=$_REQUEST['fechare'];
$fecharci=$_REQUEST['fecharci'];
$fecharfi=$_REQUEST['fecharfi'];
$fecharefi=$_REQUEST['fecharefi'];
$enfermedad=strtoupper($_REQUEST['enfermedad']);
$comenope=strtoupper($_REQUEST['comenope']);
$comenger=strtoupper($_REQUEST['comenger']);
$comenmed=strtoupper($_REQUEST['comenmed']);
$preforma1=strtoupper($_REQUEST['preforma1']);
$conexa=$_REQUEST['conexa'];
$honorariosr1=$_REQUEST['honorariosr1'];
$descri1=strtoupper($_REQUEST['descri1']);
$honorarios1=$_REQUEST['honorarios1'];
$servicio=$_REQUEST['servicio'];
$id_proveedor=$_REQUEST['id_proveedor'];
$nombre1=strtoupper($_REQUEST['nombre1']);
$tiposerv=$_REQUEST['tiposerv'];
$proceso=$_REQUEST['proceso'];
$facturaf=$_REQUEST['facturaf'];
$controlf=strtoupper($_REQUEST['controlf']);
$clave=strtoupper($_REQUEST['clave']);
$horac=strtoupper($_REQUEST['horac']);
$estado_proceso=$_REQUEST['estado_proceso'];
$honorariosr1 =$_REQUEST['honorariosr1'];
$idgasto1 =$_REQUEST['idgasto1'];
$idorgano1 =$_REQUEST['idorgano1'];
$fcreado1 =$_REQUEST['fcreado1'];
$hcreado1 =$_REQUEST['hcreado1'];
$idcobertura1 =$_REQUEST['idcobertura1'];
$idtipos1 =$_REQUEST['idtipos1'];
$idservicio1 =$_REQUEST['idservicio1'];
$retencion1 =$_REQUEST['retencion1'];
$preforma1 =$_REQUEST['preforma1'];
$nu_planilla =$_REQUEST['nu_planilla'];
$deducible =$_REQUEST['deducible'];
$fechap =$_REQUEST['fechap'];
$monto =$_REQUEST['monto'];
$unidades1 =$_REQUEST['unidades1'];
$fechacon1 =$_REQUEST['fechacon1'];
$fechaconi1 =$_REQUEST['fechaconi1'];
$continuo1 =$_REQUEST['continuo1'];
$idinsumo1 =$_REQUEST['idinsumo1'];
$iddependencia1 =$_REQUEST['iddependencia1'];
$admin= $_SESSION['id_usuario_'.empresa];

$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);




if ($servicio==9 || $servicio==6 || $servicio==14 || $f_admin[id_tipo_admin]==14){
    }
    else
    {
        $nu_planilla='0';
        
        }


/* **** busco si el usuario registra factura**** */
$q_factura="select * from tbl_003 where tbl_003.id_modulo='4' and tbl_003.id_usuario='$admin'";
$r_factura=ejecutar($q_factura);
$num_filasf=num_filas($r_factura);
/* **** fin  busco si el usuario registra factura**** */

$q_ente="select procesos.id_estado_proceso,entes.* from procesos,entes,titulares where entes.id_ente=titulares.id_ente and titulares.id_titular=procesos.id_titular and procesos.id_proceso='$proceso'";
$r_ente=ejecutar($q_ente);
$f_ente=asignar_a($r_ente);

if ($fecharfi=="")
{
$fecharfi="1900-01-01";
}
if ($fecharefi=="")
{
$fecharefi="1900-01-01";
}
if ($fecharci=="")
{
$fecharci="1900-01-01";
}
if ($fechap=="")
{
$fechap="1900-01-01";
}

$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");



/* **** verificar numero de presupuesto o planilla **** */

if ($nu_planilla>0 || $clave>0){
    
$q_cobertura="select * from procesos where 
procesos.id_proceso='$proceso'"; 
$r_cobertura=ejecutar($q_cobertura);
$f_cobertura=asignar_a($r_cobertura);
    if ($nu_planilla>0){
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
                                procesos.nu_planilla='$nu_planilla' and 
                                procesos.id_titular=titulares.id_titular and 
                                titulares.id_cliente=clientes.id_cliente and
                                procesos.id_admin=admin.id_admin and 
titulares.id_ente=entes.id_ente");
$r_cliente=ejecutar($q_cliente);
$num_filas=num_filas($r_cliente);

/* **** se verifica si el numero de planilla esta asignado a un mismo cliente **** */
		while($f_cliente=asignar_a($r_cliente,NULL,PGSQL_ASSOC))
        {
            
            if (($f_cliente[id_titular]==$f_cobertura[id_titular]) and ($f_cliente[id_beneficiario]==$f_cobertura[id_beneficiario]))
            {
                }
                else
                {
                    $malregistro++;
                }
        }
        
}
  if ($clave>0){
/* **** busco el id_cliente y procesos que tengan este numero de clave registrado **** */
$q_cliente_c=("select 
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
                                procesos.no_clave='$clave' and 
                                procesos.id_titular=titulares.id_titular and 
                                titulares.id_cliente=clientes.id_cliente and
                                procesos.id_admin=admin.id_admin and 
titulares.id_ente=entes.id_ente");
$r_cliente_c=ejecutar($q_cliente_c);
$num_filas_c=num_filas($r_cliente_c);


        /* **** se verifica si el numero de calve esta asignado a un mismo cliente **** */
		while($f_cliente_c=asignar_a($r_cliente_c,NULL,PGSQL_ASSOC))
        {
            
            if (($f_cliente_c[id_titular]==$f_cobertura[id_titular]) and ($f_cliente_c[id_beneficiario]==$f_cobertura[id_beneficiario]))
            {
                }
                else
                {
                    $malregistro_c++;
                }
        }
               
     }           
                
                if ($malregistro>0 || $malregistro_c>0)
                {
                    
              if ($malregistro>0) {
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
                                procesos.nu_planilla='$nu_planilla' and 
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


<tr>		<td colspan=6 class="titulo_seccion">No esta  Asignado el Numero de Planilla o Presupuesto </td>	</tr>	
    </table>
    
  <?php  }
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
}
                if ($malregistro_c>0) {
              /* **** busco el id_cliente y procesos que tengan este numero de clave registrado **** */
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


<tr>		<td colspan=7 class="titulo_seccion">
No Se Actualizo el Proceso ya que el Numero de Clave Asignado al Siguiente Usuario Verificar a Quien le Pertenece  la Clave y Asignarla a un Solo Usuario.
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
}
              
                    
                    
                    
                    }
                    
                    else
                    {
                        
                        

if ($estado_proceso==13)
{

	/* **** Actualizo el proceso **** */
$mod_proceso="update procesos set fecha_recibido='$fechare',fecha_modificado='$fechacreado',hora_modificado='$hora',comentarios='$comenope',factura_final='$facturaf',comentarios_gerente='$comenger',comentarios_medico='$comenmed',monto_temporal='$monto',fecha_factura_final='$fecharfi',no_clave='$clave',nu_planilla='$nu_planilla',fecha_ent_pri='$fechap',fecha_emision_factura='$fecharefi' where procesos.id_proceso='$proceso'";
$fmod_proceso=ejecutar($mod_proceso);
	}

else
{


/* **** agarro los id_cobertura que pueda tener el proceso para luego de haber modificado los gastos actualizar las coberturas **** */
$q_actualizar="select gastos_t_b.id_cobertura_t_b,count(coberturas_t_b.id_cobertura_t_b) from coberturas_t_b,gastos_t_b where coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso=$proceso  group by gastos_t_b.id_cobertura_t_b";


if ($id_proveedor==0)
{
	$id_proveedor==0;
}
else
{

/* **** Buscamos las Especialidad **** */
 $q_especialidad="select * from especialidades_medicas,s_p_proveedores,proveedores where
especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and
s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
proveedores.id_proveedor='$id_proveedor'";
$r_especialidad=ejecutar($q_especialidad);
$f_especialidad=asignar_a($r_especialidad);
$especialidad=$f_especialidad[especialidad_medica]; 
/* **** Fin de Buscar Especialidad **** */

}

$q_cobertura="select * from entes,titulares,coberturas_t_b,gastos_t_b where 
 entes.id_ente=titulares.id_ente and
titulares.id_titular=coberturas_t_b.id_titular and
coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso=$proceso"; 
$r_cobertura=ejecutar($q_cobertura);
$num_filas=num_filas($r_cobertura);

if ($num_filas == 0) {

$id_cobertura=0;
}
else
{
	$f_cobertura=asignar_a($r_cobertura);

if ($f_cobertura[id_beneficiario]==0){
$fechainicio="$f_cobertura[fecha_inicio_contrato]";
$fechafinal="$f_cobertura[fecha_renovacion_contrato]";
}
else
{
$fechainicio="$f_cobertura[fecha_inicio_contratob]";
$fechafinal="$f_cobertura[fecha_renovacion_contratob]";
}
}

$descri2=split("@",$descri1);
$nombre2=split("@",$nombre1);
$honorarios2=split("@",$honorarios1);
$honorariosr2=split("@",$honorariosr1);
$preforma2=split("@",$preforma1);
$idgasto2 =split("@",$idgasto1);
$idorgano2 =split("@",$idorgano1);
$idcobertura2 =split("@",$idcobertura1);
$idtipos2 =split("@",$idtipos1);
$idservicio2 =split("@",$idservicio1);
$retencion2 =split("@",$retencion1);
$unidades2 =split("@",$unidades1);
$fechacon2 =split("@",$fechacon1);
$fechaconi2 =split("@",$fechaconi1);
$continuo2 =split("@",$continuo1);
$idinsumo2 =split("@",$idinsumo1);
$iddependencia2 =split("@",$iddependencia1);


$q="
begin work;
delete from gastos_t_b where id_proceso='$proceso';
";
for($i=0;$i<=$conexa;$i++){


	$descri =$descri2[$i];
	$nombre =$nombre2[$i];
	$monto =$honorarios2[$i];
	$honorariosr =$honorariosr2[$i];
	$preforma = $preforma2[$i];
	$idgasto =$idgasto2[$i];
	$idorgano =$idorgano2[$i];
	$idcobertura =$idcobertura2[$i];
	$idtipos =$idtipos2[$i];
	$idservicio =$idservicio2[$i];
	$retencion =$retencion2[$i];
	$unidades =$unidades2[$i];
	$fechacon =$fechacon2[$i];
	$fechaconi =$fechaconi2[$i];
	$continuo =$continuo2[$i];
	$idinsumo =$idinsumo2[$i];
	$iddependencia =$iddependencia2[$i];
	
	if(!empty($idgasto) && $idgasto>0){

/* echo $proceso; 
echo "  **  ";
echo $idinsumo;
echo "  **  ";
echo $idorgano;
echo "  **  ";
echo $nombre;
echo "  **  ";
echo $descri;
echo "  **  ";
echo $fechacreado;
echo "  **  ";
echo $hora;
echo "  **  ";
echo $idcobertura;
echo "  **  ";
echo $enfermedad;
echo "  **  ";
echo $id_proveedor;
echo "  **  ";
echo $idtipos;
echo "  **  ";
echo $idservicio;
echo "  **  ";
echo $preforma;
echo "  **  ";
echo $honorariosr;
echo "  **  ";
echo $monto;
echo "  **  ";
echo $monto;
echo "  **  ";
echo $retencion;
echo "  **  ";
echo $fecharci;
echo "  **  ";
echo $horac;
echo "  **  ";
echo $continuo;
echo "  **  ";
echo $unidades;
echo "  **  ";
echo $fechacon;
echo "  **  ";
echo $iddependencia;*/

if ($fechaconi>"1900-01-01" and $servicio==1)
{
	$fecharci=$fechaconi;
	}
			$q.="
insert into gastos_t_b (id_proceso,id_insumo,id_organo,nombre,
descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,
id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita,hora_cita,
continuo,unidades,fecha_continuo,id_dependencia) 
values ('$proceso','$idinsumo','$idorgano','$nombre','$descri','$fechacreado','$hora',
'$idcobertura','$enfermedad','$id_proveedor','$idtipos','$idservicio','$preforma',
'$honorariosr','$monto','$monto','$retencion','$fecharci','$horac','$continuo','$unidades',
'$fechacon','$iddependencia');";

}
}
$q.="
commit work;
";
$r=ejecutar($q);


if ($tiposerv!=5)
{

 /* **** Actualizo la cobertura**** */
$r_actualizar=ejecutar($q_actualizar);
while($f_actualizar=asignar_a($r_actualizar,NULL,PGSQL_ASSOC)){

$monto_actua=0;
$monto_gastos=0;
$q_propiedad="select * from propiedades_poliza,coberturas_t_b
where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
coberturas_t_b.id_cobertura_t_b=$f_actualizar[id_cobertura_t_b]";
$r_propiedad=ejecutar($q_propiedad);
$f_propiedad=asignar_a($r_propiedad);

$q_cgastos="select * from gastos_t_b,procesos where
gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
gastos_t_b.id_cobertura_t_b=$f_actualizar[id_cobertura_t_b]"; 
$r_cgastos=ejecutar($q_cgastos);
while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
$monto_gastos= $monto_gastos +
$f_cgastos[monto_aceptado];
}

if ($f_cobertura[id_beneficiario]==0) 
{
	$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
	
	}
else
{
	
$monto_actual= $f_propiedad[monto] - $monto_gastos;

}
$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
coberturas_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]' and
coberturas_t_b.id_titular='$f_cobertura[id_titular]' and
coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
$fmod_cobertura=ejecutar($mod_cobertura);
}
/* **** Fin de Actualizar la cobertura**** */
}

/* **** Actualizo el proceso **** */
$mod_proceso="update 
                                    procesos 
                            set 
                                    id_estado_proceso='$estado_proceso' ,
                                    fecha_recibido='$fechare',
                                    fecha_modificado='$fechacreado',
                                    hora_modificado='$hora',comentarios='$comenope',
                                    factura_final='$facturaf',
                                    comentarios_gerente='$comenger',
                                    comentarios_medico='$comenmed',
                                    fecha_factura_final='$fecharfi',
                                    no_clave='$clave',nu_planilla='$nu_planilla',
                                    fecha_ent_pri='$fechap',
                                    control_factura='$controlf',
                                    fecha_emision_factura='$fecharefi',
                                    pro_deducible='$deducible'
                            where 
                                    procesos.id_proceso='$proceso'";
$fmod_proceso=ejecutar($mod_proceso);

}
/* **** Se registra lo que hizo el usuario**** **/


$log="ACTUALIZO  LA ORDEN NUMERO $proceso fecha_recibido=$fechare,fecha_modificado=$fechacreado,hora_modificado=$hora,comentarios=$comenope,factura_final=$facturaf,comentarios_gerente=$comenger,comentarios_medico=$comenmed,fecha_factura_final=$fecharfi,no_clave=$clave,nu_planilla=$nu_planilla,fecha_ent_pri=$fechap ";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */

?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $proceso?> se Actualizo con Exito <input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="<?php echo $proceso?>"   ><a href="#" 	OnClick="act_orden();" class="boton">Actualizar Orden</a><a href="#" OnClick="ir_principal();" class="boton">salir</a> </td>	
</tr>	

	
<tr>		
<td colspan=4 class="titulo_seccion">Imprimir </td>	
</tr>	

<?php
if ($estado_proceso==13){
?>

<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><?php
			$url="'views01/ireembolso.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton">Reembolso en Espera </a></td>
			
	</tr>


<?php

}
else
{

if ($servicio==1 || $servicio==10){
?>

<tr>
		<td class="tdtitulos"><?php
			$url="'views01/ireembolso.php?proceso=$proceso&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Reembolso sin Espera </a>
			<?php
			$url="'views01/irevisionc.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito </a>
			<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=0&ente=$$f_ente[nombre]'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito Ente Privado</a>
			<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=2&ente=$$f_ente[nombre]'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito Ente Privado logo mi salud</a>
			<a href="#" 	OnClick="carta_re();" class="boton">Carta Rechazo</a>
			<a href="#" 	OnClick="p_uni_gra();" class="boton">Pago Unico de Gracia</a>
			</td>
	</tr>


<?php

}
else
{
	if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13 || $servicio==10) )
	{
?>

	<tr>
		<td class="tdtitulos"><?php
			$url="'views01/irevisionc.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
			$url="'views01/icarta.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Recibo de Carta Aval </a>
			<?php
			$url="'views01/icarta.php?proceso=$proceso&si=0'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Carta Aval </a>
			
				</td>
	</tr>
	
	<?php
	}
	else
	{
	?>
<tr>
		<td class="tdtitulos"><?php
			$url="'views01/iorden.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden Con Monto </a><?php
			$url="'views01/iorden.php?proceso=$proceso&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Orden Sin Monto  </a><?php
			$url="'views01/irevision.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
			$url="'views01/iordenb.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden ente privado  </a>
			<?php
	if ($nu_planilla>=1){
		$url="'views01/isolicitudmedicamento.php?proceso=$nu_planilla&si=1'";
			?>  <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Solicitud de Medicamentos </a>
			<?php
			$url="'views01/ipresupuesto.php?proceso=$nu_planilla&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto </a>
			<?php
			$url="'views01/ipresupuestop.php?proceso=$nu_planilla&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto Ente Privado</a>
			<?php $url="'views01/ifpresupuestop.php?proceso=$nu_planilla&si=1&ente=$ente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Finiquito Presupuesto Ente Privado</a>
			<?php }
				
			if ($f_cproceso[nu_planilla]>=1 and $tiposerv==18){
			$url="'views01/icartaamb.php?proceso=$nu_planilla&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Carta Cirugia </a>
		<?php }
			?>
			
			
			
			</td>
	</tr>
<?php
}
}

if ($estado_proceso==7 || $estado_proceso==11 || $estado_proceso==16) {
?>
<tr>		
<td colspan=4 class="titulo_seccion">Imprimir Finiquitos</td>	
</tr>	
<tr>
		<td colspan=4 class="tdtitulos">
		<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito </a>
			<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=0&ente=$f_ente[nombre]'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito Ente Privado</a>
			<?php $url="'views01/ifpresupuestop.php?proceso=$nu_planilla&si=1&ente=$ente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Finiquito Presupuesto Ente Privado</a>
</tr>
<?php
}
}
if ($num_filasf>0){
?>
<tr> <td colspan=4 class="titulo_seccion"><a href="#" OnClick="reg_factura();" class="boton">Facturacion</a></td></tr>
<?php 
}
?>
</table>

<?php
                        
                        
}

/* **** fin de verificar si el proceso se le actualiza un numero de planilla**** */
}
else
{


/* **** si el numero de planilla del proceso es igual a 0 realiza lo siguiente **** */



if ($estado_proceso==13)
{

	/* **** Actualizo el proceso **** */
$mod_proceso="update procesos set fecha_recibido='$fechare',fecha_modificado='$fechacreado',hora_modificado='$hora',comentarios='$comenope',factura_final='$facturaf',comentarios_gerente='$comenger',comentarios_medico='$comenmed',monto_temporal='$monto',fecha_factura_final='$fecharfi',no_clave='$clave',nu_planilla='$nu_planilla',fecha_ent_pri='$fechap',fecha_emision_factura='$fecharefi' where procesos.id_proceso='$proceso'";
$fmod_proceso=ejecutar($mod_proceso);
	}

else
{


/* **** agarro los id_cobertura que pueda tener el proceso para luego de haber modificado los gastos actualizar las coberturas **** */
$q_actualizar="select gastos_t_b.id_cobertura_t_b,count(coberturas_t_b.id_cobertura_t_b) from coberturas_t_b,gastos_t_b where coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso=$proceso  group by gastos_t_b.id_cobertura_t_b";


if ($id_proveedor==0)
{
	$id_proveedor==0;
}
else
{

/* **** Buscamos las Especialidad **** */
 $q_especialidad="select * from especialidades_medicas,s_p_proveedores,proveedores where
especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and
s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
proveedores.id_proveedor='$id_proveedor'";
$r_especialidad=ejecutar($q_especialidad);
$f_especialidad=asignar_a($r_especialidad);
$especialidad=$f_especialidad[especialidad_medica]; 
/* **** Fin de Buscar Especialidad **** */

}

$q_cobertura="select * from entes,titulares,coberturas_t_b,gastos_t_b where 
 entes.id_ente=titulares.id_ente and
titulares.id_titular=coberturas_t_b.id_titular and
coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso=$proceso"; 
$r_cobertura=ejecutar($q_cobertura);
$num_filas=num_filas($r_cobertura);

if ($num_filas == 0) {

$id_cobertura=0;
}
else
{
	$f_cobertura=asignar_a($r_cobertura);

if ($f_cobertura[id_beneficiario]==0){
$fechainicio="$f_cobertura[fecha_inicio_contrato]";
$fechafinal="$f_cobertura[fecha_renovacion_contrato]";
}
else
{
$fechainicio="$f_cobertura[fecha_inicio_contratob]";
$fechafinal="$f_cobertura[fecha_renovacion_contratob]";
}
}

$descri2=split("@",$descri1);
$nombre2=split("@",$nombre1);
$honorarios2=split("@",$honorarios1);
$honorariosr2=split("@",$honorariosr1);
$preforma2=split("@",$preforma1);
$idgasto2 =split("@",$idgasto1);
$idorgano2 =split("@",$idorgano1);
$idcobertura2 =split("@",$idcobertura1);
$idtipos2 =split("@",$idtipos1);
$idservicio2 =split("@",$idservicio1);
$retencion2 =split("@",$retencion1);
$unidades2 =split("@",$unidades1);
$fechacon2 =split("@",$fechacon1);
$fechaconi2 =split("@",$fechaconi1);
$continuo2 =split("@",$continuo1);
$idinsumo2 =split("@",$idinsumo1);
$iddependencia2 =split("@",$iddependencia1);


$q="
begin work;
delete from gastos_t_b where id_proceso='$proceso';
";
for($i=0;$i<=$conexa;$i++){


	$descri =$descri2[$i];
	$nombre =$nombre2[$i];
	$monto =$honorarios2[$i];
	$honorariosr =$honorariosr2[$i];
	$preforma = $preforma2[$i];
	$idgasto =$idgasto2[$i];
	$idorgano =$idorgano2[$i];
	$idcobertura =$idcobertura2[$i];
	$idtipos =$idtipos2[$i];
	$idservicio =$idservicio2[$i];
	$retencion =$retencion2[$i];
	$unidades =$unidades2[$i];
	$fechacon =$fechacon2[$i];
	$fechaconi =$fechaconi2[$i];
	$continuo =$continuo2[$i];
	$idinsumo =$idinsumo2[$i];
	$iddependencia =$iddependencia2[$i];
	
	if(!empty($idgasto) && $idgasto>0){

/* echo $proceso; 
echo "  **  ";
echo $idinsumo;
echo "  **  ";
echo $idorgano;
echo "  **  ";
echo $nombre;
echo "  **  ";
echo $descri;
echo "  **  ";
echo $fechacreado;
echo "  **  ";
echo $hora;
echo "  **  ";
echo $idcobertura;
echo "  **  ";
echo $enfermedad;
echo "  **  ";
echo $id_proveedor;
echo "  **  ";
echo $idtipos;
echo "  **  ";
echo $idservicio;
echo "  **  ";
echo $preforma;
echo "  **  ";
echo $honorariosr;
echo "  **  ";
echo $monto;
echo "  **  ";
echo $monto;
echo "  **  ";
echo $retencion;
echo "  **  ";
echo $fecharci;
echo "  **  ";
echo $horac;
echo "  **  ";
echo $continuo;
echo "  **  ";
echo $unidades;
echo "  **  ";
echo $fechacon;
echo "  **  ";
echo $iddependencia;*/

if ($fechaconi>"1900-01-01" and $servicio==1)
{
	$fecharci=$fechaconi;
	}
			$q.="
insert into gastos_t_b (id_proceso,id_insumo,id_organo,nombre,
descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,id_proveedor,id_tipo_servicio,
id_servicio,factura,monto_reserva,monto_aceptado,monto_pagado,retencion,fecha_cita,hora_cita,
continuo,unidades,fecha_continuo,id_dependencia) 
values ('$proceso','$idinsumo','$idorgano','$nombre','$descri','$fechacreado','$hora',
'$idcobertura','$enfermedad','$id_proveedor','$idtipos','$idservicio','$preforma',
'$honorariosr','$monto','$monto','$retencion','$fecharci','$horac','$continuo','$unidades',
'$fechacon','$iddependencia');";

}
}
$q.="
commit work;
";
$r=ejecutar($q);


if ($tiposerv!=5)
{

 /* **** Actualizo la cobertura**** */
$r_actualizar=ejecutar($q_actualizar);
while($f_actualizar=asignar_a($r_actualizar,NULL,PGSQL_ASSOC)){

$monto_actua=0;
$monto_gastos=0;
$q_propiedad="select * from propiedades_poliza,coberturas_t_b
where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
coberturas_t_b.id_cobertura_t_b=$f_actualizar[id_cobertura_t_b]";
$r_propiedad=ejecutar($q_propiedad);
$f_propiedad=asignar_a($r_propiedad);

$q_cgastos="select * from gastos_t_b,procesos where
gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
gastos_t_b.id_cobertura_t_b=$f_actualizar[id_cobertura_t_b]"; 
$r_cgastos=ejecutar($q_cgastos);
while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
$monto_gastos= $monto_gastos +
$f_cgastos[monto_aceptado];
}

if ($f_cobertura[id_beneficiario]==0) 
{
	$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
	
	}
else
{
	
$monto_actual= $f_propiedad[monto] - $monto_gastos;

}
$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
coberturas_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]' and
coberturas_t_b.id_titular='$f_cobertura[id_titular]' and
coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
$fmod_cobertura=ejecutar($mod_cobertura);
}
/* **** Fin de Actualizar la cobertura**** */
}

/* **** Actualizo el proceso **** */

$mod_proceso="update 
                                    procesos
                            set 
                                    id_estado_proceso='$estado_proceso',
                                    fecha_recibido='$fechare',
                                    fecha_modificado='$fechacreado',
                                    hora_modificado='$hora',
                                    comentarios='$comenope',
                                    factura_final='$facturaf',
                                    comentarios_gerente='$comenger',
                                    comentarios_medico='$comenmed',
                                    fecha_factura_final='$fecharfi',
                                    no_clave='$clave',
                                    nu_planilla='$nu_planilla',
                                    fecha_ent_pri='$fechap',
                                    control_factura='$controlf',
                                    fecha_emision_factura='$fecharefi',
                                    pro_deducible='$deducible'
                            where 
                                    procesos.id_proceso='$proceso'";
$fmod_proceso=ejecutar($mod_proceso);

}
/* **** Se registra lo que hizo el usuario**** **/


$log="ACTUALIZO  LA ORDEN NUMERO $proceso fecha_recibido=$fechare,fecha_modificado=$fechacreado,hora_modificado=$hora,comentarios=$comenope,factura_final=$facturaf,comentarios_gerente=$comenger,comentarios_medico=$comenmed,fecha_factura_final=$fecharfi,no_clave=$clave,nu_planilla=$nu_planilla,fecha_ent_pri=$fechap ";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */

?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $proceso?> se Actualizo con Exito <input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="<?php echo $proceso?>"   ><a href="#" 	OnClick="act_orden();" class="boton">Actualizar Orden</a><a href="#" OnClick="ir_principal();" class="boton">salir</a> </td>	
</tr>	

	
<tr>		
<td colspan=4 class="titulo_seccion">Imprimir </td>	
</tr>	

<?php
if ($estado_proceso==13){
?>

<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><?php
			$url="'views01/ireembolso.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton">Reembolso en Espera </a></td>
			
	</tr>


<?php

}
else
{

if ($servicio==1 || $servicio==10){
?>

<tr>
		<td class="tdtitulos"><?php
			$url="'views01/ireembolso.php?proceso=$proceso&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Reembolso sin Espera </a>
			<?php
			$url="'views01/irevisionc.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito </a>
			<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=0&ente=$$f_ente[nombre]'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito Ente Privado</a>
			<a href="#" 	OnClick="carta_re();" class="boton">Carta Rechazo</a>
			<a href="#" 	OnClick="p_uni_gra();" class="boton">Pago Unico de Gracia</a>
			</td>
	</tr>


<?php

}
else
{
	if ($tiposerv==0 and ($servicio==2 || $servicio==3 || $servicio==8 || $servicio==11  || $servicio==13 || $servicio==10) )
	{
?>

	<tr>
		<td class="tdtitulos"><?php
			$url="'views01/irevisionc.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
			$url="'views01/icarta.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Recibo de Carta Aval </a>
			<?php
			$url="'views01/icarta.php?proceso=$proceso&si=0'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Carta Aval </a>
			
				</td>
	</tr>
	
	<?php
	}
	else
	{
	?>
<tr>
		<td class="tdtitulos"><?php
			$url="'views01/iorden.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden Con Monto </a><?php
			$url="'views01/iorden.php?proceso=$proceso&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Orden Sin Monto  </a><?php
			$url="'views01/irevision.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
			$url="'views01/iordenb.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden ente privado  </a>
			<?php
	if ($nu_planilla>=1){
			
				$url="'views01/isolicitudmedicamento.php?proceso=$nu_planilla&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Solicitud de Medicamentos </a>
			<?php
			$url="'views01/ipresupuesto.php?proceso=$nu_planilla&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto </a>
			<?php
			$url="'views01/ipresupuestop.php?proceso=$nu_planilla&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto Ente Privado</a>
			
			<?php $url="'views01/ifpresupuestop.php?proceso=$nu_planilla&si=1&ente=$ente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Finiquito Presupuesto Ente Privado</a>
			<?php }
				
			if ($f_cproceso[nu_planilla]>=1 and $tiposerv==18){
			$url="'views01/icartaamb.php?proceso=$nu_planilla&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Carta Cirugia </a>
		<?php }
			?>
			
			
			
			</td>
	</tr>
<?php
}
}

if ($estado_proceso==7 || $estado_proceso==11 || $estado_proceso==16) {
?>
<tr>		
<td colspan=4 class="titulo_seccion">Imprimir Finiquitos</td>	
</tr>	
<tr>
		<td colspan=4 class="tdtitulos">
		<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito </a>
			<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=0&ente=$f_ente[nombre]'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito Ente Privado</a>
			<?php $url="'views01/ifpresupuestop.php?proceso=$nu_planilla&si=1&ente=$ente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Finiquito Presupuesto Ente Privado</a>
</tr>
<?php
}
}
if ($num_filasf>0){
?>
<tr> <td colspan=4 class="titulo_seccion"><a href="#" OnClick="reg_factura();" class="boton">Facturacion</a></td></tr>
<?php 
}
?>
</table>

<?php
}
?>




