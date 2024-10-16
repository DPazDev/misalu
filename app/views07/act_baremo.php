<?php
include ("../../lib/jfunciones.php");
$tbaremo=$_REQUEST['tbaremo'];
$baremo=$_REQUEST['baremo'];
$conexa=$_REQUEST['conexa'];
$id_imagenologia1=$_REQUEST['id_imagenologia1'];
$imagenologia1=$_REQUEST['imagenologia1'];
$honorarios1=$_REQUEST['honorarios1'];
$honorarios_pri1=$_REQUEST['honorarios2'];

$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
$admin= $_SESSION['id_usuario_'.empresa];

/*echo $id_cobertura;
echo "**1**";
echo $monto;
echo "**2**";
echo $id_proveedor;
echo "**3**";
*/

$q_admin="select * from admin where id_admin=$admin";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);


$id_imagenologia2=split("@",$id_imagenologia1);
$imagenologia2=split("@",$imagenologia1);
$honorarios2=split("@",$honorarios1);
$honorarios_pri2=split("@",$honorarios_pri1);
	

$q="
begin work;
";
	if  ($tbaremo==0)
{
for($i=0;$i<=$conexa;$i++){

	$id_imagenologia=$id_imagenologia2[$i];
	$imagenologia=$imagenologia2[$i];
	$honorarios=$honorarios2[$i];
	$honorarios_pri=$honorarios_pri2[$i];


	
	if(!empty($id_imagenologia) && $id_imagenologia>0){

		$q.="update imagenologia_bi set imagenologia_bi=upper('$imagenologia') ,honorarios='$honorarios',hono_privados='$honorarios_pri' where  imagenologia_bi.id_imagenologia_bi='$id_imagenologia' ;";
	}
	
	}
$q.="
commit work;
";
$r=ejecutar($q);

}

	if  ($tbaremo==1)
{
	for($i=0;$i<=$conexa;$i++){

	$id_imagenologia=$id_imagenologia2[$i];
	$imagenologia=$imagenologia2[$i];
	$honorarios=$honorarios2[$i];
	$honorarios_pri=$honorarios_pri2[$i];

	
	if(!empty($id_imagenologia) && $id_imagenologia>0){

		$q.="update examenes_bl set examen_bl=upper('$imagenologia'),honorarios='$honorarios',hono_privados='$honorarios_pri' where  examenes_bl.id_examen_bl='$id_imagenologia' ;";
	}


	}
$q.="
commit work;
";
$r=ejecutar($q);

}

	if  ($tbaremo==2)
{
	
		for($i=0;$i<=$conexa;$i++){

	$id_imagenologia=$id_imagenologia2[$i];
	$imagenologia=$imagenologia2[$i];
	$honorarios=$honorarios2[$i];
	$honorarios_pri=$honorarios_pri2[$i];

	
	
	if(!empty($id_imagenologia) && $id_imagenologia>0){

		$q.="update especialidades_medicas set especialidad_medica=upper('$imagenologia') ,monto='$honorarios',hono_privados='$honorarios_pri' where  especialidades_medicas.id_especialidad_medica='$id_imagenologia' ;";
	}

	}
$q.="
commit work;
";
$r=ejecutar($q);

}



$admin= $_SESSION['id_usuario_'.empresa];

$log="ACTUALIZO EL BAREMO ";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
?>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		
<td colspan=4 class="titulo_seccion">Se Actualizo con Exito el Baremo<a href="#" OnClick="reg_baremos();" class="boton">Registrar O Actualizar Baremos</a><a href="#" OnClick="ir_principal();" class="boton">salir</a> </td>	
</tr>	

</table>



