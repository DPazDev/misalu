<?
include ("../../lib/jfunciones.php");
sesion();
$lamatriz=$_SESSION['matriz'];
$elmatriz=$_SESSION['matrizt'];
$opera1=$_SESSION['toperacion'];
$dependarestar=$_SESSION['ladepenusu'];
$idtiposervicio=0;
 if($opera1=='externa'){
   $elservicio=7;
 }else{
    $elservicio=5;
  }
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$provees=$_POST['elproveedor'];
$enfermedades=$_POST['laenferme'];
$comentarioes=$_POST['elcoment'];
$coberturaes=$_POST['lacobertu'];
$recepciones=$_POST['fecharecep'];
$espcialmedic=$_POST['mediespcial'];
$codigot=time();
$pasogtb=0;
$codigo=$elid.$codigot;
$montoadescargar=$_POST['montodescarga'];

$busctipodetiobe=("select coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario,coberturas_t_b.monto_actual from coberturas_t_b where coberturas_t_b.id_cobertura_t_b=$coberturaes;");
$repbusctiobe=ejecutar($busctipodetiobe);
$databustiobe=assoc_a($repbusctiobe);
$estitu=$databustiobe['id_titular'];
$esbene=$databustiobe['id_beneficiario'];
$montoactualcober=$databustiobe['monto_actual'];
if($montoadescargar>$montoactualcober){
	echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
      <tr>
         <td colspan=8 class=\"titulo_seccion\">El monto a descargar es mayor que la cobertura actual !!</td>
       </tr>
     </table>";
	}else{
		if($opera1=='externa'){
		 //guardo en proceso para crear el gasto
		$guardarenproceso=("insert into procesos(id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado
                                             ,hora_creado,comentarios,id_admin,codigo) values($estitu,$esbene,2,'$recepciones','$fecha','$hora',
                                            upper('$comentarioes'),$elid,'$codigo');");
		   $repguardaenproceso=ejecutar($guardarenproceso);
		//busco lo que guarde en la tabla procesos
         $buscoelproceso=("select procesos.id_proceso from procesos where procesos.id_titular=$estitu and
										procesos.id_beneficiario=$esbene and procesos.fecha_creado='$fecha' and procesos.hora_creado='$hora' and procesos.codigo='$codigo';");
		$repbuscoelproceso=ejecutar($buscoelproceso);
		$databuscoelproceso=assoc_a($repbuscoelproceso);
		$elprocesocargado=$databuscoelproceso['id_proceso'];
		//guardo en la tabla gastos_t_b
		$cuantos=count($lamatriz);
        for ($f=0;$f<$cuantos;$f++){
			 $producto=$lamatriz[$f][0];
			 $tratamiento=$lamatriz[$f][1];
			 if($tratamiento=='Si'){
				$tratamiento='on'; }else{
					$tratamiento='0';
				}
			 $cantidades= $lamatriz[$f][2];
			 $precioprodu=$lamatriz[$f][3];
			 $subtotalprod=$lamatriz[$f][4];
			 $fechattconti=$lamatriz[$f][5];
			 $idinsumo=$lamatriz[$f][6];
			 $dep=$lamatriz[$f][7];
			 $espc=$lamatriz[$f][8];
			 $CantAlmacen=$lamatriz[$f][9];
       /////ESPECIALIDAD MEDICA
       echo$buscarlconcepto=("select especialidades_medicas.especialidad_medica,especialidades_medicas.id_especialidad_medica from especialidades_medicas where especialidades_medicas.id_especialidad_medica=$espc;");
       $repbuscarlconcepto=ejecutar($buscarlconcepto);
       $dataconcepto=assoc_a($repbuscarlconcepto);
       $elconceptoes=$dataconcepto['especialidad_medica'];

			if(!empty($fechattconti)){
				  $fechattconti="'$fechattconti'";
				}else{
					$fechattconti="NULL";
					}
			$guardoengatotb=("insert into gastos_t_b(id_proceso,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,
                                            enfermedad,id_proveedor,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,
											monto_pagado,fecha_cita,hora_cita,continuo,unidades,fecha_continuo,id_dependencia)
									values($elprocesocargado,'$elconceptoes','$producto','$fecha','$hora',$coberturaes,
                                    upper('$enfermedades'),$provees,$idtiposervicio,$elservicio,'$subtotalprod','$subtotalprod',
                                    '$subtotalprod','$fecha','$hora','$tratamiento','$cantidades',$fechattconti,$dep);");
			$repguardoengatotb=ejecutar($guardoengatotb);
			$pasogtb=1;
		}
   if($pasogtb==1){
	  $buscpararestar=("select coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario,coberturas_t_b.monto_actual from coberturas_t_b where coberturas_t_b.id_cobertura_t_b=$coberturaes;");
	   $repbuscpararestar=ejecutar($buscpararestar);
	   $datapararestar=assoc_a($repbuscpararestar);
	   $elmontoqtiene=$datapararestar['monto_actual'];
	   $larestaes= $elmontoqtiene-$montoadescargar;
	   $actualizolacober=("update coberturas_t_b set monto_actual=$larestaes where coberturas_t_b.id_cobertura_t_b=$coberturaes;");
	   $repactualizolacober=ejecutar($actualizolacober);

	  echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
      <tr>
         <td colspan=8 class=\"titulo_seccion\">El proceso No. $elprocesocargado se ha cargado exitosamente!!</td>
         <td colspan=7 class=\"titulo_seccion\" title=\"Imprimir reporte\">";
			$url="'views01/farmaext7.php?elproceso=$elprocesocargado'";?>
			<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a>
			<?echo"</td>
         <td class=\"titulo_seccion\"><label title=\"Regresar a Orden de M&eacute;dicamento\"class=\"boton\" style=\"cursor:pointer\" onclick=\"reg_farma()\" >Regresar</label></td>
       </tr>
     </table>";
	}
}
}?>
<?
if($opera1=='interna'){
        $provees= $_SESSION['proveeinterna'];
	//guardo en proceso para crear el gasto
		$guardarenproceso=("insert into procesos(id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado
                                             ,hora_creado,comentarios,id_admin,codigo) values($estitu,$esbene,2,'$recepciones','$fecha','$hora',
                                            upper('$comentarioes'),$elid,'$codigo');");
		   $repguardaenproceso=ejecutar($guardarenproceso);
		//busco lo que guarde en la tabla procesos
         $buscoelproceso=("select procesos.id_proceso from procesos where procesos.id_titular=$estitu and
										procesos.id_beneficiario=$esbene and procesos.fecha_creado='$fecha' and procesos.hora_creado='$hora' and procesos.codigo='$codigo';");
		$repbuscoelproceso=ejecutar($buscoelproceso);
		$databuscoelproceso=assoc_a($repbuscoelproceso);
		$elprocesocargado=$databuscoelproceso['id_proceso'];
		//guardo en la tabla gastos_t_b
		$cuantos=count($lamatriz);

        for ($f=0;$f<$cuantos;$f++){
			 $producto=$lamatriz[$f][0];
			 $tratamiento=$lamatriz[$f][1];
			 if($tratamiento=='Si'){
				$tratamiento='on'; }else{
					$tratamiento='0';
				}
			 $cantidades= $lamatriz[$f][2];
			 $precioprodu=$lamatriz[$f][3];
			 $subtotalprod=$lamatriz[$f][4];
			 $fechattconti=$lamatriz[$f][5];
       $elidproductoes=$lamatriz[$f][6];
       $dep=$lamatriz[$f][7];
       $espc=$lamatriz[$f][8];
			 $CantAlmacen=$lamatriz[$f][9];

       /////ESPECIALIDAD MEDICA
       $buscarlconcepto=("select especialidades_medicas.especialidad_medica,especialidades_medicas.id_especialidad_medica from especialidades_medicas where especialidades_medicas.id_especialidad_medica=$espc;");
       $repbuscarlconcepto=ejecutar($buscarlconcepto);
       $dataconcepto=assoc_a($repbuscarlconcepto);
       $elconceptoes=$dataconcepto['especialidad_medica'];
			if(!empty($fechattconti)){
				  $fechattconti="'$fechattconti'";
				} else{
					$fechattconti="NULL";
					}
			$guardoengatotb=("insert into gastos_t_b(id_proceso,nombre,id_insumo,descripcion,
                                            fecha_creado,hora_creado,
                                            id_cobertura_t_b,
                                            enfermedad,id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,
					    monto_pagado,fecha_cita,hora_cita,continuo,unidades,fecha_continuo,id_proveedor,id_dependencia)
									values($elprocesocargado,'$elconceptoes',$elidproductoes,'$producto','$fecha','$hora',$coberturaes,
                                    upper('$enfermedades'),$idtiposervicio,$elservicio,'$subtotalprod','$subtotalprod',
                                    '$subtotalprod','$fecha','$hora','$tratamiento','$cantidades',$fechattconti,$provees,$dep);");
			$repguardoengatotb=ejecutar($guardoengatotb);
			$buscoloreste=("select tbl_insumos.id_insumo from tbl_insumos where
                                       tbl_insumos.id_insumo=$elidproductoes;");
			$repbusloreste=ejecutar($buscoloreste);
			$datbusloreste=assoc_a($repbusloreste);
			$elproides=$datbusloreste['id_insumo'];
			$buscarlproducto=("select tbl_insumos_almacen.cantidad from tbl_insumos_almacen where
                                                      tbl_insumos_almacen.id_insumo=$elproides and
                                                      tbl_insumos_almacen.id_dependencia=$dep;");

			$repbuscarlproducto=ejecutar($buscarlproducto);
			$databuscalprodu=assoc_a($repbuscarlproducto);
			$cantidadactua=$databuscalprodu['cantidad'];
			$nuevacantidad=$cantidadactua-$cantidades;
			$actualizolosinsumos=("update tbl_insumos_almacen set cantidad=$nuevacantidad where
                                                      tbl_insumos_almacen.id_insumo=$elproides and
                                                      tbl_insumos_almacen.id_dependencia=$dep;");
			$repactualizolosinsumos=ejecutar($actualizolosinsumos);
			$pasogtb=1;
      ///REGISTRAR MOVIMIENTO DE INVENTARIO franklin monsalve
      $Movimiento='VENTA';///que tipo de movimiento se esta haciendo
      $TipoMovimiento=2;//Es una entrada(1) o una salida(2)
      $Descripcion="SALIDA SEGUN PROCESO:$elprocesocargado";//como se puede comprovar el movimiento
      $RegistroMovimientossql=("INSERT INTO tbl_insumos_movimientos (id_insumo,id_dependencia,id_proveedor,movimiento,tipo_movimiento,precio_unitario,cantida,cantidad_almacen,cantida_actual,id_admin,nota_movimiento)
                        VALUES ('$elproides','$dep','$provees','$Movimiento','$TipoMovimiento','$precioprodu','$cantidades','$cantidadactua','$nuevacantidad','$elid','$Descripcion');");
      $RegistroMov=ejecutar($RegistroMovimientossql);
     //fin REGISTRAR MOVIMIENTO DE INVENTARIO
		}
   if($pasogtb==1){
	  $buscpararestar=("select coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario,coberturas_t_b.monto_actual from coberturas_t_b where coberturas_t_b.id_cobertura_t_b=$coberturaes;");
	   $repbuscpararestar=ejecutar($buscpararestar);
	   $datapararestar=assoc_a($repbuscpararestar);
	   $elmontoqtiene=$datapararestar['monto_actual'];
	   $larestaes= $elmontoqtiene-$montoadescargar;
	   $actualizolacober=("update coberturas_t_b set monto_actual=$larestaes where coberturas_t_b.id_cobertura_t_b=$coberturaes;");
	   $repactualizolacober=ejecutar($actualizolacober);

	  echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
      <tr>

         <td colspan=8 class=\"titulo_seccion\">El proceso No. $elprocesocargado se ha cargado exitosamente!!</td>
         <td colspan=7 class=\"titulo_seccion\" title=\"Imprimir reporte\">";

			$url="'views01/farmaext7.php?elproceso=$elprocesocargado'";?>
			<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a>
			<?echo"</td>
           <td class=\"titulo_seccion\"><label title=\"Regresar a Orden de M&eacute;dicamento\"class=\"boton\" style=\"cursor:pointer\" onclick=\"reg_farma()\" >Regresar</label></td>
       </tr>
     </table>";
	}
}
/* **** Se registra lo que hizo el usuario**** **/


$log="REGISTRO LA ORDEN NUMERO $elprocesocargado DEL ID TITULAR $estitu Y ID BENEFICIARIO $esbene COMENTARIO $comentarioes , TIPO SERVICIO $idtiposervicio Y SERVICIO $elservicio ";
logs($log,$ip,$elid);

/* **** Fin de lo que hizo el usuario **** */
?>
