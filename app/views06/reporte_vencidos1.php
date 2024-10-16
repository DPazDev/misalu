<?php
ini_set('error_reporting', E_ALL-E_NOTICE);
ini_set('display_errors', 1);
//scrip para mostrar los provedores de un product, precio y tipo de producto
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$_SESSION['opcionpe']=0;



function getUltimoDiaMes($elAnio,$elMes) {
  return date("d",(mktime(0,0,0,$elMes+1,1,$elAnio)-1));
}
//funcion para calcular los dias restantes
//parametros necesarios Fecha de inicio y fecha final 
function dias_restante($fech1,$fech2) {

$f1=explode('-',$fech1);
//defino fecha 1
$ano1 = $f1[0];
$mes1 = $f1[1];
$dia1 = $f1[2];

$f2=explode('-',$fech2);
//defino fecha 2
$ano2 = $f2[0];
$mes2 = $f2[1];
$dia2 = $f2[2];

//calculo timestam de las dos fechas
$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
$timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2);

//resto a una fecha la otra
$segundos_diferencia = $timestamp1 - $timestamp2;
//echo $segundos_diferencia;

//convierto segundos en días
$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);

//obtengo el valor absoulto de los días (quito el posible signo negativo)
$dias_diferencia = abs($dias_diferencia);

//quito los decimales a los días de diferencia
$dias_diferencia = floor($dias_diferencia);

return $dias_diferencia;
	
	}
	//recepcion de variables
$dependencia=$_POST['dependencia'];
$tpinsumo=$_POST['tipo_insumo'];
$intervalo=$_POST['catidadmes'];
$fechaact=date('Y-m-d');//fecha actual
$forzarmescompleto=$_POST['forzar'];//si forzarmescompleto es igual a true tomara en cuenta los
$listavencidos='false';//para invertir lista a los meses anteriores



$ultimo_dia_mes=getUltimoDiaMes($an,$mm);
$proximoF = date('Y-m-d', strtotime('+'.$intervalo.' month')) ; // suma un mes segunel intervalo
$iniciofecha=$fechaact;//inicia en la fecha actual
$finfecha=$proximoF;//fecha Final de busquedad

//modificar forma de busqueda//
/*
if($listavencidos=='true')//invertir busqueda a partir de la fecha actual buscar meses anteriores
{//fecha anterior
	$anteriorF = date('Y-m-d', strtotime('-'.$intervalo.' month')) ; // resta un mes segunel intervalo

	 $fact=explode('-',$fechaact);//fecha actual explotar
	$mes_act=$fact[1];//mes
	$an_act=$fact[0];//año
	$ultimo_dia_mes=getUltimoDiaMes($an_act,$mes_act);	//ultimo dia del mes
$finfecha=$an_act.'-'.$mes_act.'-'.$ultimo_dia_mes;//arreglar fecha 
	
	//Fecha ANTERIOR
	$fact=explode('-',$anteriorF);//fecha Anterior explotar
	$mesA=$fact[1];//mes	
	$anA=$fact[0];//año
	$iniciofecha=$anA.'-'.$mesA.'-01';//arreglar fecha
	
}

*/


if($forzarmescompleto=='true')//mostrar los meses completos
{
	$fact=explode('-',$fechaact);//fecha actual explotar
	$mes=$fact[1];
	$an=$fact[0];
	$iniciofecha=$an.'-'.$mes.'-01';//fecha inicial de Busqueda
	//Fecha Final
	$fact=explode('-',$proximoF);//fecha actual explotar
	$mesp=$fact[1];
	$anp=$fact[0];
$ultimo_dia_mes=getUltimoDiaMes($anp,$mesp);	

$finfecha=$anp.'-'.$mesp.'-'.$ultimo_dia_mes;

}


//$dt_5DiasDespues = date('Y-m-d', strtotime('+5 day')) ; // Suma 5 días
//$dt_5SemanasDespues = date('Y-m-d', strtotime('+5 week')) ; // Suma 5 semanas
//$dt_5AniosDespues = date('Y-m-d', strtotime('+5 year')) ; // Suma 5 años



$sql="SELECT
  tbl_insumos.insumo,  
  tbl_laboratorios.laboratorio, 
  tbl_insumos_ordenes_compras.fecha_vencimiento
FROM 
  public.tbl_insumos_ordenes_compras, 
  public.tbl_insumos, 
  public.tbl_laboratorios, 
  public.tbl_tipos_insumos, 
  public.tbl_insumos_almacen, 
  public.tbl_dependencias
WHERE 
  tbl_insumos_ordenes_compras.id_insumo = tbl_insumos.id_insumo AND
  tbl_insumos_almacen.cantidad >'0' and
  tbl_insumos.id_laboratorio = tbl_laboratorios.id_laboratorio AND
  tbl_insumos.id_tipo_insumo = tbl_tipos_insumos.id_tipo_insumo AND
  tbl_insumos.id_insumo = tbl_insumos_almacen.id_insumo AND
  tbl_insumos_almacen.id_dependencia = tbl_dependencias.id_dependencia AND
  tbl_dependencias.id_dependencia= '$dependencia'  
  AND 
  tbl_insumos.id_tipo_insumo= '$tpinsumo'  AND 
  tbl_insumos_ordenes_compras.fecha_vencimiento Between '$iniciofecha' and '$finfecha'
ORDER BY
  tbl_insumos_ordenes_compras.fecha_vencimiento ASC;";



$vencidos=ejecutar($sql);
$numcos=num_filas($vencidos);//numero de registros 

if($numcos>0) {
?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td class="titulo_seccion" colspan="5"> <center> <?php  echo "Medicamentos o insumos por vencer en las fecha del $iniciofecha hasta $finfecha"; ?> </center></td>
</tr>

<?php
echo"	<tr>
	<td class='tdtitulos'>Nro</td>
	 <td class='tdtitulos'>Articulo</td> 
	<td class='tdtitulos'>Laboratorio</td>
	<td class='tdtitulos'>Fecha de Vencimiento</td>
	<td class='tdtitulos'>Dias Restantes</td>

	</tr>	";
	$con=0;//contador de elementos del bucle
		while($ve = asignar_a($vencidos)){
			  $con=$con+1;	//contador de repeticiones
	$diasR=dias_restante($fechaact,$ve[fecha_vencimiento]);	
		echo"
		<tr>	
			<td class='tdcampos'> $con </td>
			<td class='tdcampos'> $ve[insumo] </td>
			<td class='tdcampos'> $ve[laboratorio] </td>
			<td class='tdcampos'> $ve[fecha_vencimiento] </td>
			<td class='tdcampos'> $diasR </td>
		</tr>	";	
			
			}
?></table><?php
echo"<label id='imprimirxvencer' class='boton' style='cursor:pointer;' onclick='impr_xvencer()' >Imprimir</label>";

}//fin del if si hay registros
else {
	?>
		<table class='tabla_cabecera5'  cellpadding=0 cellspacing=0>
     <tr>
         <td  class='titulo_seccion'>No se encontraron resultados!!!!</td>
     </tr>
    </table>
  <?php
	}