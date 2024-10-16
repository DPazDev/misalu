<?php
include ("../../lib/jfunciones.php");
sesion();

if(!empty($_GET['nombre']) || $_GET['nombre']!='') {
$nombreArt=$_GET['nombre'];
$marca=$_GET['marca'];
$consultarinsumo=true;
}else{$consultarinsumo=false;}

if($nombreArt!=''){
		$sqlserial="SELECT  tbl_insumos.id_insumo,tbl_insumos.id_laboratorio,tbl_insumos.insumo,tbl_tipos_insumos.tipo_insumo,tbl_insumos.id_tipo_insumo,tbl_insumos.codigo_barras,  tbl_insumos.psicotropico
		FROM    
		public.tbl_insumos, 
		public.tbl_laboratorios,
		public.tbl_tipos_insumos  		
   WHERE 
   tbl_laboratorios.id_laboratorio = tbl_insumos.id_laboratorio AND  tbl_tipos_insumos.id_tipo_insumo = tbl_insumos.id_tipo_insumo 
   AND  upper(tbl_insumos.insumo) = upper('$nombreArt') AND	tbl_insumos.id_laboratorio='$marca';";
		$insumocarac=ejecutar($sqlserial);
		$encontradosinsumos=num_filas($insumocarac);
	if($encontradosinsumos>=1) {//hay registros
		$ins=asignar_a($insumocarac,NULL,PGSQL_ASSOC);
		$idins=$ins[id_insumo];	//id insumo
		$tins=$ins[tipo_insumo];	//tipo de insumo
	$tidins=$ins[id_tipo_insumo];//id tipo insumo
	$insum=$ins[insumo];//insumo
	$labins=$ins[id_laboratorio];//id marca 
	$cod=$ins[codigo_barras];//codigo de barra
	$psi=$ins[psicotropico];//codigo de barra
	if($tins=='MEDICAMENTO')
	{
$SQLcarcateMedical="SELECT 
  tbl_caract_medicamentos.id_insumo, 
  tbl_caract_medicamentos.nombre_comercial, 
  tbl_caract_medicamentos.nombre_generico, 
  tbl_caract_medicamentos.principio_activo, 
  tbl_caract_medicamentos.bajo_minimo, 
  tbl_caract_medicamentos.farmacologia, 
  tbl_caract_medicamentos.otras_caracteristicas
FROM 
  public.tbl_caract_medicamentos where tbl_caract_medicamentos.id_insumo='$idins';";

  
 $insumocarac=ejecutar($SQLcarcateMedical);
		$CaractInsumo=num_filas($insumocarac);
		if($CaractInsumo>=1) {
			$carc=asignar_a($insumocarac,NULL,PGSQL_ASSOC);
				$ncomer=$carc[nombre_comercial];		
				$ngener=$carc[nombre_generico];		
				$princi=$carc[principio_activo];		
				$bajomi=$carc[bajo_minimo];		
				$farmco=$carc[farmacologia];		
				$caract=$carc[otras_caracteristicas];	
			echo "true/#/true/#/$idins/#/$tins/#/$tidins/#/$insum/#/$labins/#/$cod/#/$ncomer/#/$ngener/#/$princi/#/$bajomi/#/$farmco/#/$caract/#/$psi";
			//devolver 1:true si hay ya hay un registro false:si no existe 11 varibles
			//devolver 2:true si hay ya carcteristicas:si no falso
			//devolver 2:true si hay ya carcteristicas:si no falso
				
			} else {  echo"true/#/false/#/$idins/#/$tins/#/$tidins/#/$insum/#/$labins/#/$cod ";}
  
    }else{
echo "false/#/false/#/$idins/#/$tins/#/$tidins/#/$insum/#/$labins/#/$cod";//ID DE MEDICAMENTO FALTANTE 4 variables
}	
	
		}else { echo "true"; }

}
 ?>