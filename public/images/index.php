<?php
include ("../lib/jfunciones.php");
echo cabecera(sistema);
?>
<style>
#invitacion{color: #8B0000;
font-size:300%;
	}

</style>
<script type="text/javascript" language="JavaScript" src="../script/md5.js" charset="iso-8859-1"></script>
<link href="../public/stylesheets/navegador.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../public/stylesheets/animate.css" rel="stylesheet" type="text/css" media="screen" />
<script LANGUAGE="JavaScript">
function entrar(){
	if (document.login.clave.value.length == 0 || document.login.usuario.value.length == 0 || document.login.clave.value == 1){
		alert("TODOS LOS CAMPOS SON OBLIGATORIOS ...");
	}else{
		document.login.clave.value = calcMD5(document.login.clave.value);
		document.login.procedimiento.value="0";
		document.login.submit();
	}
}

function olvidar(){
	if (document.login.usuario.value.length == 0 ){
		alert("ES OBLIGATORIO COLOCAR SU USUARIO ...");
	}else{
		document.login.clave.value = calcMD5(document.login.clave.value);
		document.login.procedimiento.value="1";
		document.login.submit();
	}
}

//-->
</SCRIPT>
<br>
<body>


<div id="navegador">
<ul>                     
<li><a href="#" onClick='Modalbox.show("misiones.php", {title: "CliniSalud Medicina Prepagada S.A.", width: 600}); return false;'>MISI&Oacute;N</a></li>
<li><a href="#" onClick='Modalbox.show("visiones.php", {title: "CliniSalud Medicina Prepagada S.A.", width: 600}); return false;'>VISI&Oacute;N</a></li>
<li><a href="#" onClick='Modalbox.show("valores.php", {title: "CliniSalud Medicina Prepagada S.A.", width: 600}); return false;'>VALORES</a></li>
</ul>
</div>

</body>
<br>
<form method="POST" action="login.php" name="login" id="loginsesion"  onSubmit="return entrar()">
<table width="400" cellpadding="0" cellspacing="0" border=0 align="center">
	
	<tr>
		<td colspan=3 class="bg_celda">
		<table border=0 width=350 cellpadding=0 cellspacing=4 align="center" class="tabla">
		<tr>
		<td rowspan=1 colspan=2 align="center" valign="top"><samp ><img  src="../public/images/logo.png"></samp></td>
		<tr>	
			
		<tr>
		<td class="tdcampos"><h2 >Nombre Usuario</h2></td>
		<td align="right"><input class="campos" type="text" name="usuario" maxlength=16 size=20 onfocus="this.className='color_over'" onblur="this.className='color_down';"></td>
		</tr>
		<tr>
		<td class="tdcampos"><h2 >Clave</h2></td>
		<td align="right"><input class="campos" type="password" name="clave" maxlength=64 size=20 onfocus="this.className='color_over'" onblur="this.className='color_down';">
			<input class="campos" type="hidden" name="procedimiento" id="procedimiento" maxlength=64 size=20 value="">

</td>
		</tr>
	<tr><td colspan=4>&nbsp;</td></tr>
		<tr>
	<td colspan=2 align="left">
		<a href="#" OnClick="olvidar(1);" class="boton_7">Olvido Contrase&ntilde;a</a> 
	<td colspan=2 align="right"><a href="#" onClick='entrar(this);' class="boton_7">Entrar</a></td>
		</tr>	
		</table>
		</td>
	</tr>
	<tr>
		<td align="left"></td>

		<td class="celda_tabla" width="100%"></td>
		<td align="right"></td>
	</tr>		
</table>
<INPUT TYPE="Submit" value="" class="boton_invisible">
</form>
<?php
echo pie();
?>
