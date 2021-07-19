<?php
	include "modelo.php";
	include "vista.php";

	session_start();


	if (isset($_GET["accion"])) {
		$accion = $_GET["accion"];
	} else {
		if (isset($_POST["accion"])) {
			$accion = $_POST["accion"];
		} else {	
			
			if(isset($_SESSION["nombreusuario"] )){
				echo "entra if usuario";
				$accion = "menuprincipalusuario";
			}
			else if(isset($_SESSION["nombreadmin"])){
				echo "entra if admin";
				$accion = "menuprincipaladmin";
			}
			else {
				$accion = "menu";
			}
			
				
		}
	}
	if (isset($_GET["valor"])) {
		$valor = $_GET["valor"];
	} else {
		if (isset($_POST["valor"])) {
			$valor = $_POST["valor"];
		} else {
			$valor = 1;	
		}
	}
	if (isset($_GET["eliminar"])) {
					$eliminar = $_GET["eliminar"];
	}


	if (isset($_GET["idproducto"])) {
		$idproducto = $_GET["idproducto"];
	}
	
	if (isset($_GET["metodopago"])){
		$metodopago= $_GET["metodopago"];
	}
	if (isset($_GET["direccionenvio"])){
		$direccionenvio= $_GET["direccionenvio"];
	}

	if ($accion == "menu") {
		switch ($valor) {
			case 1:
				//Mostrar el menu
				vmostrarmenu(mdesplegableDeportes());
				break;
		}
	}

	
	
	if ($accion == "menuprincipalusuario") {
		switch ($valor) {
			case 1:
				//Mostrar el menu
				echo "El usuario es : " . $_SESSION["nombreusuario"] . ".<br>";
				$listaDeportes= mdesplegableDeportesMenuUsuario();
				vmostrarmenuprincipalusuario($listaDeportes);
				break;
		}
	}
	if($accion== "menuprincipaladmin"){
		switch ($valor) {
			case 1:
				echo "El usuario es : " . $_SESSION["nombreadmin"] . ".<br>";
				vmostrarmenuprincipaladmin();
			break;
		}
	}

	else if ($accion == "tienda"){
		switch ($valor) {
			case 1:
				$deporte=$_GET["deporte"];
				vmostrartienda(mListaDeporteSeleccionado($deporte));
				break;
		};
	}
	else if($accion=="comprar"){
		switch ($valor) {
			case 1:
				if(isset($_SESSION["nombreusuario"] )){
					vmostrarcomprar(mComprar($idproducto));
				}
				else{
					vmostrarusuario();
				}
				break;
			case 2:
				vaniadirpedido(maniadirpedido($_SESSION["nombreusuario"], $idproducto, $metodopago, $direccionenvio));
				
				break;
		};
	}

	else if ($accion == "posts"){
		switch ($valor) {
			case 1:
				vmostrarposts();
				break;
		};
	}

	else if($accion == "galeriaFotos"){
		switch ($valor) {
			case 1:
				vmostrargaleriafotos(mListaGaleria());
				break;
		}
	}
	else if($accion == "usuario"){
		switch ($valor) {
			case 1:
		 		vmostrarusuario();
				break;
			case 2:
				vmostrarRegistrarCuenta();
				break;
			case 3:
				vmostrarResultadoRegistrarCuenta(mRegistrarCuenta());
				break;
			case 4:
				
				if (mLogin()==1){
					echo "El usuario es : " . $_SESSION["nombreusuario"] . ".<br>";
					vmostrarMenuPrincipalUsuario(mdesplegableDeportesMenuUsuario());
				}
				else if (mLogin()==2){
					echo "El usuario es : " . $_SESSION["nombreadmin"] . ".<br>";
					vmostrarMenuPrincipalAdmin();
				}
				else{
					echo "Error en el login. Nombre de usuario o contraseña incorrectos.";
					vmostrarusuario();
				}
		}
	}
	else if($accion == "perfil"){
		switch ($valor){
			case 1:
				vmostrarperfil();
				break;
			case 2:
				vcambiarpassword();
				break;
			case 3:
				vmostrarborrarcuenta();
				break;
			case 4:
				if (mCambiarPassword($_SESSION["nombreusuario"])==1){
					mostrarmensaje("Cambiar contraseña","Contraseña cambiada con éxito.");
				}
				else if(mCambiarPassword($_SESSION["nombreusuario"])==-1){
					mostrarmensaje("Cambiar contraseña","Error al cambiar la Contraseña.");
				}
				else{
					mostrarmensaje("Cambiar contraseña","Error al cambiar la Contraseña. Contraseña errónea.");
				}
				break;
			case 5:
				$password= $_POST["contraseña"];
				if(mBorrarCuenta($_SESSION["nombreusuario"], $password)==1){
					
					vmostrarventanaconfirmacion();
				}
				else{
					mostrarmensaje("Borrar cuenta", "Contraseña errónea.");
				}
				break;
			case 6:
				if ($eliminar==1){
					if(mBorrarDefinitivamenteCuenta($_SESSION["nombreusuario"])==1){
						session_destroy();
						mostrarmensaje("Borrar cuenta","La cuenta ha sido borrada correctamente.");
					}
					else{
						mostrarmensaje("Borrar cuenta", "Se ha producido un error.");
					}
				}
				else if ($eliminar==0){
					vmostrarperfil();
				}
				break;
		}	
	}
	else if($accion== "pedidos"){
		switch ($valor){
			case 1:
				vmostrarpedidos(mmostrarpedidos());
			break;
			case 2:
				mgenerarpedidospdf();
			break;
		}
	}

	else if($accion == "cerrarsesion"){
		session_destroy();
		vcerrarsesion();
		
	}
?>
