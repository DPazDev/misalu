<?php
include("../lib/jfunciones.php");

$usuario=strtolower($_REQUEST['usuario']);
$hash=$_REQUEST['clave'];
$num=$_REQUEST['procedimiento'];

echo cabecera(sistema);
?>
<script type="text/javascript" src="../public/javascripts/ajax.js"></script>
<script type="text/javascript" src="../public/javascripts/general.js"></script>
<script type="text/javascript" src="../public/javascripts/fparaproto.js"></script>
<script type="text/javascript" src="../public/javascripts/fcparaproto.js"></script>
<script type="text/javascript" src="../public/javascripts/fhparaproto.js"></script>
<script type="text/javascript" src="../public/javascripts/events.js"></script>
<script type="text/javascript" src="../public/javascripts/calpopup.js"></script>
<script type="text/javascript" src="../public/javascripts/dateparse.js"></script>
<script type="text/javascript" src="../public/javascripts/prototype.js"></script>
<script type="text/javascript" src="../public/javascripts/scriptaculous.js"></script>
<script type="text/javascript" src="../public/javascripts/effects.js"></script>
<script type="text/javascript" src="../public/javascripts/validation.js"></script>
<script type="text/javascript" src="../public/javascripts/webtoolkit.aim.js"></script>
<script type="text/javascript" src="../public/javascripts/fabtabulous.js"></script>
<script type="text/javascript" src="../public/javascripts/autocomplete.js"></script>
<script type="text/javascript" src="../public/javascripts/modalbox.js"> </script>
<script type="text/javascript" src=".../public/javascripts/debug.js"> </script>
<?php


$q_usuario=("select * from admin
		where admin.login='$usuario' and admin.activar='1'");

$r_usuario=ejecutar($q_usuario) or mensaje(ERROR_BD);
$num_filas=num_filas($r_usuario);
$f_usuario=asignar_a($r_usuario);
$hash2=$f_usuario['password'];

	$con=$f_usuario[contador];



if($con<=3){

$q_contar="update admin set contador='0' where admin.id_admin='$f_usuario[id_admin]';";
$r_contar=ejecutar($q_contar);}


if($num==1)   {




$q_pregunta=("select
			tbl_preguntas.id_pregunta,
			tbl_preguntas.pregunta,
			tbl_respuestas.respuesta,
			tbl_respuestas.id_admin,
			tbl_respuestas.id_pregunta
		from
			tbl_preguntas,
			tbl_respuestas
		where
			tbl_preguntas.id_pregunta=tbl_respuestas.id_pregunta and
			tbl_respuestas.id_admin='$f_usuario[id_admin]'
		ORDER BY
			RANDOM() LIMIT 1;");
$r_pregunta=ejecutar($q_pregunta);
$num_filasp=num_filas($r_pregunta);

if ($num_filasp==0){   //El usuario no tiene registradas preguntas y respuestas en la tabla de preguntas de seguridad

 mensaje("EL USUARIO NO POSEE NINGUN REGISTRO DE PREGUNTAS, POR FAVOR COMUNICARSE CON EL DEPARTAMENTO DE SISTEMAS .....  GRACIAS.");

}
else
{

$f_pregunta=asignar_a($r_pregunta);

?>
<div id="login-container">
<table  class="tabla_cabecera3">
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr><!-- MUESTRA PREGUNTA DE SEGURIDAD ALEATORIA .-->
	<tr>
		<td colspan=2 class="tdtitulos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?echo $f_pregunta[pregunta];?> </td>

		<td colspan=1 class="tdcampos">
		<input class="campos" type="text" name="respuesta" id="respuesta" maxlength=128 size=20 value="">
		<!--<input class="campos" type="hidden" name="resp" id="resp" maxlength=128 size=20 value="<?echo $f_pregunta[pregunta];?>">-->
		<input class="campos" type="hidden" name="valor" id="valor" maxlength=128 size=20 value="1">
		<input class="campos" type="hidden" name="id" id="id" maxlength=128 size=20 value="<?echo $f_usuario[id_admin];?>">
		<input class="campos" type="hidden" name="id_pre" id="id_pre" maxlength=128 size=20 value="<?echo $f_pregunta[id_pregunta];?>">
		</td>


		<td colspan=1 class="tdtitulos">
		<a href="#" OnClick="pregunta_pass()" class="boton">Modificar Password</a>
		<a href="logout.php" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
</table>






<div id="clientes"></div>
</div>
<?php
}
}
else
{

if ($num_filas == 0 || $hash != $hash2)	{

//echo "------".$con."*****";

$con=$con+1;
//echo $con;

$int=(3 - $con);

if($con<=3){

$q_contar="update admin set contador='$con' where admin.id_admin='$f_usuario[id_admin]';";
$r_contar=ejecutar($q_contar);


 mensaje("NOMBRE DE USUARIO O CONTRASEÑA INCORRECTOS. VUELVA A INTENTARLO... USTED CUENTA CON $int INTENTOS ");

}
else

 mensaje("SU CUENTA HA SIDO BLOQUEADA.... POR FAVOR COMUNICARSE CON EL DEPARTAMENTO DE SISTEMAS... GRACIAS.");
}

//busco las secciones disponibles para esta persona
	$r_secciones=pg_query("select tbl_003.*,
					  tbl_001.*,
					  tbl_002.id_tipmod
					  from tbl_003,
						   tbl_001,
					   tbl_002
					  where
						   tbl_003.id_usuario=$f_usuario[id_admin] and
						tbl_001.id_modulo=tbl_003.id_modulo and
						tbl_001.id_tipmod=tbl_002.id_tipmod
					  order by tbl_001.modulo asc");
/*echo num_filas($r_secciones) . "<br><br>";*/
$tmp="";
while($f_seccion=asignar_a($r_secciones,NULL,PGSQL_ASSOC)){
       if($tmp!=$f_seccion[id_tipmod]){
                $tmp=$f_seccion[id_tipmod];
        }
        $matriz[$tmp][$f_seccion[id_modulo]] = array("codigo"=>$tmp,"modulo" => $f_seccion[modulo], "url" => $f_seccion[url]);
}


/*foreach ($matriz as $codigo) {
        foreach ($codigo as $seccion) {
           echo "<a href=\"$seccion[url]\">$seccion[modulo]</a><br>";
}
        echo "<br>";
}


echo "<br><br><br>";
print_r($matriz);
echo "<br><br><br>";
print_r($matriz[1][1]);
echo "<br><br><br>";
print_r($matriz[2][2]);
echo "<br><br><br>";
print_r($matriz[3][3]);
echo "<br><br><br>";
print_r($matriz[4][4]);
echo "<br><br><br>";
print_r($matriz[4][5]);
die();*/

session_start();
$_SESSION['permisos']=$matriz;
$_SESSION['id_usuario_'.empresa]=$f_usuario['id_admin'];
$_SESSION['login_usuario_'.empresa]=$f_usuario['login'];
$_SESSION['nombre_usuario_'.empresa]=$f_usuario['nombres'];
$_SESSION['apellido_usuario_'.empresa]=$f_usuario['apellidos'];
$_SESSION['clave_invalida_'.empresa]=$num;
$_SESSION['valorcambiario']='0';
//$_SESSION['ciudad_'.empresa] = $f_usuario['ciudad'];
//$_SESSION['id_ciudad_'.empresa] = $f_usuario['id_ciudad'];
$_SESSION['bienvenida_'.empresa]="Bienvenido(a) ".$_SESSION['nombre_usuario_'.empresa] ." ".$_SESSION['apellido_usuario_'.empresa];
$_SESSION['tamano_'.empresa]=strlen($_SESSION['bienvenida_'.empresa]) * 10;
$log=noacento($_SESSION['login_usuario_'.empresa].", inici� sesi�n.");
logs($log,$ip,$_SESSION['id_usuario_'.empresa]);
die(header("Location: panel.php"));
}
?>
