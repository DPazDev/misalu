<?php
ini_set('error_reporting', E_ALL-E_NOTICE);
ini_set('display_errors', 1);
?>

<style type="text/css">
.spanX {
width: 10px;
height: 10px;
font-size: 80%;
border:2px solid red;
border-color: #000;
padding-bottom: 1px;
padding-left:1px;
padding-right:1px;
padding-top: 1px;
display: block;
color: #000;
float: right;
z-index: 999;
	}


.tooltips {
  position: relative;
  display: inline;
}

.tooltips span {
  position: absolute;
  width:140px;
  color: #000000;
  text-shadow: 0px 0px 1px #050504;
  background: #ABFFAB;
  height: 31px;
  line-height: 31px;
  text-align: center;
  visibility: hidden;
  border-radius: 7px;
  box-shadow: 1px 2px 3px #8C7A7A;
}

.tooltips span:after {
  content: '';
  position: absolute;
  bottom: 100%;
  left: 50%;
  margin-left: -8px;
  width: 0; height: 0;
  border-bottom: 35px solid #ABFFAB;
  border-right: 89px solid transparent;
  border-left: 8px solid transparent;
}

a:hover.tooltips span {
  visibility: visible;
  opacity: 0.7;
  top: 30px;
  left: 50%;
  margin-left: -80px;
  z-index: 999;
}

.estaconte{
	position: relative;
display: inline;}

.esta{  
  position: absolute;
  width:140px;
  color: #000000;
  text-shadow: 0px 0px 1px #050504;
  background: #ABFFAB;
  height: 31px;
  line-height: 31px;
  text-align: center;
  visibility: visible;
  border-radius: 7px;
  box-shadow: 1px 2px 3px #8C7A7A;
 	 margin-left: -50px;
 	 opacity: 0.9;
 	  top: 30px;
  z-index: 999;
  }
  
.esta::after
{  content: '';
display: block;
  position: absolute;
  bottom: 100%;
  left: 50%; 
  margin-left: -8px;
  width: 0; height: 0;
  border-bottom: 10px solid #ABFFAB;
  border-right: 10px solid transparent;
  border-left: 8px solid transparent;
  }
  
</style>

<?php
include ("../../lib/jfunciones.php");
sesion();

$idorden=$_GET['numfactu'];
$cantidadArti=$_GET['cantidad'];//cantidad de articulos (maximos lotes)
$nun=$_GET['num'];//numero de lista
$nunlote=$_GET['nunlote'];//lotes precargados

$listlote=$_GET['list'];
if($listlote=='') { $activovacios=0; $crtstatus=2;}
else{$activovacios=1;$crtstatus=0;}

/*
echo "<h1>LISTA $listlote<br></h1>";
echo "<h1>CANTIDAD $cantidadArti<br></h1>";
echo "<h1>ORDEN $idorden<br></h1>";
echo "<h1>NUM $nun<br></h1>";
//echo "<h1></h1>";
*/

$reglonlotes=explode('***',$listlote);
$nlot=count($reglonlotes);//cantidad x articulos lote


?>

<div id="lotespincipal"  style='display:block;'><?php
$numreglotes=2;
  if($numreglotes<=0) {
echo "no se asignaron Lotes para el este Articulo";  
?>
<?php
 }else {
//////////encabezado de tabla datos del producto y despacho/////// 	
///consultar la orden

 	?> 	
 	<table width='100%'>
		<tr>
			<th> <?php echo "<H3>INSUMO ORDEN $idorden  </H3>";?></th>		
		</tr> 	
 	</table>
<input type="hidden" name="artcantidad" id="artcantidad" value="<?php echo $cantidadArti;?>">
<input type="hidden" name="idorden" id="idorden" value="<?php echo $idorden;?>">
<input type="hidden" name="num" id="num" value="<?php echo $nun;?>">
 	
 	
 	<?php
 	$tbl='colortable';
 ?><table width='100%' id='colortable'  class="tabla_cabecera3" border="2">
 <tr>
	<td class="tdcamposc">Lote </td>
	<td class="tdcamposc">Cantidad</td>
	<td class="tdcamposc">Fecha de vencimiento</td>
	<td class="tdcamposc">
<span id='<?php echo $img; ?>' style='cursor:pointer'onclick="lotesmaximo('<?php echo $tbl;?>','<?php echo $idorden;?>','<?php echo $idorden; ?>','<?php echo $idorden; ?>','artcantidad'),clotesagregados()" title="Agregar otro lote"><img id="add" src="../public/images/add_16.png" /></span>	
	</td>
	
</tr>

<?php  
//limpiar variables usadas
$lote='';
$cantidadlote='';
$fechalot='';
$status='';

$g=0;//id
$nst=0;
$nolectura='';
$h=0;//num de lotes enocontrados
foreach ($reglonlotes as $rlotes) 
{ $g++;//incremento 1
	$campo=$idorden.'-'.$g;
	$ListaLot=explode('::',$rlotes);
	$numfila=count($ListaLot);//nun de lotes por Art
//	echo"<h1> nun:$numfila</h1>";	

$lote=$ListaLot[0];
$cantidadlote=$ListaLot[1];
$fechalot=$ListaLot[2];
$status=$ListaLot[3];
//if($activovacios==0){echo "el status es desactivo$activovacios";}
if($status=='') {$status=2; $nolectura="";}//lote precargado vacios
if($status==0) {++$nst; $nolectura="readonly='readonly'";}
if($cadena=strstr($fechalot,'/'))
{ //echo $cadena."<br>";
}else {
	//echo "no se encontraron onsidencias";
}

	?>

<tr>

	<td class="tdcamposc"><input  type="text" <?php  echo $nolectura; ?> name='lot' id='<?php echo $campo."-lot"?>' onkeyup="contenidocampo(this)" size='15' value="<?php echo $lote;?>" maxlength='25'></td>
	<td class="tdcamposc"><input type="text"  <?php echo $nolectura; ?> name='cant' id='<?php echo  $campo."-cant"?>'onkeyup="contenidocampo(this)" onKeyPress="return soloNumeros(event)"size='11' value="<?php echo $cantidadlote;?>" ></td>
	<td class="tdcamposc"> <input type="text" <?php echo $nolectura; ?> name='fech' id='<?php echo  $campo."-fech"?>' onkeyup="contenidocampo(this)" onKeyPress="return fechasformato(event,this,'-');" value="<?php echo $fechalot;?>" maxlength='11' size='11'></td>	
	<td class="tdcamposc">
<input type='hidden' name='statu' id='<?php echo  $campo."-statu"?>' value='<?php echo $status;?>'>

<span id='del' style='cursor:pointer' onclick="QuitarLotes(this,document.getElementById('colortable')),lotesprecargados('<?php echo $status;?>')"><img id='dl2' src='../public/images/del_16.png' /></span>
	 </td>
</tr>

<?php 
	if($numfila>=4) {$h++;}//Si esta VACIO ESTE NO SE CUENTA

 //fin Forech
}

?>
<input type="hidden" size="2" name="tdcantidad" id="tdcantidad" value="<?php echo $g;?>" >
<input type="hidden" size="2" name="lotesdefault" id="lotesdefault" value="<?php echo $nunlote;?>" ><!-- LOTE CARGADOS -->
<input type="hidden" size="2" name="lotecargados" id="lotecargados" value="<?php echo $h;?>" ><!-- LOTE CARGADOS -->
<input type="hidden" size="2" name="crteliminacionlot" id="crteliminacionlot" value="<?php echo $nst;?>" ><!-- ELIMINADOS LOTE CARGADOS -->
<input type="hidden" size="2" name="agregarlote" id="agregarlote" value="0" ><!-- MAS LOTE CARGADOS -->
<input type="hidden" size="2" name="agregarlote" id="crtstatus" value="<?php echo $crtstatus;?>" ><!-- MAS LOTE CARGADOS -->

</table>
<table width='100%' id='opciones'  class="tabla_cabecera3" border="2">
<tr> 
<td colspan="5" align='center'>
<a href="#" title="Aplicar lotes" class="boton" onclick="CargConfirmacionLotes('colortable',<?php echo $idorden;?>,<?php echo $nun;?>)">Confirmar Lotes</a>

</td>
</tr>

</table>
 <?php  }  
?>

</div>


<div id="nuevo"> </div>