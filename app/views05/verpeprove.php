<?php 
//control de pedido: Procesar pedido,Ver pedido,Anular pedido
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$_SESSION['opcionpe']=0;
$buscarpedidos=("select admin.nombres,admin.apellidos from admin,tbl_admin_dependencias,tbl_dependencias where admin.id_admin=tbl_admin_dependencias.id_admin and tbl_admin_dependencias.id_dependencia=tbl_dependencias.id_dependencia and
tbl_dependencias.esalmacen=1 and admin.id_admin=$elid;");
$repbuscarpedidos=ejecutar($buscarpedidos);
$cuantosusu=num_filas($repbuscarpedidos);
$dependciacentral=("select tbl_dependencias.id_dependencia,tbl_dependencias.dependencia from tbl_dependencias,tbl_admin_dependencias where tbl_dependencias.esalmacen=1 and tbl_dependencias.id_dependencia=tbl_admin_dependencias.id_dependencia and tbl_admin_dependencias.id_admin=$elid order by tbl_dependencias.dependencia;");
$repdepcentral=ejecutar($dependciacentral);
if ($cuantosusu<=0){
	 echo"
      <table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
                   <br>
                   <tr>
                      <td colspan=4 class=\"titulo_seccion\">Usuario desactivado para realizar esta operaci&oacute;n</td>
                   </tr>
              </table>  
     ";
	}else{
?>
<style type="text/css">
a.tooltips {
  position: relative;
  display: inline;
}
a.tooltips span {
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
a.tooltips span:after {
  content: '';
  position: absolute;
  bottom: 100%;
  left: 50%;
  margin-left: -8px;
  width: 0; height: 0;
  border-bottom: 8px solid #ABFFAB;
  border-right: 8px solid transparent;
  border-left: 8px solid transparent;
}
a:hover.tooltips span {
  visibility: visible;
  opacity: 0.7;
  top: 30px;
  left: 50%;
  margin-left: -76px;
  z-index: 999;
}

</style>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Control de pedidos proveedores</td>
     </tr>
          <br>
        <tr>
              <td class="tdtitulos" colspan="1">Opciones del pedido:</td>
              <td class="tdcampos"  colspan="1">
			   <select id="estadopedi" class="campos"  style="width: 200px;" >
			        <option value=0></option>   
			        <option value=1>Procesar pedido</option>
				<option value=2>Ver pedido</option>
				<option value=3>Anular pedido</option>
           		</select></td> 
			  <td class="tdtitulos" colspan="1">Almacen:</td>
                        <td class="tdcampos"  colspan="1"> 
			  <select id="almacenes" class="campos"  style="width: 310px;" >
			        <option value=""></option>
           <?php  while($almacentral=asignar_a($repdepcentral,NULL,PGSQL_ASSOC)){?>
						<option   value="<?php echo "$almacentral[id_dependencia]"?>"> <?php echo "$almacentral[dependencia]"?></option>
			      <?php
			             }
		              ?>
		      </select> </td>
			  <td><label title="Buscar pedidos"  class="boton" style="cursor:pointer" onclick="buspPro()" >Buscar</label></td>  
              <td><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
        </tr>
</table>

<div id="mispedprove"></div>


<?php }?>
