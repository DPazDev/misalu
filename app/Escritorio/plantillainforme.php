<?
include ("../../lib/jfunciones.php");
sesion();
$elprocesoes=$_REQUEST['procesoid'];
$buscarhorafecha=("select procesos.fecha_creado,procesos.hora_creado from procesos where id_proceso=$elprocesoes");
$repbushorafecha=ejecutar($buscarhorafecha);
$horaegreso=date("H:i:s");
$datoshorafehca=assoc_a($repbushorafecha);
list($ano,$mes,$dia)=explode("-",$datoshorafehca[fecha_creado]);
$lahora=$datoshorafehca[hora_creado];
$buscotipro=("select gastos_t_b.id_servicio from gastos_t_b where gastos_t_b.id_proceso=$proceson limit 1");
  $repbustipro=ejecutar($buscotipro);
  $datbustipro=assoc_a($repbustipro);
  $eltiposervicio=$datbustipro['id_servicio'];
  if($eltiposervicio==6){
	$esservi="EMERGENCIA";  
  }
//buscamos los datos del informe medico
$datosproceso=("select procesos.id_titular,procesos.id_beneficiario,admin.nombres,admin.apellidos,tbl_informedico.diagnostico,
               tbl_informedico.laboratorio,tbl_informedico.ultrasonido,tbl_informedico.radiologia,tbl_informedico.estudiosespe,
               tbl_informedico.fechacreado,tbl_informedico.indicandole,tbl_informedico.presenta
from 
  procesos,admin,tbl_informedico
where
  procesos.id_proceso=tbl_informedico.id_proceso and
  tbl_informedico.id_admin=admin.id_admin and
  tbl_informedico.id_proceso=$elprocesoes");
$repdatosproceso=ejecutar($datosproceso);  
$actuhoraegre=("update tbl_informedico set horaegreso='$horaegreso' where id_proceso=$elprocesoes and horaegreso IS  NULL "); 
$repachoraegre=ejecutar($actuhoraegre);
$buscohoraegre=("select tbl_informedico.horaegreso from tbl_informedico where id_proceso=$elprocesoes");
$repbuscohora=ejecutar($buscohoraegre);
$ladathora=assoc_a($repbuscohora);
$egrehora=$ladathora[horaegreso];
$ladataproceso=assoc_a($repdatosproceso);
$estitular=$ladataproceso['id_titular'];
$esbenefi=$ladataproceso['id_beneficiario'];
$nombdoc="$ladataproceso[nombres] $ladataproceso[apellidos]";
$qpresenta=$ladataproceso['presenta'];
$qdiagnostic=$ladataproceso['diagnostico'];
$qlaboratorio=$ladataproceso['laboratorio'];
$qultrasonido=$ladataproceso['ultrasonido'];
$qradiologia=$ladataproceso['radiologia'];
$qestudiespe=$ladataproceso['estudiosespe'];
$qindico=$ladataproceso['indicandole'];
$qfechacreado=$ladataproceso['fechacreado'];
//Buscamos los datos del titular
if ($esbenefi==0){
	$busdattitu=("select clientes.nombres,clientes.apellidos,clientes.sexo,clientes.fecha_nacimiento,clientes.cedula,entes.nombre 
	                   from 
	                     clientes,titulares,entes 
	                   where
							clientes.id_cliente=titulares.id_cliente and
							titulares.id_ente=entes.id_ente and titulares.id_titular=$estitular");
	    $repdatatitu=ejecutar($busdattitu);						
	    $dataprintitu=assoc_a($repdatatitu);
	    $nombretitu=$dataprintitu['nombres'];
	    $apellittitu=$dataprintitu['apellidos'];
	    $nompaciente="$nombretitu $apellittitu";
	    $cedupaciente=$dataprintitu['cedula'];
	    $elentepacien=$dataprintitu['nombre'];
	    $genertitu=$dataprintitu['sexo'];
	    $laedad=calcular_edad($dataprintitu['fecha_nacimiento']);
	    $esun="Titular";
	    if($genertitu==0){
			$ms1="atendida";
			$ms2="la";
		 }else{
			 $ms1="atendido";
			 $ms2="el";
			 }
}else{
	  	$busdattitu=("select clientes.nombres,clientes.apellidos,clientes.sexo,clientes.fecha_nacimiento,clientes.cedula
	                   from 
	                     clientes,titulares,beneficiarios 
	                   where
							clientes.id_cliente=titulares.id_cliente and
							titulares.id_titular=beneficiarios.id_titular and
                            beneficiarios.id_beneficiario=$esbenefi");
	    $repdatatitu=ejecutar($busdattitu);						
	    $dataprintitu=assoc_a($repdatatitu);
	    $nombretitu=$dataprintitu['nombres'];
	    $apellittitu=$dataprintitu['apellidos'];
	    $cedulartitu=$dataprintitu['cedula'];
	    $nomcompletotitu="del Titular $nombretitu $apellittitu, CI No.$cedulartitu";
	
	
	   $busdatbeni=("select clientes.nombres,clientes.apellidos,clientes.sexo,clientes.fecha_nacimiento,
	   clientes.cedula,entes.nombre,parentesco.parentesco 
	from 
	   clientes,titulares,entes,beneficiarios,parentesco 
	where
	clientes.id_cliente=beneficiarios.id_cliente and
        beneficiarios.id_titular=titulares.id_titular and
        beneficiarios.id_parentesco=parentesco.id_parentesco and
	titulares.id_ente=entes.id_ente and beneficiarios.id_beneficiario=$esbenefi");
	   $repdatbeni=ejecutar($busdatbeni);
	   $ladatbeni=assoc_a($repdatbeni);
	   $nompaciente="$ladatbeni[nombres] $ladatbeni[apellidos]";
	   $cedupaciente=$ladatbeni['cedula'];
	   $sexobeni=$ladatbeni['sexo'];
	   $elentepacien=$ladatbeni['nombre'];
	   $laedad=calcular_edad($ladatbeni['fecha_nacimiento']);
	   $esun="Beneficiario $nomcompletotitu";
	   if($sexobeni==0){
		   $ms1="atendida";
			$ms2="la";
		}else{
			$ms1="atendido";
			 $ms2="el";
			}
	}
?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo"><br><br>M&eacute;rida: Calle 25 ente Av. 7 y 8 Edif. El Cisne<br>
Tel&eacute;f.: (0274) 2510028 / 2510092 / 2510910 <br>
Av. Las Am&eacute;ricas. C.C. Mayeya. Nivel Mezzanina Local 16,17 y 24<br>
Tel&eacute;f.: (0274) 2459101 / 2459229 <br>
El Vig&iacute;a: Av. Bol&iacute;var esquina con Av. 12 calle 6 Edif. Liegos <br>
Tel&eacute;f.: (0275) 8812017 / Fax 8813436</td>
<td colspan=1 class="titulo"></td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=2 class="titulo">

</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">

Fecha creado: <?echo "$dia-$mes-$ano"?><br>
Hora  creado: <?echo $lahora?><br>
Hora  egreso: <?echo $egrehora?><br>
</td>
<td colspan=2 class="titulo">

</td>
</tr>
<tr>
<td colspan=4>

</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
EMERGENCIA
</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
INFORME MEDICO
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
 En ejercicio legal de mi profesi&oacute;n como Medico, hago constar que el (la) Sr. (Sra.):  <?echo $nompaciente?> de 
 <?echo $laedad?> años de edad portador (a) de la C&eacute;dula de identidad No. <?echo $cedupaciente?> perteneciente al ente (<?echo $elentepacien?>) como (<?echo $esun?>); <br>
 Acude a este Centro de Asistencia por presentar: <?echo $qpresenta?> <br>
 <?if((!empty($qlaboratorio)) or (!empty($qultrasonido)) or (!empty($qradiologia)) or (!empty($qestudiespe))or (!empty($qindico))){
	 echo "Ameritando tratamiento con:<br>";
	 if(!empty($qlaboratorio)){
		 echo "Laboratorio: $qlaboratorio";
		}
	  if(!empty($qultrasonido)){
		 echo " Ultrasonido: $qultrasonido";
		}
	  if(!empty($qradiologia)){
		 echo " Radiolog&iacute;a: $qradiologia";
		}
	 if(!empty($qestudiespe)){
		 echo " Estudios Especiales: $qestudiespe";
		}	
	 if(!empty($qindico)){
		 echo " Tratamiento: $qindico";
		}				
	 }
 ?>

 
</td>
</tr>
<tr><td colspan=4 class="datos_cliente">
 Diagn&oacute;stico: <?echo $qdiagnostic?></td></tr>
<tr>
<tr><td><br><br>
 M&eacute;dico tratante: <?echo $nombdoc?></td></tr>
<tr>
<br>
<br>

<td colspan=4 class="titulo3"><br><br>
<p style="text-decoration: overline;"> Firma y Sello del M&eacute;dico Tratante</p>

</td>
</tr>
</table>
<br>
<br>
<br>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo"><br><br>M&eacute;rida: Calle 25 ente Av. 7 y 8 Edif. El Cisne<br>
Tel&eacute;f.: (0274) 2510028 / 2510092 / 2510910 <br>
Av. Las Am&eacute;ricas. C.C. Mayeya. Nivel Mezzanina Local 16,17 y 24<br>
Tel&eacute;f.: (0274) 2459101 / 2459229 <br>
El Vig&iacute;a: Av. Bol&iacute;var esquina con Av. 12 calle 6 Edif. Liegos <br>
Tel&eacute;f.: (0275) 8812017 / Fax 8813436</td>
<td colspan=1 class="titulo"></td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=2 class="titulo">

</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">

Fecha creado: <?echo "$dia-$mes-$ano"?><br>
Hora  creado: <?echo $lahora?><br>
Hora  egreso: <?echo $egrehora?><br>
</td>
<td colspan=2 class="titulo">

</td>
</tr>
<tr>
<td colspan=4>

</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
EMERGENCIA
</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
INFORME MEDICO
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
 En ejercicio legal de mi profesi&oacute;n como Medico, hago constar que el (la) Sr. (Sra.):  <?echo $nompaciente?> de 
 <?echo $laedad?> años de edad portador (a) de la C&eacute;dula de identidad No. <?echo $cedupaciente?> perteneciente al ente (<?echo $elentepacien?>) como (<?echo $esun?>); <br>
 Acude a este Centro de Asistencia por presentar: <?echo $qpresenta?> <br>
 <?if((!empty($qlaboratorio)) or (!empty($qultrasonido)) or (!empty($qradiologia)) or (!empty($qestudiespe))or (!empty($qindico))){
	 echo "Ameritando tratamiento con:<br>";
	 if(!empty($qlaboratorio)){
		 echo "Laboratorio: $qlaboratorio";
		}
	  if(!empty($qultrasonido)){
		 echo " Ultrasonido: $qultrasonido";
		}
	  if(!empty($qradiologia)){
		 echo " Radiolog&iacute;a: $qradiologia";
		}
	 if(!empty($qestudiespe)){
		 echo " Estudios Especiales: $qestudiespe";
		}	
	 if(!empty($qindico)){
		 echo " Tratamiento: $qindico";
		}				
	 }
 ?>

</td>
</tr>
<tr><td colspan=4 class="datos_cliente">
 Diagn&oacute;stico: <?echo $qdiagnostic?></td></tr>
<tr>
<tr><td><br><br>
 M&eacute;dico tratante: <?echo $nombdoc?></td></tr>
<tr>
<br>
<br>

<td colspan=4 class="titulo3"><br><br>
<p style="text-decoration: overline;"> Firma y Sello del M&eacute;dico Tratante</p>

</td>
</tr>
</table>
