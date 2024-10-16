function nuevoAjax(){
  var xmlhttp=false;
  try {
   // Creación del objeto ajax para navegadores diferentes a Explorer
   xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
   // o bien
   try {
     // Creación del objet ajax para Explorer
     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) {
     xmlhttp = false;
   }
  }

  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
   xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}

function ir(url){
  location.href = url;
}

function ventana(url){
        window.open(url,'window','scrollbars=1,width=650,height=350');
}	

function ventana2(url,ancho,alto){
        window.open(url,'window','scrollbars=1,width='+ancho+',height='+alto);
}

function buscarusuario(){
	var usuario,contenedor;
	contenedor = document.getElementById('buscarusuario');

	usuario = document.usuario.usuario.value;
	if(usuario.length>0){
		ajax=nuevoAjax();
		ajax.open("GET", "views07/buscarusuario.php?usuario="+usuario,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
			}
		}
		ajax.send(null);
	}
}

function buscarcedula(){
	var cedula,contenedor;
	contenedor = document.getElementById('buscarcedula');
	cedula = document.usuario.cedula.value;
	if(cedula.length==0){
	alert("El Campo cedula es Obligatorio.");
	}
		
	if(cedula.length>0){
		ajax=nuevoAjax();
		ajax.open("GET", "views07/buscarcedula.php?cedula="+cedula,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
			}
		}
		ajax.send(null);
	}
}

function buscarcedulac(){
	var cedula,contenedor;
	contenedor = document.getElementById('buscarcedulac');
	cedula = document.clientes.cedula.value;
	if(cedula.length==0){
	alert("El Campo cedula es Obligatorio.");
	}
		
	if(cedula.length>0){
		ajax=nuevoAjax();
		ajax.open("GET", "views01/buscarcedulac.php?cedula="+cedula,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
			}
		}
		ajax.send(null);
	}
}

function buscarcliente(){
	var cedula,contenedor;
	contenedor = document.getElementById('buscarcliente');
	cedula = document.clientes.cedula.value;
	if(cedula.length==0){
	alert("El Campo cedula es Obligatorio.");
	}
		
	if(cedula.length>0){
		ajax=nuevoAjax();
		ajax.open("GET", "views01/buscarcliente.php?cedula="+cedula,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
			}
		}
		ajax.send(null);
	}
}

function buscarestado(){
	var pais,contenedor;
	contenedor = document.getElementById('buscarestado');
	pais = document.clientes.pais.value;
	if(pais.length>0){
		ajax=nuevoAjax();
		ajax.open("GET", "views01/buscarestado.php?pais="+pais,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		ajax.send(null);
	}
}

function buscarciudad(){
	var estado,contenedor;
	contenedor = document.getElementById('buscarciudad');
	estado = document.clientes.estado.value;
	if(estado.length>0){
		ajax=nuevoAjax();
		ajax.open("GET", "views01/buscarciudad.php?estado="+estado,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		ajax.send(null);
	}
}

function verificarclave(){
	var clave,clave1,contenedor;
	contenedor = document.getElementById('verificarclave');

	clave = document.usuario.clave.value;
	clave1 = document.usuario.clave1.value;
	if(clave != clave1){
	alert("Las claves tienen que ser iguales.");
	}
	
}

function cliente(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
		registrar_clientes();
}


function registrar_clientes(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/clientes.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function ir_clientes(){
	var contenedor, nombres, apellidos, cedula, sexo, correo, tlf, celular, pais, estado, ciudad, rif, nit, dir, comen, tipcli;
	contenedor = document.getElementById('clientes');
	nombres = document.clientes.nombres.value;
	apellidos = document.clientes.apellidos.value;
	cedula = document.clientes.cedula.value;
	sexo = document.clientes.sexo.value;
	correo = document.clientes.correo.value;
	tlf = document.clientes.tlf.value;
	celular = document.clientes.celular.value;
	pais = document.clientes.pais.value;
	estado = document.clientes.estado.value;
	ciudad = document.clientes.ciudad.value;
	rif = document.clientes.rif.value; 
	nit = document.clientes.nit.value; 
	dir = document.clientes.dir.value;
	comen = document.clientes.comen.value;
	tipcli = document.clientes.tipcli.value;
	
	if(nombres.length==0 || apellidos.length==0 || tlf.length==0 || celular.length==0 || tipcli.length==0 || ciudad.length==0 ){
	alert("Algunos de los campos Obligatorios (*) estan vacios.");
	
	}
	else
{
	ajax=nuevoAjax();
	ajax.open("GET", "views01/reclientes.php?nombres="+nombres+"&apellidos="+apellidos+"&cedula="+cedula+"&sexo="+sexo+"&correo="+correo+"&tlf="+tlf+"&celular="+celular+"&pais="+pais+"&estado="+estado+"&ciudad="+ciudad+"&rif="+rif+"&nit="+nit+"&dir="+dir+"&comen="+comen+"&tipcli="+tipcli,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}
}

function act_clientes(){
	var contenedor, nombres, apellidos, cedula, sexo, correo, tlf, celular, pais, estado, ciudad, rif, nit, dir, comen, tipcli;
	contenedor = document.getElementById('clientes');
	nombres = document.clientes.nombres.value;
	apellidos = document.clientes.apellidos.value;
	cedula = document.clientes.cedula.value;
	sexo = document.clientes.sexo.value;
	correo = document.clientes.correo.value;
	tlf = document.clientes.tlf.value;
	celular = document.clientes.celular.value;
	pais = document.clientes.pais.value;
	estado = document.clientes.estado.value;
	ciudad = document.clientes.ciudad.value;
	rif = document.clientes.rif.value; 
	nit = document.clientes.nit.value; 
	dir = document.clientes.dir.value;
	comen = document.clientes.comen.value;
	tipcli = document.clientes.tipcli.value;
	
	if(nombres.length==0 || apellidos.length==0 || tlf.length==0 || celular.length==0 || tipcli.length==0 || ciudad.length==0 ){
	alert("Algunos de los campos Obligatorios (*) estan vacios.");
	
	}
	else
{
	ajax=nuevoAjax();
	ajax.open("GET", "views01/actclientes1.php?nombres="+nombres+"&apellidos="+apellidos+"&cedula="+cedula+"&sexo="+sexo+"&correo="+correo+"&tlf="+tlf+"&celular="+celular+"&pais="+pais+"&estado="+estado+"&ciudad="+ciudad+"&rif="+rif+"&nit="+nit+"&dir="+dir+"&comen="+comen+"&tipcli="+tipcli,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}
}

function actcliente(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
		actualizar_clientes();
}

function actualizar_clientes(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/actclientes.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function usuario(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
		registrar_usuario();
	

	}

function registrar_usuario(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/usuarios.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function ir_usuario(){
	var contenedor, nombres, apellidos, cedula, usuario, clave, clave1, sexo, correo, tlf, celular, pais, estado, ciudad, mes, dia, ano, dir, estadoc, dpto, cargo, paso, paso1;
	contenedor = document.getElementById('clientes');
	nombres = document.usuario.nombres.value;
	apellidos = document.usuario.apellidos.value;
	cedula = document.usuario.cedula.value;
	usuario = document.usuario.usuario.value;
	clave = document.usuario.clave.value; 
	clave1 = document.usuario.clave1.value; 
	sexo = document.usuario.sexo.value;
	correo = document.usuario.correo.value;
	tlf = document.usuario.tlf.value;
	celular = document.usuario.celular.value;
	pais = document.usuario.pais.value;
	estado = document.usuario.estado.value;
	ciudad = document.usuario.ciudad.value;
	mes = document.usuario.mes.value;
	dia = document.usuario.dia.value;
	ano = document.usuario.ano.value;
	dir = document.usuario.dir.value;
	estadoc = document.usuario.estadoc.value;
	dpto = document.usuario.dpto.value;
	cargo = document.usuario.cargo.value;
	clave=calcMD5(clave);
	
	if(cedula.length==0 || nombres.length==0 || apellidos.length==0 || clave.length==0 || clave1.length==0 || dpto.length==0 || ciudad.length==0 ){
	alert("Algunos de los campos Obligatorios (*) estan vacios.");
	
	}
	else
{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reusuarios.php?nombres="+nombres+"&apellidos="+apellidos+"&cedula="+cedula+"&usuario="+usuario+"&clave="+clave+"&sexo="+sexo+"&correo="+correo+"&tlf="+tlf+"&celular="+celular+"&pais="+pais+"&estado="+estado+"&ciudad="+ciudad+"&mes="+mes+"&dia="+dia+"&ano="+ano+"&dir="+dir+"&estadoc="+estadoc+"&dpto="+dpto+"&cargo="+cargo,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}
}

function act_usuarios(){
	var contenedor, nombres, apellidos, cedula, usuario, clave, clave1, sexo, correo, tlf, celular, pais, estado, ciudad, mes, dia, ano, dir, estadoc, dpto, cargo, paso, paso1;
	contenedor = document.getElementById('clientes');
	nombres = document.usuario.nombres.value;
	apellidos = document.usuario.apellidos.value;
	cedula = document.usuario.cedula.value;
	usuario = document.usuario.usuario.value;
	clave = document.usuario.clave.value; 
	clave1 = document.usuario.clave1.value; 
	sexo = document.usuario.sexo.value;
	correo = document.usuario.correo.value;
	tlf = document.usuario.tlf.value;
	celular = document.usuario.celular.value;
	pais = document.usuario.pais.value;
	estado = document.usuario.estado.value;
	ciudad = document.usuario.ciudad.value;
	mes = document.usuario.mes.value;
	dia = document.usuario.dia.value;
	ano = document.usuario.ano.value;
	dir = document.usuario.dir.value;
	estadoc = document.usuario.estadoc.value;
	dpto = document.usuario.dpto.value;
	cargo = document.usuario.cargo.value;
	clave=calcMD5(clave);
	
	if(nombres.length==0 || apellidos.length==0 || clave.length==0 || clave1.length==0 || dpto.length==0 || ciudad.length==0 ){
	alert("Algunos de los campos Obligatorios (*) estan vacios.");
	
	}
	else
{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/actusuarios.php?nombres="+nombres+"&apellidos="+apellidos+"&cedula="+cedula+"&usuario="+usuario+"&clave="+clave+"&sexo="+sexo+"&correo="+correo+"&tlf="+tlf+"&celular="+celular+"&pais="+pais+"&estado="+estado+"&ciudad="+ciudad+"&mes="+mes+"&dia="+dia+"&ano="+ano+"&dir="+dir+"&estadoc="+estadoc+"&dpto="+dpto+"&cargo="+cargo,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}
}



function pais(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
		registrar_pais();
	

	}

function registrar_pais(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/pais.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function ir_pais(){
	var contenedor, nombre;
	contenedor = document.getElementById('clientes');
	nombre = document.pais.nombre.value;
	ajax=nuevoAjax();
	ajax.open("GET", "views07/repais.php?nombre="+nombre,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function estado(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
		registrar_estado();
	

	}

function registrar_estado(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/estado.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function ir_estado(){
	var contenedor, nombre;
	contenedor = document.getElementById('clientes');
	pais = document.estado.pais.value;
	nombre = document.estado.nombre.value;
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reestado.php?nombre="+nombre+"&pais="+pais,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function ciudad(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
		registrar_ciudad();
	

	}

function registrar_ciudad(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/ciudad.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function ir_ciudad(){
	var contenedor, nombre;
	contenedor = document.getElementById('clientes');
	estado = document.ciudad.estado.value;
	nombre = document.ciudad.nombre.value;
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reciudad.php?nombre="+nombre+"&estado="+estado,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}


function dpto(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
		registrar_dpto();
	}

function registrar_dpto(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/dpto.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function ir_dpto(){
	var contenedor, nombre;
	contenedor = document.getElementById('clientes');
	nombre = document.dpto.nombre.value;
	ajax=nuevoAjax();
	ajax.open("GET", "views07/redpto.php?nombre="+nombre,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function cargo(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
		registrar_cargo();
	}

function registrar_cargo(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/cargo.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function ir_cargo(){
	var contenedor, nombre;
	contenedor = document.getElementById('clientes');
	nombre = document.cargo.nombre.value;
	ajax=nuevoAjax();
	ajax.open("GET", "views07/recargo.php?nombre="+nombre,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}


function servicios(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
		registrar_servicios();
	}

function registrar_servicios(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/servicios.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function ir_servicios(){
	var contenedor, nombre;
	contenedor = document.getElementById('clientes');
	nombre = document.servicios.nombre.value;
	ajax=nuevoAjax();
	ajax.open("GET", "views07/reservicios.php?nombre="+nombre,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}



function caracteristicas(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
		registrar_caracteristicas();
	}

function registrar_caracteristicas(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/caracteristicas.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function ir_caracteristicas(){
	var contenedor, nombre;
	contenedor = document.getElementById('clientes');
	nombre = document.caracteristicas.nombre.value;
	ajax=nuevoAjax();
	ajax.open("GET", "views07/recaracteristicas.php?nombre="+nombre,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function material(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
		registrar_material();
	}

function registrar_material(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/material.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function ir_material(){
	var contenedor, nombre;
	contenedor = document.getElementById('clientes');
	nombre = document.material.nombre.value;
	ajax=nuevoAjax();
	ajax.open("GET", "views07/rematerial.php?nombre="+nombre,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

	
function permisos(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/opciones.png"><br><span  class="titulos">buscando...</span>';
		buscar_permisos();
	}

function buscar_permisos(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/permisos.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function buscar_permisos2(){
	var login,contenedor;
	contenedor = document.getElementById('clientes');
	login = document.permiso.login.value;
	ajax=nuevoAjax();
	ajax.open("GET", "views07/permisos2.php?login="+login,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function buscar_permisos3(){
        var usuario,modulo,seccion,contenedor;
        contenedor = document.getElementById('clientes');
        usuario = document.permiso2.usuario.value;
	modulo = document.permiso2.modulo.value;
	seccion = document.permiso2.seccion_1.value;
	ajax=nuevoAjax();
        ajax.open("GET", "views07/permisos33.php?usuario="+usuario+"&modulo="+modulo+"&seccion="+seccion,true);
        ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {	
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');	
        ajax.send(null)
}

function ir_principal(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views01/principal.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

/*
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Copyright (C) Paul Johnston 1999 - 2000.
 * Updated by Greg Holt 2000 - 2001.
 * See http://pajhome.org.uk/site/legal.html for details.
 */

/*
 * Convert a 32-bit number to a hex string with ls-byte first
 */
var hex_chr = "0123456789abcdef";
function rhex(num)
{
  str = "";
  for(j = 0; j <= 3; j++)
    str += hex_chr.charAt((num >> (j * 8 + 4)) & 0x0F) +
           hex_chr.charAt((num >> (j * 8)) & 0x0F);
  return str;
}

/*
 * Convert a string to a sequence of 16-word blocks, stored as an array.
 * Append padding bits and the length, as described in the MD5 standard.
 */
function str2blks_MD5(str)
{
  nblk = ((str.length + 8) >> 6) + 1;
  blks = new Array(nblk * 16);
  for(i = 0; i < nblk * 16; i++) blks[i] = 0;
  for(i = 0; i < str.length; i++)
    blks[i >> 2] |= str.charCodeAt(i) << ((i % 4) * 8);
  blks[i >> 2] |= 0x80 << ((i % 4) * 8);
  blks[nblk * 16 - 2] = str.length * 8;
  return blks;
}

/*
 * Add integers, wrapping at 2^32. This uses 16-bit operations internally 
 * to work around bugs in some JS interpreters.
 */
function add(x, y)
{
  var lsw = (x & 0xFFFF) + (y & 0xFFFF);
  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return (msw << 16) | (lsw & 0xFFFF);
}

/*
 * Bitwise rotate a 32-bit number to the left
 */
function rol(num, cnt)
{
  return (num << cnt) | (num >>> (32 - cnt));
}

/*
 * These functions implement the basic operation for each round of the
 * algorithm.
 */
function cmn(q, a, b, x, s, t)
{
  return add(rol(add(add(a, q), add(x, t)), s), b);
}
function ff(a, b, c, d, x, s, t)
{
  return cmn((b & c) | ((~b) & d), a, b, x, s, t);
}
function gg(a, b, c, d, x, s, t)
{
  return cmn((b & d) | (c & (~d)), a, b, x, s, t);
}
function hh(a, b, c, d, x, s, t)
{
  return cmn(b ^ c ^ d, a, b, x, s, t);
}
function ii(a, b, c, d, x, s, t)
{
  return cmn(c ^ (b | (~d)), a, b, x, s, t);
}

/*
 * Take a string and return the hex representation of its MD5.
 */
function calcMD5(str)
{
  x = str2blks_MD5(str);
  a =  1732584193;
  b = -271733879;
  c = -1732584194;
  d =  271733878;

  for(i = 0; i < x.length; i += 16)
  {
    olda = a;
    oldb = b;
    oldc = c;
    oldd = d;

    a = ff(a, b, c, d, x[i+ 0], 7 , -680876936);
    d = ff(d, a, b, c, x[i+ 1], 12, -389564586);
    c = ff(c, d, a, b, x[i+ 2], 17,  606105819);
    b = ff(b, c, d, a, x[i+ 3], 22, -1044525330);
    a = ff(a, b, c, d, x[i+ 4], 7 , -176418897);
    d = ff(d, a, b, c, x[i+ 5], 12,  1200080426);
    c = ff(c, d, a, b, x[i+ 6], 17, -1473231341);
    b = ff(b, c, d, a, x[i+ 7], 22, -45705983);
    a = ff(a, b, c, d, x[i+ 8], 7 ,  1770035416);
    d = ff(d, a, b, c, x[i+ 9], 12, -1958414417);
    c = ff(c, d, a, b, x[i+10], 17, -42063);
    b = ff(b, c, d, a, x[i+11], 22, -1990404162);
    a = ff(a, b, c, d, x[i+12], 7 ,  1804603682);
    d = ff(d, a, b, c, x[i+13], 12, -40341101);
    c = ff(c, d, a, b, x[i+14], 17, -1502002290);
    b = ff(b, c, d, a, x[i+15], 22,  1236535329);    

    a = gg(a, b, c, d, x[i+ 1], 5 , -165796510);
    d = gg(d, a, b, c, x[i+ 6], 9 , -1069501632);
    c = gg(c, d, a, b, x[i+11], 14,  643717713);
    b = gg(b, c, d, a, x[i+ 0], 20, -373897302);
    a = gg(a, b, c, d, x[i+ 5], 5 , -701558691);
    d = gg(d, a, b, c, x[i+10], 9 ,  38016083);
    c = gg(c, d, a, b, x[i+15], 14, -660478335);
    b = gg(b, c, d, a, x[i+ 4], 20, -405537848);
    a = gg(a, b, c, d, x[i+ 9], 5 ,  568446438);
    d = gg(d, a, b, c, x[i+14], 9 , -1019803690);
    c = gg(c, d, a, b, x[i+ 3], 14, -187363961);
    b = gg(b, c, d, a, x[i+ 8], 20,  1163531501);
    a = gg(a, b, c, d, x[i+13], 5 , -1444681467);
    d = gg(d, a, b, c, x[i+ 2], 9 , -51403784);
    c = gg(c, d, a, b, x[i+ 7], 14,  1735328473);
    b = gg(b, c, d, a, x[i+12], 20, -1926607734);
    
    a = hh(a, b, c, d, x[i+ 5], 4 , -378558);
    d = hh(d, a, b, c, x[i+ 8], 11, -2022574463);
    c = hh(c, d, a, b, x[i+11], 16,  1839030562);
    b = hh(b, c, d, a, x[i+14], 23, -35309556);
    a = hh(a, b, c, d, x[i+ 1], 4 , -1530992060);
    d = hh(d, a, b, c, x[i+ 4], 11,  1272893353);
    c = hh(c, d, a, b, x[i+ 7], 16, -155497632);
    b = hh(b, c, d, a, x[i+10], 23, -1094730640);
    a = hh(a, b, c, d, x[i+13], 4 ,  681279174);
    d = hh(d, a, b, c, x[i+ 0], 11, -358537222);
    c = hh(c, d, a, b, x[i+ 3], 16, -722521979);
    b = hh(b, c, d, a, x[i+ 6], 23,  76029189);
    a = hh(a, b, c, d, x[i+ 9], 4 , -640364487);
    d = hh(d, a, b, c, x[i+12], 11, -421815835);
    c = hh(c, d, a, b, x[i+15], 16,  530742520);
    b = hh(b, c, d, a, x[i+ 2], 23, -995338651);

    a = ii(a, b, c, d, x[i+ 0], 6 , -198630844);
    d = ii(d, a, b, c, x[i+ 7], 10,  1126891415);
    c = ii(c, d, a, b, x[i+14], 15, -1416354905);
    b = ii(b, c, d, a, x[i+ 5], 21, -57434055);
    a = ii(a, b, c, d, x[i+12], 6 ,  1700485571);
    d = ii(d, a, b, c, x[i+ 3], 10, -1894986606);
    c = ii(c, d, a, b, x[i+10], 15, -1051523);
    b = ii(b, c, d, a, x[i+ 1], 21, -2054922799);
    a = ii(a, b, c, d, x[i+ 8], 6 ,  1873313359);
    d = ii(d, a, b, c, x[i+15], 10, -30611744);
    c = ii(c, d, a, b, x[i+ 6], 15, -1560198380);
    b = ii(b, c, d, a, x[i+13], 21,  1309151649);
    a = ii(a, b, c, d, x[i+ 4], 6 , -145523070);
    d = ii(d, a, b, c, x[i+11], 10, -1120210379);
    c = ii(c, d, a, b, x[i+ 2], 15,  718787259);
    b = ii(b, c, d, a, x[i+ 9], 21, -343485551);

    a = add(a, olda);
    b = add(b, oldb);
    c = add(c, oldc);
    d = add(d, oldd);
  }
  return rhex(a) + rhex(b) + rhex(c) + rhex(d);
}
