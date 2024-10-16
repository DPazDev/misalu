<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $cedulaclien=$_REQUEST['lacedula'];
  $buscliente=("select clientes.id_cliente from clientes where cedula='$cedulaclien';");
  $repbusclien=ejecutar($buscliente);
  $cuantoscliente=num_filas($repbusclien);
  if($cuantoscliente>=1){?>
       <label for="nombre" style="color: #FE2E2E">El cliente existe!
       <input type="checkbox" id="pvn" value=1 onclick="pasardata(<?echo $cedulaclien?>)">Cargar data?<br>
       </label>
  <?}else{
    }?>