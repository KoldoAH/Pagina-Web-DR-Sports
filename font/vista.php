<?php

	function vmostrarmenu ($consultaListaDeportes) {
		if ($consultaListaDeportes!=-1){
			echo $consultaListaDeportes;
		}
		else{
			mostrarmensaje("Mostrar desplegable", "Error accediendo a la lista de deportes.");
		}
	}
	function vmostrarmenuprincipaladmin(){
		echo file_get_contents("menuprincipaladmin.html");
	}

	function vmostrartienda ($listaDeporteSeleccionado) {
		if ($listaDeporteSeleccionado != -1){
			echo $listaDeporteSeleccionado;
		}
		else {
			mostrarmensaje("Mostrar productos", "Error accediendo a la lista de productos.");
		}

	}
	function vmostrargaleriafotos ($listaGaleria){
		if ($listaGaleria!=-1){
			echo $listaGaleria;
		}
		else{
			mostrarmensaje("Mostrar galería de fotos","Error al mostrar la galería de fotos.");
		}
		
	}
	function vmostrarposts (){
		echo file_get_contents("publicaciones.html");
	}
	function vmostrarusuario (){
		echo file_get_contents("usuario.html");
	}
	function vmostrarRegistrarCuenta (){
		echo file_get_contents("registrarCuenta.html");
	}

	function mostrarmensaje($titulo, $mensaje) {
		$cadena = file_get_contents("mensaje.html");
		$cadena = str_replace("##titulo##", $titulo, $cadena);
		$cadena = str_replace("##mensaje##", $mensaje, $cadena);
		echo $cadena;
	}
	function vmostrarResultadoRegistrarCuenta ($resultado) {
		if ($resultado == 1) {
			mostrarmensaje("Alta de persona", "El alta se ha realizado correctamente.");
		} else if ($resultado == -1){
			mostrarmensaje("Alta de persona", "Error al registrar. Ya hay un usuario con ese nombre.");
		}
		else if ($resultado == -3){
			mostrarmensaje("Alta de persona", "Error al registrar. Uno de los campos está vacío.");
		
		} 
		else{
			mostrarmensaje("Alta de persona", "No se ha podido dar de alta la persona, vuelva a intentarlo mas tarde.");
		}
	}

	function vmostrarResultadoLogin($resultado){
		if ($resultado == 1) {
			
		}
		else{
			mostrarmensaje("Login", "Error en el login. Nombre de usuario o contraseña incorrectos.");
		}
	}
	function vmostrarMenuPrincipalUsuario($consultaListaDeportes){
		if ($consultaListaDeportes!=-1){
			echo $consultaListaDeportes;
		}
		else{
			mostrarmensaje("Mostrar desplegable", "Error accediendo a la lista de deportes.");
		}
	}
	function vmostrarperfil(){
		echo file_get_contents("perfil.html");
	}
	function vcerrarsesion(){
		mostrarmensaje("Sesion cerrada", "Tu sesion ha terminado.");
	}
	function vcambiarpassword(){
		echo file_get_contents("cambiarpassword.html");
	}
	function vmostrarborrarcuenta(){
		echo file_get_contents("borrarcuenta.html");
	}
	function vmostrarventanaconfirmacion(){
		echo file_get_contents("ventanaconfirmacion.html");
	}

	function vmostrarcomprar($comprar){
		if($comprar!=-1){
			echo $comprar;
		}
		else{
			mostrarmensaje("Mostrar comprar", "Error realizando la compra.");
		}
		
	}
	function vaniadirpedido($resultadoinsert){
		if($resultadoinsert==1){
				mostrarmensaje("Mostrar comprar", "Compra realizada con éxito!");
		}
		else{
				mostrarmensaje("Mostrar comprar", "Error realizando la compra.");
		}
	
	}
	function vmostrarpedidos($pedidos){
		if($pedidos != -1){
			echo $pedidos;
		}
		else{
			mostrarmensaje("Mostrar pedidos","Error al mostrar los pedidos de los clientes.");
		}
	}
?>
