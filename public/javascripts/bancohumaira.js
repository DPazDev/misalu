/* **** REGISTRAR BANCO **** */
function cuenta_banco(){
		var var1 = document.getElementById('clientes'); 
		var1.innerHTML = '<img src="../public/images/esperar.gif"><br><span  class="titulos">Cargando...</span>';
		banco();
	}

function banco(){
	var contenedor;
	contenedor = document.getElementById('clientes');
	ajax=nuevoAjax();
	ajax.open("GET", "views07/banco.php",true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function r_banco1(){
	var contenedor,nombre1;
	contenedor = document.getElementById('clientes');
	nombre1=document.banca1.nombre1.value;

	ajax=nuevoAjax();
	ajax.open("GET", "views07/r_banco1.php?nombre1="+nombre1,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function guardar_cuenta(){
	var nombre,id_banco,num,tipo,contenedor;
	contenedor = document.getElementById('clientes');
	nombre = document.banca.nombre.value;
	id_banco = document.banca.id_banco.value;
	num = document.banca.num.value;
	tipo = document.banca.tipo.value;
		

	if(nombre.length== 0 || num.length== 0 || tipo.length== 0){
		alert("Los campos Nombre, Número de Cuenta y Tipo de Cuenta son Obligatorios");
	}
	
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_cuenta.php?nombre="+nombre+"&num="+num+
"&tipo="+tipo+"&id_banco="+id_banco,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
   }
}

function modificar_banco(){
	var contenedor,nombre1;
	contenedor = document.getElementById('clientes');
	nombre1=document.banca1.nombre1.value;

	ajax=nuevoAjax();
	ajax.open("GET", "views07/modificar_banco.php?nombre1="+nombre1,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
}

function guardar_banco(){
	var nombre,id_banco,contenedor;
	contenedor = document.getElementById('clientes');
	nombre = document.banca.nombre.value;
	id_banco = document.banca.id_banco.value;	

	if(nombre.length== 0){
		alert("Los campos Nombre son Obligatorios");
	}
	alert(id_banco);
	else{
	ajax=nuevoAjax();
	ajax.open("GET", "views07/guardar_banco.php?nombre="+nombre+"&id_banco="+id_banco,true);
	ajax.onreadystatechange=function() {
	if (ajax.readyState==4) {
		contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.send(null)
   }
