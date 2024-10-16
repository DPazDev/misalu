<?
include ("../../lib/jfunciones.php");
sesion();
$estpedido=$_POST['estapedido'];
$dependcia=$_POST['dependencia'];
$fechai=$_POST['fe1'];
$fechaf=$_POST['fe2'];
$elid=$_SESSION['id_usuario_'.empresa];
$estanendepen=0;
$buscadepenusuario=("select tbl_admin_dependencias.id_dependencia,tbl_admin_dependencias.activar from tbl_admin_dependencias where tbl_admin_dependencias.activar=1 and
tbl_admin_dependencias.id_admin=$elid;");
$repbuscardepeusu=ejecutar($buscadepenusuario);
if($estpedido==1){
	$titulo="PEDIDOS PENDIENTES PARA LA DEPENDENCIA";
$lospedidos=("select
       tbl_ordenes_pedidos.id_orden_pedido,tbl_ordenes_pedidos.id_dependencia,
       tbl_ordenes_pedidos.no_orden_pedido,tbl_ordenes_pedidos.fecha_pedido,
       tbl_ordenes_pedidos.fecha_despachado,tbl_ordenes_pedidos.estatus,
       tbl_ordenes_pedidos.id_admin,tbl_ordenes_pedidos.id_dependencia_saliente,
       admin.nombres,admin.apellidos, tbl_dependencias.dependencia as quienlohizo
    from
       tbl_ordenes_pedidos,admin,tbl_dependencias
    where
       tbl_ordenes_pedidos.id_dependencia=$dependcia and
       tbl_ordenes_pedidos.id_admin=admin.id_admin and
       tbl_ordenes_pedidos.id_dependencia_saliente = tbl_dependencias.id_dependencia and
       tbl_ordenes_pedidos.estatus=$estpedido order by tbl_ordenes_pedidos.fecha_pedido desc;");

       $replospedidos=ejecutar($lospedidos);
       $cuantos=num_filas($replospedidos);
       $nopedido="No.";
       $realpor="Realizado por.";
       $depenpor="Dependencia.";
       $lafecha="Fecha del pedido.";
	}
if($estpedido==2){
	$titulo="PEDIDOS DESPACHADOS PARA LA DEPENDENCIA";
	 $lospedidos=("select
       tbl_ordenes_pedidos.id_orden_pedido,tbl_ordenes_pedidos.id_dependencia,
       tbl_ordenes_pedidos.no_orden_pedido,
       tbl_ordenes_pedidos.fecha_pedido,tbl_ordenes_pedidos.fecha_despachado,tbl_ordenes_pedidos.estatus,
       tbl_ordenes_pedidos.id_admin,tbl_ordenes_pedidos.id_dependencia_saliente,
       admin.nombres,admin.apellidos, tbl_dependencias.dependencia as quienlohizo
    from
       tbl_ordenes_pedidos,admin,tbl_dependencias
    where
       tbl_ordenes_pedidos.id_dependencia_saliente=$dependcia and
       tbl_ordenes_pedidos.id_admin=admin.id_admin and
       tbl_ordenes_pedidos.id_dependencia_saliente = tbl_dependencias.id_dependencia and
       tbl_ordenes_pedidos.estatus=$estpedido order by fecha_pedido desc;");
       $replospedidos=ejecutar($lospedidos);
       $cuantos=num_filas($replospedidos);
       $nopedido="No.";
       $realpor="Encargado.";
       $depenpor="Dependencia.";
       $lafecha="Fecha de despacho.";
}
if($estpedido==3){
$titulo="PEDIDOS RECIBIDOS POR LA DEPENDENCIA";
	 $lospedidos=("select tbl_ordenes_entregas.id_orden_entrega,
tbl_ordenes_entregas.no_orden_entrega,
tbl_dependencias.dependencia,admin.nombres,admin.apellidos,
tbl_ordenes_entregas.fecha_emision
from tbl_ordenes_entregas,admin,tbl_dependencias,tbl_ordenes_pedidos
where
tbl_ordenes_entregas.id_admin= admin.id_admin and
tbl_ordenes_pedidos.id_orden_pedido=tbl_ordenes_entregas.id_orden_pedido and
tbl_ordenes_pedidos.estatus=$estpedido and
tbl_ordenes_entregas.id_dependencia=tbl_dependencias.id_dependencia and
tbl_ordenes_entregas.id_dependencia=$dependcia and
tbl_ordenes_pedidos.fecha_despachado between '$fechai' and '$fechaf'
order by tbl_ordenes_entregas.fecha_emision;");
$replospedidos=ejecutar($lospedidos);
$cuantos=num_filas($replospedidos);
	if($estpedido==3){
	  $nopedido="No.";
          $realpor="Despachado por.";
          $depenpor="A la Dependencia.";
          $lafecha="Fecha recibido.";
	}
}
if($estpedido==4){
	$titulo="ANULAR PEDIDOS REALIZADOS POR LA DEPENDENCIA";
      $lospedidos=("select
       tbl_ordenes_pedidos.id_orden_pedido,tbl_ordenes_pedidos.id_dependencia,
       tbl_ordenes_pedidos.no_orden_pedido,
       tbl_ordenes_pedidos.fecha_pedido,tbl_ordenes_pedidos.fecha_despachado,tbl_ordenes_pedidos.estatus,
       tbl_ordenes_pedidos.id_admin,tbl_ordenes_pedidos.id_dependencia_saliente,
       admin.nombres,admin.apellidos, tbl_dependencias.dependencia as quienlohizo
    from
       tbl_ordenes_pedidos,admin,tbl_dependencias
    where
       tbl_ordenes_pedidos.id_dependencia_saliente=$dependcia and
       tbl_ordenes_pedidos.id_admin=admin.id_admin and
       tbl_ordenes_pedidos.id_dependencia = tbl_dependencias.id_dependencia and
       tbl_ordenes_pedidos.estatus=1 order by fecha_pedido desc;");

			 $replospedidos=ejecutar($lospedidos);
       $cuantos=num_filas($replospedidos);
       $nopedido="No.";
       $realpor="Encargado.";
       $depenpor="Dependencia.";
       $lafecha="Fecha de despacho.";
}
if($estpedido==5){
	$titulo="PEDIDOS REALIZADOS POR LA DEPENDENCIA";
	 $lospedidos=("select
       tbl_ordenes_pedidos.id_orden_pedido,tbl_ordenes_pedidos.id_dependencia,
       tbl_ordenes_pedidos.no_orden_pedido,tbl_ordenes_pedidos.fecha_pedido,

       tbl_ordenes_pedidos.id_admin,tbl_ordenes_pedidos.id_dependencia_saliente,
       admin.nombres,admin.apellidos, tbl_dependencias.dependencia as quienlohizo
    from
       tbl_ordenes_pedidos,admin,tbl_dependencias
    where
       tbl_ordenes_pedidos.id_dependencia_saliente=$dependcia and
       tbl_ordenes_pedidos.id_admin=admin.id_admin and
       tbl_ordenes_pedidos.id_dependencia_saliente = tbl_dependencias.id_dependencia and
       tbl_ordenes_pedidos.estatus<>4 and
       tbl_ordenes_pedidos.fecha_pedido between '$fechai' and '$fechaf' order by tbl_ordenes_pedidos.fecha_pedido desc;");
       $replospedidos=ejecutar($lospedidos);
       $cuantos=num_filas($replospedidos);
       $nopedido="No. Pedido.";
       $realpor="Realizado por.";
       $depenpor="Dependencia.";
       $lafecha="Fecha del pedido.";
	}
if($cuantos==0){
echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
<br>
     <tr>
       <br>
         <td colspan=4 class=\"titulo_seccion\">No hay pedidos actualmente</td>
     </tr>
</table>";
}else{
echo	$BuscarDependencia=("select tbl_admin_dependencias.id_dependencia,tbl_admin_dependencias.activar,tbl_dependencias.dependencia from tbl_admin_dependencias,tbl_dependencias where tbl_dependencias.id_dependencia =tbl_admin_dependencias.id_dependencia  and tbl_admin_dependencias.activar=1 and
tbl_admin_dependencias.id_admin=$elid and tbl_admin_dependencias.id_dependencia=$dependcia;");
	$RespDepen=ejecutar($BuscarDependencia);
$datdependencia=assoc_a($RespDepen);
$NombreDependecia=$datdependencia['dependencia'];
?>
<table class="tabla_citas colortable"  cellpadding=0 cellspacing=0>
	<tr>
		<td class="titulo_seccion" colspan="5"><?php echo "$titulo: $NombreDependecia"?></td>
	</tr>
        <tr>
        	<th class="tdtitulos"><?echo $nopedido?></th>
         	<th class="tdtitulos"><?echo $realpor?></th>
				 	<th class="tdtitulos"><?echo $depenpor?></th>
				 	<th class="tdtitulos">-<?echo $lafecha?>-</th>
				 	<th class="tdtitulos">Opci&oacute;n.</th>
				</tr>
			<?
			  while($datpedido=asignar_a($replospedidos,NULL,PGSQL_ASSOC)){
				if($estpedido==1){
					 $numeamostrar=$datpedido[no_orden_pedido];
					 $personamostrar="$datpedido[nombres] $datpedido[apellidos] ";
					 $depenamostrar=$datpedido[quienlohizo];
					 $fechamostrar=$datpedido[fecha_pedido];
                                         if($dependcia==89){
					 $opcamostrar="<label title='Procesar el pedido' class='boton' style='cursor:pointer' onclick='pedideid($datpedido[id_orden_pedido])' >Procesar</label><label title='Modificar el pedido' class='boton' style='cursor:pointer' onclick='ModfElPed($datpedido[id_orden_pedido] )' >Modificar</label>
					               <label title='Anular el pedido' class='boton' style='cursor:pointer' onclick='AnulaElPed($datpedido[id_orden_pedido])' >Anu.</label>";
					 }else{
					     $opcamostrar="<label title='Procesar el pedido' class='boton' style='cursor:pointer' onclick='pedideid($datpedido[id_orden_pedido] )' >Procesar</label><label title='Modificar el pedido' class='boton' style='cursor:pointer' onclick='ModfElPed($datpedido[id_orden_pedido] )' >Modificar</label>";

						 }
					}
					if($estpedido==2){

					    $numeamostrar=$datpedido[no_orden_pedido];
   				            $personamostrar="$datpedido[nombres] $datpedido[apellidos] ";
					    $depenamostrar=$datpedido[quienlohizo];
				            $fechamostrar=$datpedido[fecha_despachado];
					    $dependenciadelpedido=$datpedido[id_dependencia_saliente];
					    while($lasdepenusu=asignar_a($repbuscardepeusu,NULL,PGSQL_ASSOC)){
								   $usuarioendepen=$lasdepenusu[id_dependencia];
								   if($usuarioendepen==$dependenciadelpedido){
									  $estanendepen=1;
									}
								}
                                                    if(($elid==60)||($elid==120)|| ($elid==326) ||($elid==246)||($elid==93) || ($elid==264)||($elid==99)||($elid==280)||($elid==294)||($elid==314) ||($elid==132) || ($elid==335) || ($elid==441)){
                                                    $url="'views05/reportpediprov1.php?idordepe=$datpedido[id_orden_pedido]'";
						    $opcamostrar="<label title='Ver pedido despachado' class='boton' style='cursor:pointer' onclick='Retomapedido($datpedido[id_orden_pedido])' >Ver pedido</label>
                                                    <a href=\"javascript: imprimir($url)\" class=\"boton\">Imprimir</a>
                                                    ";
                                                    }else{
                                                         $opcamostrar="<label title='Ver pedido despachado' class='boton' style='cursor:pointer' onclick='Retomapedido($datpedido[id_orden_pedido])' >Ver pedido</label>";
                                                          }
					}
					 if($estpedido==3){
						  $numeamostrar=$datpedido[no_orden_entrega];
					          $personamostrar="$datpedido[nombres] $datpedido[apellidos] ";
					          $depenamostrar=$datpedido[dependencia];
					          $fechamostrar=$datpedido[fecha_emision] ;
					          $opcamostrar="<label title='Ver pedido recibido' class='boton' style='cursor:pointer' onclick='Retomapedido1($datpedido[id_orden_entrega],1 )' >Ver pedido</label>";
					 }
				          if($estpedido==4){
                                                    $numeamostrar=$datpedido[no_orden_pedido];
                                                    $personamostrar="$datpedido[nombres] $datpedido[apellidos] ";
                                                    $depenamostrar=$datpedido[quienlohizo];
                                                    $fechamostrar=$datpedido[fecha_pedido];
                                                    $opcamostrar="<label title='Ver pedido despachado' class='boton' style='cursor:pointer' onclick='Retomapedido2($datpedido[id_orden_pedido])' >Ver pedido</label>";
                                        }
										 if($estpedido==5){
                                                    $numeamostrar=$datpedido[no_orden_pedido];
                                                    $personamostrar="$datpedido[nombres] $datpedido[apellidos] ";
													$busladepen=$datpedido['id_dependencia'];
													$ladepenesq=("select tbl_dependencias.dependencia from tbl_dependencias where
                                                                             tbl_dependencias.id_dependencia=$busladepen");
													$repladepenesq=ejecutar($ladepenesq);
													$datadepen=assoc_a($repladepenesq);
                                                    $depenamostrar=$datadepen[dependencia];
                                                    $fechamostrar=$datpedido[fecha_pedido];
                                                    $opcamostrar="<label title='Ver pedido despachado' class='boton' style='cursor:pointer' onclick='Retomapedido22($datpedido[id_orden_pedido] )' >Ver pedido</label>";
                                        }

				if(($estanendepen==0) && ($estpedido==2) && ($elid!=326) && ($elid!=60) &&($elid!=1) &&($elid!=120)&&($elid!=246)&&($elid!=93)&&($elid!=264)&&($elid!=99)&&($elid!=280) &&($elid!=294)&&($elid!=314)&&($elid!=132)&&($elid!=335) &&($elid!=441)){
			        echo"
                                <table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
                                <tr>
                                  <td colspan=4 class=\"titulo_seccion\">El usuario no pertenece a la dependencia seleccionada</td>
                                  <td class=\"titulo_seccion\"><label title=\"Salir del Proceso\" class=\"boton\" style=\"cursor:pointer\" onclick=\"ira()\" >Salir</label></td>
                                </tr>
                               </table>";

				}else{
				echo"<tr>
                      <td class=\"tdcampos\">$numeamostrar</td>
				      <td class=\"tdcampos\">$personamostrar</td>
				      <td class=\"tdcampos\">$depenamostrar</td>
				      <td class=\"tdcampos\">$fechamostrar</td>
				      <td  class=\"tdcampos\">$opcamostrar</td>
                                     </tr>";
				}
			}

			?>
</table>
<input type="hidden" id="controlNavega" name="controlNavega"  value="<?php echo $dependcia;?>">
<?
}?>
