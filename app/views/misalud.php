<?php
include ("../../lib/jfunciones.php");
echo cabecera(MiSalud);
?>

</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table  class="tabla_cabecera" >
  <tr>
      <tr>
      <td class="logo"><img src="<?php echo ruta_imagenes."/logo.png"; ?> alt="" title=""></td>
      <td class="boton_cabecera"><img src="<?php echo ruta_imagenes."/opciones.png"; ?> alt="" title=""></td>
      <td class="boton_cabecera"><img src="<?php echo ruta_imagenes."/ayuda.png"; ?> alt="" title=""></td>
      <td class="boton_cabecera"><img src="<?php echo ruta_imagenes."/agenda.png"; ?> alt="" title=""></td>
      <td class="boton_cabecera"><img src="<?php echo ruta_imagenes."/mensajeria.png"; ?> alt="" title=""></td>
    </tr>
   </tr>
</table>
<table class="tabla_cabecera2">
  <tr>
      <td class="usuario">Hola! Jocp, Hoy es Miercoles 08/10/2008</td>
      <td class="c_buscar">Cédula <input type="text" class="campo_busqueda"></td>
      <td class="c_buscar">Nombre <input type="text" class="campo_busqueda"></td>
      <td class="c_buscar2">Buscar</td>
    </tr>
</table>
<table   class="tabla_cabecera3">
 <tr>
	 <td align="center">
	
	<style type="text/css">
<!--
@import"nav-h.css";
-->
/* Root = Horizontal, Secondary = Vertical */
ul#navmenu-h {
  margin: 0;
  border: 0 none;
  padding: 0;
  width: 700px; /*For KHTML*/
  list-style: none;
  height: 24px;
}

ul#navmenu-h li {
  margin: 0;
  border: 0 none;
  padding: 0;
  float: left; /*For Gecko*/
  display: inline;
  list-style: none;
  position: relative;
  height: 24px;
}

ul#navmenu-h ul {
  margin: 0;
  border: 0 none;
  padding: 0;
  width: 160px;
  list-style: none;
  display: none;
  position: absolute;
  top: 24px;
  left: 0;
}

ul#navmenu-h ul:after /*From IE 7 lack of compliance*/{
  clear: both;
  display: block;
  font: 1px/0px serif;
  content: ".";
  height: 0;
  visibility: hidden;
}

ul#navmenu-h ul li {
  width: 160px;
  float: left; /*For IE 7 lack of compliance*/
  display: block !important;
  display: inline; /*For IE*/
}

/* Root Menu */
ul#navmenu-h a {
  border: 1px solid #FFF;
  border-right-color: #CCC;
  border-bottom-color: #CCC;
  padding: 0 6px;
  float: none !important; /*For Opera*/
  float: left; /*For IE*/
  display: block;
  background: #FCC2B8;
  color: #000000;
  font: bold 10px/22px Verdana, Arial, Helvetica, sans-serif;
  text-decoration: none;
  height: auto !important;
  height: 1%; /*For IE*/
}

/* Root Menu Hover Persistence */
ul#navmenu-h a:hover,
ul#navmenu-h li:hover a,
ul#navmenu-h li.iehover a {
  background: #FDD6B9;
  color: #FDFCFC;
}

/* 2nd Menu */
ul#navmenu-h li:hover li a,
ul#navmenu-h li.iehover li a {
  float: none;
  background: #FCC2B8;
  color: #000000;
}

/* 2nd Menu Hover Persistence */
ul#navmenu-h li:hover li a:hover,
ul#navmenu-h li:hover li:hover a,
ul#navmenu-h li.iehover li a:hover,
ul#navmenu-h li.iehover li.iehover a {
  background: #FDD6B9;
  color: #FDFCFC;
}

/* 3rd Menu */
ul#navmenu-h li:hover li:hover li a,
ul#navmenu-h li.iehover li.iehover li a {
  background: #FCC2B8;
  color: #000000;
}

/* 3rd Menu Hover Persistence */
ul#navmenu-h li:hover li:hover li a:hover,
ul#navmenu-h li:hover li:hover li:hover a,
ul#navmenu-h li.iehover li.iehover li a:hover,
ul#navmenu-h li.iehover li.iehover li.iehover a {
  background: #FDD6B9;
  color: #FDFCFC;
}

/* 4th Menu */
ul#navmenu-h li:hover li:hover li:hover li a,
ul#navmenu-h li.iehover li.iehover li.iehover li a {
  background: #EEE;
  color: #000000;
}

/* 4th Menu Hover */
ul#navmenu-h li:hover li:hover li:hover li a:hover,
ul#navmenu-h li.iehover li.iehover li.iehover li a:hover {
  background: #CCC;
  color: #FDFCFC;
}

ul#navmenu-h ul ul,
ul#navmenu-h ul ul ul {
  display: none;
  position: absolute;
  top: 0;
  left: 160px;
}

/* Do Not Move - Must Come Before display:block for Gecko */
ul#navmenu-h li:hover ul ul,
ul#navmenu-h li:hover ul ul ul,
ul#navmenu-h li.iehover ul ul,
ul#navmenu-h li.iehover ul ul ul { 
  display: none;
}

ul#navmenu-h li:hover ul,
ul#navmenu-h ul li:hover ul,
ul#navmenu-h ul ul li:hover ul,
ul#navmenu-h li.iehover ul,
ul#navmenu-h ul li.iehover ul,
ul#navmenu-h ul ul li.iehover ul {
  display: block;
}
</style>

  <ul id="navmenu-h">
    <li><a href="#">Clientes <img src="<?php echo ruta_imagenes."/abajo.png"; ?> border=0></a>
        <ul>
           <li><a href="#">Consultas <img src="<?php echo ruta_imagenes."/derecha.png"; ?> border=0></a>
            <ul> 
               <li><a href="#">Archivo</a></li>
               <li><a href="#">Cliente</a></li>
               <li><a href="#">Cobertura</a></li>
               <li><a href="#">Preventivas</a></li>
               <li><a href="#">Comentarios del Proceso</a></li>
               <li><a href="#">Gastos</a></li>
               <li><a href="#">Procesos</a></li>
            </ul>
           <li><a href="#">Registros <img src="<?php echo ruta_imagenes."/derecha.png"; ?> border=0></a>
             <ul> 
               <li><a href="#">Archivo</a></li>
               <li><a href="#">Cliente</a></li>
               <li><a href="#">Citas</a></li>
               <li><a href="#">Estados de Clientes</a></li>
               <li><a href="#">Gastos Nuevos</a></li>
               <li><a href="#">Gastos Viejos</a></li>
               <li><a href="#">Estado del Proceso</a></li>
               <li><a href="#">Parentesco</a></li>
               <li><a href="#">Tipo de Beneficiario</a></li>
            </ul> 
            <li><a href="#">Actualizaciones <img src="<?php echo ruta_imagenes."/derecha.png"; ?> border=0></a>
            <ul> 
               <li><a href="#">Archivo</a></li>
               <li><a href="#">Cliente</a></li>
               <li><a href="#">Citas</a></li>
               <li><a href="#">Estados de Clientes</a></li>
               <li><a href="#">Proceso</a></li>
               <li><a href="#">Parentesco</a></li>
               <li><a href="#">Tipo de Beneficiario</a></li>
            </ul> 
        </ul>  
    </li>
    <li><a href="#">Entes <img src="<?php echo ruta_imagenes."/abajo.png"; ?> border=0></a>
          <ul>
           <li><a href="#">Consultas <img src="<?php echo ruta_imagenes."/derecha.png";?> border=0></a>
            <ul> 
               <li><a href="#">Ente</a></li>
               <li><a href="#">P&oacute;liza</a></li>
            </ul>
           <li><a href="#">Registros <img src="<?php echo ruta_imagenes."/derecha.png"; ?> border=0></a>
             <ul> 
               <li><a href="#">Cargo</a></li>
               <li><a href="#">Ente</a></li>
               <li><a href="#">P&oacute;liza</a></li>
               <li><a href="#">Propiedad P&oacute;liza</a></li>
               <li><a href="#">Prima</a></li>
            </ul> 
            <li><a href="#">Actualizaciones <img src="<?php echo ruta_imagenes."/derecha.png"; ?> border=0></a>
            <ul> 
               <li><a href="#">Cargo</a></li>
               <li><a href="#">Ente</a></li>
               <li><a href="#">P&oacute;liza</a></li>
               <li><a href="#">Propiedad P&oacute;liza</a></li>
               <li><a href="#">Prima</a></li>
               <li><a href="#">Coberturas de Entes</a></li>
            </ul> 
      </ul>
    </li>
    <li><a href="#">Proveedores <img src="<?php echo ruta_imagenes."/abajo.png"; ?> border=0></a>
      <ul>
        <li><a href="#">Consultas <img src="<?php echo ruta_imagenes."/derecha.png"; ?> border=0></a>
            <ul> 
               <li><a href="#">Proveedor</a></li>
               <li><a href="#">Baremos</a></li>
            </ul>
           <li><a href="#">Registros <img src="<?php echo ruta_imagenes."/derecha.png"; ?> border=0></a>
             <ul> 
               <li><a href="#">Proveedora</a></li>
               <li><a href="#">Servicios del Proveedor</a></li>
               <li><a href="#">Servicios Prestados</a></li>
               <li><a href="#">Baremos</a></li>
            </ul> 
            <li><a href="#">Actualizaciones <img src="<?php echo ruta_imagenes."/derecha.png"; ?> border=0></a>
            <ul> 
               <li><a href="#">Proveedor</a></li>
               <li><a href="#">Servicios del Proveedor</a></li>
               <li><a href="#">Servicos Prestados</a></li>
               <li><a href="#">Baremos</a></li>
            </ul> 
      </ul>

    </li>
    <li><a href="#">Administrativo <img src="<?php echo ruta_imagenes."/abajo.png"; ?> border=0></a>
         <ul>
        <li><a href="#">Consultas <img src="<?php echo ruta_imagenes."/derecha.png"; ?> border=0></a>
            <ul> 
               <li><a href="#">Proveedor</a></li>
               <li><a href="#">Baremos</a></li>
            </ul>
           <li><a href="#">Registros <img src="<?php echo ruta_imagenes."/derecha.png"; ?> border=0></a>
             <ul> 
               <li><a href="#">Proveedora</a></li>
               <li><a href="#">Servicios del Proveedor</a></li>
               <li><a href="#">Servicios Prestados</a></li>
               <li><a href="#">Baremos</a></li>
            </ul> 
            <li><a href="#">Actualizaciones <img src="<?php echo ruta_imagenes."/derecha.png"; ?> border=0></a>
            <ul> 
               <li><a href="#">Proveedor</a></li>
               <li><a href="#">Servicios del Proveedor</a></li>
               <li><a href="#">Servicos Prestados</a></li>
               <li><a href="#">Baremos</a></li>
            </ul> 
      </ul>
    </li>
    <li><a href="#">Compras <img src="<?php echo ruta_imagenes."/abajo.png"; ?> border=0></a></li>
    <li><a href="#">Reportes <img src="<?php echo ruta_imagenes."/abajo.png"; ?> border=0></a></li>
    <li><a href="#">Administrador <img src="<?php echo ruta_imagenes."/abajo.png"; ?> border=0></a></li>
  </ul>
	
	 </td>
    </tr>
</table>

<table  class="tabla_cabecera4">
 
<tr>
    <td >contenido </td>
  </tr>
 
</table>

temp

<?php
echo pie();
?>
