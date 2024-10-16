<?php
include("../lib/jfunciones.php");
sesion();
$fecha = date("Y-m-d");
$mes=date("m");
$fecha = date("l");
if($fecha == "Sunday")
	$fecha = "domingo";
elseif($fecha == "Monday")
	$fecha = "lunes";
elseif($fecha == "Tuesday")
	$fecha = "martes";
elseif($fecha == "Wednesday")
	$fecha = "miercoles";
elseif($fecha == "Thursday")
	$fecha = "jueves";
elseif($fecha == "Friday")
	$fecha = "viernes";
elseif($fecha == "Saturday")
	$fecha = "sabado";

$fecha = $fecha.",".date("d/m/Y");
echo cabecera(sistema);
?>

<script type="text/javascript" src="../public/javascripts/ajax.js"></script>
<script type="text/javascript" src="../public/javascripts/general.js"></script>
<script type="text/javascript" src="../public/javascripts/fparaproto.js"></script>
<script type="text/javascript" src="../public/javascripts/fcparaproto.js"></script>
<script type="text/javascript" src="../public/javascripts/events.js"></script>
<script type="text/javascript" src="../public/javascripts/calpopup.js"></script>
<script type="text/javascript" src="../public/javascripts/dateparse.js"></script>
<script type="text/javascript" src="../public/javascripts/prototype.js"></script>
<script type="text/javascript" src="../public/javascripts/scriptaculous.js"></script>
<script type="text/javascript" src="../public/javascripts/effects.js"></script>
<script type="text/javascript" src="../public/javascripts/validation.js"></script>
<script type="text/javascript" src="../public/javascripts/fabtabulous.js"></script>
<script type="text/javascript" src="../public/javascripts/autocomplete.js"></script>
<script type="text/javascript" src="../public/javascripts/modalbox.js"> </script> 
<script type="text/javascript" src=".../public/javascripts/debug.js"> </script>
<script LANGUAGE="JavaScript">
function iop(url){
	window.open(url,'','location=0,status=0,scrollbars=1,width=650,height=600,resizable=1');
}
function imprimir(url){
        window.open(url,'','location=0,status=0,scrollbars=1,width=800,height=800,resizable=1');
}


function changeF(){
      document.forms['panel']['cednom'].focus();
}
function sumar(elem){
	
	//if (document.u.p.value.length == 0 || document.u.l.value.length == 0){
		var oa = document.getElementById("oa");
			var j=0;
			var montot=0;
			for(var i=0; i<oa.elements.length; i++) {
  var elemento = oa.elements[i];
var monto,montot;

 var num;  
  if(elemento.type == "checkbox") {
	j++;
	   if(elemento.checked) {
		monto=document.getElementById("honorarios_"+[j]);
		montot=parseInt(montot)+ parseInt(monto.value);
	
    }
  }
document.forms['oa']['monto'].value=montot;  
}
//alert("El Monto Aceptado que lleva Hasta el Momento es:"+montot);	
}


function changeF(){
      document.forms['panel']['cednom'].focus();
}

function sumar2(elem){
	//if (document.u.p.value.length == 0 || document.u.l.value.length == 0){
		var oa = document.getElementById("oa");
			var j=0;
			var montot=0;
			for(var i=0; i<oa.elements.length; i++) {
  var elemento = oa.elements[i];
var monto,montot;

 var num;  
  if(elemento.type == "checkbox") {
	j++;
	   if(elemento.checked) {
		monto=document.getElementById("honorariosr_"+[j]);
		montot=parseInt(montot)+ parseInt(monto.value);
	
    }
  }
document.forms['oa']['montor'].value=montot;  
}
//alert("El Monto Reserva que lleva Hasta el Momento es:"+montot);	
}

function multiplicar(elem){

	//if (document.u.p.value.length == 0 || document.u.l.value.length == 0){
		var oa = document.getElementById("oa");
			var j=0;
			var valort=0;
for(var i=0; i<oa.elements.length; i++) {
  var elemento = oa.elements[i];
var valor,valor2;

 var num;  
  if(elemento.type == "checkbox") {
	j++;
	   if(elemento.checked) {
		valor=document.getElementById("valor_"+[j]);
		valor2=document.getElementById("factor_"+[j]);
		valort=parseInt(valor2.value) * parseInt(valor.value);
		//document.forms['oa']['monto'].value=montot;
	
//		document.forms["oa"]["honorarios_"+[j]].value=valort;
		document.getElementById("honorarios_"+[j]).value=valort;
	
    }
  }
  
}
	
}

function calcular_cobertura(elem)
{
	var oa = document.getElementById("oa");
	var jfinal=document.forms['oa']['conj'].value
	var monto,montot,cobertura,k;
	k=0;
	for(var j=1; j<jfinal+1; j++) 
	{
		cobertura=0;
		montot=0;
		i_inicial=document.getElementById("i_inicial_"+[j]);
		i_final=document.getElementById("i_final_"+[j]);
		ifinal=parseInt(i_final.value)+parseInt(1);
		
		for(var i=i_inicial.value; i<ifinal; i++) 
		{
		
			chequeo=document.getElementById("check_"+[i]);
			if(chequeo.checked) 
			{
				monto=document.getElementById("honorarios_"+[i]);
				montot=parseInt(montot)+ parseInt(monto.value);
		
			}	
		}
		cobertura=document.getElementById("cobertura_"+[j]);
		subcob=document.getElementById("cob_"+[j]) ;
				
		cobertura=(parseInt(cobertura.value) + parseInt(subcob.value))- parseInt(montot);
	
		document.getElementById("cob_"+[j]).value=montot;  
		document.getElementById("cobertura_"+[j]).value=cobertura;
		negativo=document.getElementById("cobertura_"+[j]) 
		negativo2=parseInt(negativo.value);
		cero=0;
		if (negativo2<0)
		{
					k++;
		}
		if (k>0)
		{
			document.getElementById("actualizar").style.visibility='hidden'; 
			}
			else
			{
				document.getElementById("actualizar").style.visibility='visible'; 
			}
	}
}

function verificarproc(elem){
var proveedorp,proveedorc;
	proveedorc=document.getElementById("id_proveedorc");
	pc=proveedorc.value;
				document.getElementById("id_proveedorp").value=0; 
	
		document.getElementById("id_proveedor").value=proveedorc.value;  
	
		
}


function verificarprop(elem){
var proveedorp,proveedorc;
		proveedorp=document.getElementById("id_proveedorp");
		pp=proveedorp.value;
	
	
		document.getElementById("id_proveedorc").value=0; 
	
		document.getElementById("id_proveedor").value=proveedorp.value;  
		
}

// solo campos numericos
  function Solo_Numerico(variable){
        Numer=parseInt(variable);
        if (isNaN(Numer)){
            return "";
        }
        return Numer;
    }
    function ValNumero(Control){
        Control.value=Solo_Numerico(Control.value);
    }
// fin de solo campos numerico

// calcular tratamiento continuo
function cal(elem){
	
	//if (document.u.p.value.length == 0 || document.u.l.value.length == 0){
		var oa = document.getElementById("oa");
			var j=0;
			var montot=0;
			for(var i=0; i<oa.elements.length; i++) {
  var elemento = oa.elements[i];
var monto,montot;

 var num;  
  if(elemento.type == "checkbox") {
	j++;
	   if(elemento.checked) {
		fechaci=document.getElementById("fechaci_"+[j]);
		fechaci1 = fechaci.value.split('-');
		fechacf=document.getElementById("fechacf_"+[j]);
		fechacf1 = fechacf.value.split('-');
		fecha1=new Date(fechaci1[0],fechaci1[1]-1,fechaci1[2]);
		fecha2=new Date(fechacf1[0],fechacf1[1]-1,fechacf1[2]);
		var resta=(fecha2-fecha1)/1000/3600/24; 
		monto=document.getElementById("dd_"+[j]);
		montot=parseInt(monto.value) * (parseInt(resta)+1);
		document.forms['oa']["t_"+[j]].value=montot;  
	}
  }

}
//alert("El Monto Aceptado que lleva Hasta el Momento es:"+montot);	
}

function restar(dia1,mes1,ano1,dia2,mes2,ano2)
{
 fecha1=new Date(2011,03-1,22);
 fecha2=new Date(2011,04-1,29);
alert (fecha1) 
 var resta=(fecha2-fecha1)/1000/3600/24; 
 alert (resta)
}

// fin calcular tratamiento continuo


</SCRIPT>
<table  class="tabla_cabecera" >
  <tr>
      <tr>
      <td class="logo"><img src="../public/images/MSS.png" alt="" title=""></td>
     
    </tr>
   </tr>
</table>
<body onload="changeF()">
<form id="panel" onsubmit="return false;"  name="panel">
<table class="tabla_cabecera2">
  <tr>
         <td  class="usuario">" 
                <?php echo $_SESSION['bienvenida_'.empresa]?>", <?php echo $fecha ?>, 
	 </td>
	
         <td class="usuario" align="right">
	        Buscar por
	          <LABEL>C&eacute;dula &oacute; Nombre:</LABEL> 
	           <input  class="campos"  type="text" name="cednom" id="cednom" style="width: 130px;" />
                   <img src="../public/images/Bus.png" width="20" height="20" onclick="busClien()"  style="cursor:pointer"> 
	 </td>
   </tr>
  </table>
</form>



<table   class="tabla_cabecera3">
 <tr>
	 <td  align="center">
	
  <ul id="navmenu-h">
    <li><a href="#">Clientes <img src="../public/images/abajo.png" border=0></a>
        <ul>
           <?php menud(CLIENTES);?>
	</ul>
    </li>
	<li><a href="#">Entes <img src="../public/images/abajo.png" border=0></a>
          <ul>
            <?php menud(ENTES);?>
         </ul>
       </li>
    <li><a href="#">Proveedores <img src="../public/images/abajo.png" border=0></a>
      <ul>
	<?php menud(PROVEEDORES);?>
       </ul>
	</li>
   <li><a href="#">Administrativo <img src="../public/images/abajo.png" border=0></a>
	<ul>
	 <?php menud(ADMINISTRATIVO);?>
	</ul>
	</li>
   <li><a href="#">Compras <img src="../public/images/abajo.png" border=0></a> 
        <ul>
         <?php menud(COMPRAS);?>
        </ul>
        </li>
   <li><a href="#">Reportes <img src="../public/images/abajo.png" border=0></a> 
        <ul>
         <?php menud(REPORTES);?>
        </ul>
        </li> 
   <li><a href="#">Seguridad <img src="../public/images/abajo.png" border=0></a> 
        <ul>
         <?php menud(SEGURIDAD);?>
        </ul>
        </li>
		<li><a href="#">Ayuda <img src="../public/images/abajo.png" border=0></a> 
        <ul>
         <?php menud(AYUDA);?>
        </ul>
        </li>
		<li><a href="logout.php">Salir </a> 
        <ul>
        
        </ul>
        </li>

	
  </ul>
	
	 </td>
    </tr>
</table>


<table  class="tabla_cabecera4">

<tr>
<td >
	<img alt="spinner" id="spinner" src="../public/images/esperar.gif" style="display:none;" />
	<div id="clientes" align="center">
	<table  class="tabla_cabecera5" >

      <tr>
      <td colspan=4 align="center"><img src="../public/images/navidad.png" alt="" title=""></td>
     
    </tr>
   
</table>
	</div>
</td>
</tr>
 
</table>

<?php
echo pie();
?>
