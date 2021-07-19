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
				$accion = "menuprincipalusuario";
			}
			else if(isset($_SESSION["nombreadmin"])){
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
	}else{
		if (isset($_POST["idproducto"])) {
			$idproducto = $_POST["idproducto"];
		}
	}

	if (isset($_POST["mensajeusuario"])){
		$mensajeusuario= $_POST["mensajeusuario"];
	}
	
	if (isset($_GET["metodopago"])){
		$metodopago= $_GET["metodopago"];
	}
	if (isset($_GET["direccionenvio"])){
		$direccionenvio= $_GET["direccionenvio"];
	}
	if (isset($_GET["pin"])) {
					$pin = $_GET["pin"];
	}
	if (isset($_POST['uploadBtn'])){
		$uploadBtn= $_POST['uploadBtn'];
	}
	if (isset($_POST['uploadedFile'])){
		$uploadedFile= $_POST['uploadedFile'];
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
				vmostrarmenuprincipaladmin(mmenuprincipaladmin());
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
				vaniadirpedido(maniadirpedido($_SESSION["nombreusuario"], $idproducto, $metodopago, $direccionenvio, $pin));
				break;
			case 3:
				vmostraropinionesusuarios(mOpinionesUsuarios($idproducto));
				break;
			case 4:
				if(isset($_SESSION["nombreusuario"] )){
					if(mpublicarmensaje($_SESSION["nombreusuario"],$mensajeusuario,$idproducto)==1){
						vmostraropinionesusuarios(mOpinionesUsuarios($idproducto));
						}else{
							mostrarmensaje("Publicar mensaje","Error al publicar el mensaje");
						}
				}
				else{
					echo "Para publicar un comentario necesitas acceder a tu cuenta!";
					vmostrarusuario();
				}
				break;
				
		};
	}

	else if ($accion == "posts"){
		switch ($valor) {
			case 1:
				vmostrarposts(mmostrarposts());
				break;
		};
	}
	else if ($accion == "postsadmin"){
		switch ($valor) {
			case 1:
				vmostrarposts(mmostrarpostsadmin());
				break;
			case 2:
				$titulo= $_POST["titulo"];
				$descripcion= $_POST["descripcion"];
				$fecha_actual= getdate();
				vpublicarpost(mpublicarpost($titulo,$descripcion,$fecha_actual));
				break;
			case 3:
				$idpublicacion= $_POST["idpublicacion"];
				vconfirmacioneliminarpublicacion($idpublicacion);
				break;
			case 4:
				$idpublicacion= $_POST["idpublicacion"];
				vmostrareliminarpublicacion(meliminarpublicacion($idpublicacion));
		}
	}

	else if($accion == "galeriaFotos"){
		switch ($valor) {
			case 1:
				vmostrargaleriafotos(mListaGaleria());
				break;

		}

	}
	else if($accion == "galeriaFotosAdmin"){
		switch ($valor) {
			case 1:
				vmostrargaleriafotos(mListaGaleriaAdmin());
				break;
			case 2:
				$numeroArchivos= count($_FILES['uploadedFile']['name']);				

				foreach($_FILES['uploadedFile']['tmp_name'] as $key=>$tmp_name)//as $key=>$tmp_name
				{
					if($_FILES['uploadedFile']['name'][$key]){
						$nombreImagen= $_FILES['uploadedFile']['name'][$key];
						$guardado= $_FILES['uploadedFile']['tmp_name'][$key];

						if (move_uploaded_file($guardado, $nombreImagen)){
							$original = imagecreatefromjpeg($nombreImagen);
							list($ancho,$alto)=getimagesize($nombreImagen);
							$nuevoanchomediano= "600";
							$nuevoaltomediano= "400";
							$lienzo=imagecreatetruecolor($nuevoanchomediano,$nuevoaltomediano);
							imagecopyresampled($lienzo,$original,0,0,0,0,$nuevoanchomediano, $nuevoaltomediano,$ancho,$alto);
							//imagedestroy($original);
							$cal=500;//Definimos la calidad de la imagen final
							$nuevoNombreImagen= $nombreImagen."mediana";
							imagejpeg($lienzo,$nuevoNombreImagen,$cal);

							msubirImagenes($nuevoNombreImagen,1);

							$nuevoanchopequenio= "300";
							$nuevoaltopequenio= "200";
							$lienzo=imagecreatetruecolor($nuevoanchopequenio,$nuevoaltopequenio);
							imagecopyresampled($lienzo,$original,0,0,0,0,$nuevoanchopequenio, $nuevoaltopequenio,$ancho,$alto);
							$cal=90;
							$nuevoNombreImagen= $nombreImagen."pequenia";
							imagejpeg($lienzo,$nuevoNombreImagen,$cal);
							msubirImagenes($nuevoNombreImagen,0);


							$nuevoanchogrande= "1200";
							$nuevoaltogrande= "800";

							/*$max_ancho = 1200;
							$max_alto = 800;
							list($ancho,$alto)=getimagesize($nombreImagen);
							$x_ratio = $max_ancho / $ancho;
							$y_ratio = $max_alto / $alto;
							if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){//Si ancho 
								$ancho_final = $ancho;
								$alto_final = $alto;
							}

							elseif (($x_ratio * $alto) < $max_alto){
								$alto_final = ceil($x_ratio * $alto);
								$ancho_final = $max_ancho;
							}

							else{
								$ancho_final = ceil($y_ratio * $ancho);
								$alto_final = $max_alto;
							}
							$tmp=imagecreatetruecolor($ancho_final,$alto_final);
							imagecopyresampled($tmp,$original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
							imagedestroy($original);
							$calidad=95;
							$nuevoNombreImagen= $nombreImagen."grande";
							imagejpeg($tmp,$nuevoNombreImagen,$calidad);
							msubirImagenes($nuevoNombreImagen,2);*/

							$lienzo=imagecreatetruecolor($nuevoanchogrande,$nuevoaltogrande);
							imagecopyresampled($lienzo,$original,0,0,0,0,$nuevoanchogrande, $nuevoaltogrande,$ancho,$alto);
							imagedestroy($original);
							$cal=1000;
							$nuevoNombreImagen= $nombreImagen."grande";
							imagejpeg($lienzo,$nuevoNombreImagen,$cal);
							msubirImagenes($nuevoNombreImagen,2);


						}
						else{
							mostrarmensaje("Subir imagen","Error subiendo imagen");
							break;
						}
					}
					else{
						mostrarmensaje("Subir imagen","Error subiendo imagen");
						break;
					}
				} 
				mostrarmensaje( "Subir imagen","archivo guardado con éxito");


				break;
			case 3:
				$imagen = $_GET["idfoto"];
				vconfirmacioneliminarimagen($imagen);
				break;
			case 4:
				$imagen = $_POST["imagen"];
				$nombreimagen = str_replace("pequenia", "", $imagen);
				$imagenpequenia = $nombreimagen."pequenia";
				$imagenmediana = $nombreimagen."mediana";
				$imagengrande = $nombreimagen."grande";
				$res = mborrarimagen($imagenpequenia, $imagenmediana, $imagengrande);
				if ($res == 1){
					mostrarmensaje( "Eliminar imagen","imagen eliminada con éxito");
				}
				else{
					mostrarmensaje( "Eliminar imagen","no se ha podido eliminar la imagen");
				}
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
					vmostrarmenuprincipaladmin(mmenuprincipaladmin());
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
				if (empty($_SESSION["nombreadmin"])){
					vmostrarperfil(mCargarPerfil($_SESSION["nombreusuario"]));
				}
				else{
					vmostrarperfil(mCargarPerfil($_SESSION["nombreadmin"]));
				}
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
				else if (mCambiarPassword($_SESSION["nombreusuario"])==-3){
					mostrarmensaje("Cambiar contraseña", "La nueva contraseña tiene que tener al menos un dígito.");
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
					vmostrarperfil(mCargarPerfil($_SESSION["nombreusuario"]));
				}
				break;
			case 7:
				if (isset($_POST['subirIcono'])) {
					if(isset($_SESSION["nombreusuario"])){
					    $archivo = $_FILES['icono']['name'];
					    if (isset($archivo) && $archivo != "") {
					      	$tipo = $_FILES['icono']['type'];
					      	$tamano = $_FILES['icono']['size'];
					      	$temp = $_FILES['icono']['tmp_name'];
					      	//Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
					    	if (!((strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
					        	echo '<div><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
					        	- Se permiten archivos .jpg, .png. y de 200 kb como máximo.</b></div>';
					        	vmostrarperfil(mCargarPerfil($_SESSION["nombreusuario"]));
					    	}
					    	else {
					        	//Si la imagen es correcta en tamaño y tipo
					        	//Se intenta subir al servidor
					        	if (move_uploaded_file($temp, $archivo)) {
					            	//Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
					            	chmod($archivo, 0777);
					            	//Mostramos el mensaje de que se ha subido co éxito
					            	echo '<div><b>Se ha subido correctamente la imagen.</b></div>';
					            	//actualizar la imagen en la base de datos
					            	mActualizarIcono($archivo, $_SESSION["nombreusuario"]);
					            	//VMOSTRARPERFIL
					            	vmostrarperfil(mCargarPerfil($_SESSION["nombreusuario"]));
					        	}
					        	else {
					           		//Si no se ha podido subir la imagen, mostramos un mensaje de error
					          		echo '<div><b>Ocurrió algún error al subir el fichero. No pudo guardarse.</b></div>';
					        	}
					    	}
					    }
					}
					else{
						$archivo = $_FILES['icono']['name'];
					    if (isset($archivo) && $archivo != "") {
					      	$tipo = $_FILES['icono']['type'];
					      	$tamano = $_FILES['icono']['size'];
					      	$temp = $_FILES['icono']['tmp_name'];
					      	//Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
					    	if (!((strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
					        	echo '<div><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
					        	- Se permiten archivos .jpg, .png. y de 200 kb como máximo.</b></div>';
					        	vmostrarperfil(mCargarPerfil($_SESSION["nombreadmin"]));
					    	}
					    	else {
					        	//Si la imagen es correcta en tamaño y tipo
					        	//Se intenta subir al servidor
					        	if (move_uploaded_file($temp, $archivo)) {
					            	//Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
					            	chmod($archivo, 0777);
					            	//Mostramos el mensaje de que se ha subido co éxito
					            	echo '<div><b>Se ha subido correctamente la imagen.</b></div>';
					            	//actualizar la imagen en la base de datos
					            	mActualizarIcono($archivo, $_SESSION["nombreadmin"]);
					            	//VMOSTRARPERFIL
					            	vmostrarperfil(mCargarPerfil($_SESSION["nombreadmin"]));
					        	}
					        	else {
					           		//Si no se ha podido subir la imagen, mostramos un mensaje de error
					          		echo '<div><b>Ocurrió algún error al subir el fichero. No pudo guardarse.</b></div>';
					        	}
					    	}
					    }
					}
				}
				break;
			case 8;
				if (isset($_POST['descripcion'])){
					if(isset($_SESSION["nombreusuario"])){
						$descripcion = $_POST['descripcion'];
						mActualizarDescripcion($descripcion, $_SESSION["nombreusuario"]);
						vmostrarperfil(mCargarPerfil($_SESSION["nombreusuario"]));
					}
					else{
						$descripcion = $_POST['descripcion'];
						mActualizarDescripcion($descripcion, $_SESSION["nombreadmin"]);
						vmostrarperfil(mCargarPerfil($_SESSION["nombreadmin"]));
					}
				}
				else{
					vmostrarperfil(mCargarPerfil($_SESSION["nombreusuario"]));
				}
				break;
			case 9;
				if (isset($_POST['buscar'])){
					$nombrebuscado = $_POST['buscar'];
					if (mCargarPerfilBuscado($nombrebuscado) != -1){
						vmostrarperfil(mCargarPerfilBuscado($nombrebuscado));
					}
					else{
						echo "El usuario ".$nombrebuscado. " no existe";
						if(isset($_SESSION["nombreusuario"])){
							vmostrarperfil(mCargarPerfil($_SESSION["nombreusuario"]));
						}
						else{
							vmostrarperfil(mCargarPerfil($_SESSION["nombreadmin"]));
						}
					}
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
	else if($accion== "listausuarios"){
		switch ($valor){
			case 1:
				vmostrarlistausuarios(mmostrarlistausuarios());
			break;
			case 2:
				vgenerarlistausuariosjson(mgenerarlistausuariosjson());
			break;
			case 3:
				vparsearlistausuariosjson(mgenerarlistausuariosjson());
			break;
			case 4:
				msubirarchivo();
			break;
			case 5:
				
				if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
					$fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
					$fileName = $_FILES['uploadedFile']['name'];
					$fileSize = $_FILES['uploadedFile']['size'];
					$fileType = $_FILES['uploadedFile']['type'];
					$fileNameCmps = explode(".", $fileName);
					$fileExtension = strtolower(end($fileNameCmps));
					$newFileName = md5(time() . $fileName) . '.' . $fileExtension;
					$allowedfileExtensions = array('csv');
					if (in_array($fileExtension, $allowedfileExtensions)) {
						// directory in which the uploaded file will be moved
						$uploadFileDir = $fileTmpPath;
						$dest_path = $uploadFileDir . $newFileName;
						 
						if(move_uploaded_file($fileTmpPath, $dest_path))
						{
						  //echo "Archivo subido";
						  mconversionCSVaSQL($dest_path);
						  mostrarmensaje("Subir archivo CSV","Achivo subido con éxito");
						}
						else
						{
						  mostrarmensaje("Subir archivo CSV","Error al subir archivo CSV");
						}
					}
					else{
						mostrarmensaje("Subir archivo","El archivo no es de tipo csv.");
					}
				}

				break;
		}
	}

	else if($accion == "cerrarsesion"){
		session_destroy();
		vcerrarsesion();
		
	}

	else if($accion == "administrarproductos"){
		switch($valor){
			case 1: //AÑADIR NUEVO PRODUCTO

				vaniadirproducto();
				break;
			case 2:
				if (isset($_FILES['fotoproducto']) && $_FILES['fotoproducto']['error'] === UPLOAD_ERR_OK) {
				    $archivo = $_FILES['fotoproducto']['name'];
				    if (isset($archivo) && $archivo != "") {
				      	$tipo = $_FILES['fotoproducto']['type'];
				      	$tamano = $_FILES['fotoproducto']['size'];
				      	$temp = $_FILES['fotoproducto']['tmp_name'];
				      	//Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
				    	if (!((strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
				        	echo '<div><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
				        	- Se permiten archivos .jpg, .png. y de 200 kb como máximo.</b></div>';
				    	}
				    	else {
				        	//Si la imagen es correcta en tamaño y tipo
				        	//Se intenta subir al servidor
				        	if (move_uploaded_file($temp, $archivo)) {
				            	//Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
				            	chmod($archivo, 0777);
				            	//Mostramos el mensaje de que se ha subido co éxito
				            	//echo '<div><b>Se ha subido correctamente la imagen.</b></div>';
				            	//actualizar la imagen en la base de datos
				            	if (!isset($_POST['descripcion'])|| !isset($_POST['elegircategoriadeporte'])||!isset($_POST['color'])||!isset($_POST['precio'])) {
				            		echo "Hay que rellenar todos los campos";
									vaniadirproducto();
				            	}
				            	else{
				            		$descripcionproducto= $_POST['descripcion'];
				            		$categoriadeporte= $_POST['elegircategoriadeporte'];
				            		$color= $_POST['color'];
									$precio= $_POST['precio'];
				            		if($descripcionproducto== "" || $categoriadeporte=="categoria"||$color==""||$precio== ""){
				            			echo "Hay que rellenar todos los campos";
										vaniadirproducto();
				            		}
				            		else{
				            			if(is_numeric($precio)==1){
				            				vmostraraniadirproducto(maniadirproducto($categoriadeporte,$descripcionproducto,$archivo,floatval($precio),$color));	
				            			}
				            			else{
				            				echo "Formato del precio incorrecto.";
											vaniadirproducto();
				            			}
				            			
				            		}
				            	}

				        	}
				        	else {
				           		//Si no se ha podido subir la imagen, mostramos un mensaje de error
				          		echo '<div><b>Ocurrió algún error al subir el fichero. No pudo guardarse.</b></div>';
				        	}
				    	}
				    }
				}
				else{
					echo "Hay que introducir una imagen del producto";
					vaniadirproducto();
				}
				break;
			case 3:
				$idproducto= $_GET["idproducto"];
				vconfirmacioneliminarproducto($idproducto);
				break;

			case 4: //modificar producto
				$idproducto = $_GET["idproducto"];
				vmostrarmodificarproducto(mmodificarproducto($idproducto));
				break;
			case 5:
				$idproducto= $_POST["idproducto"];
				vmostrareliminarproducto(meliminarproducto($idproducto));
				break;
			case 6;
				$idproducto = $_POST["idproducto"];
				$archivo = "";
				if(isset($_FILES['fotoproducto']) && $_FILES['fotoproducto']['error'] === UPLOAD_ERR_OK){
					$archivo = $_FILES['fotoproducto']['name'];
					if (isset($archivo) && $archivo != "") {
				      	$tipo = $_FILES['fotoproducto']['type'];
				      	$tamano = $_FILES['fotoproducto']['size'];
				      	$temp = $_FILES['fotoproducto']['tmp_name'];
				      	//Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
				    	if (!((strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
				        	echo '<div><b>Error. La extensión o el tamaño de los archivos no es correcta.<br/>
				        	- Se permiten archivos .jpg, .png. y de 200 kb como máximo.</b></div>';
				        	vmostrarmodificarproducto(mmodificarproducto($idproducto));
				        	break;
				    	}
				    	else {
				        	//Si la imagen es correcta en tamaño y tipo
				        	//Se intenta subir al servidor
				        	if (move_uploaded_file($temp, $archivo)) {
				            	//Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
				            	chmod($archivo, 0777);
				        	}
				        	else {
				           		//Si no se ha podido subir la imagen, mostramos un mensaje de error
				          		echo '<div><b>Ocurrió algún error al actualizar la imagen. No pudo guardarse.</b></div>';
				        	}
				    	}
					}
				}
				$descripcionproducto= $_POST['descripcion'];
				$categoriadeporte= $_POST['elegircategoriadeporte'];
				$color= $_POST['color'];
				$precio= $_POST['precio'];

				if($archivo != ""){ //hay imagen
					mactualizarcampo("Fotografía", $archivo, $idproducto);
				}

				if($descripcionproducto != ""){ //hay descripcion
					mactualizarcampo("Descripción", $descripcionproducto, $idproducto);
				}
				

				if($categoriadeporte != "categoria"){ //hay categoria
					mactualizarcampo("IDdeporte", $categoriadeporte, $idproducto);
				}

				if($color != ""){ //hay color
					mactualizarcampo("Color", $color, $idproducto);
				}

				if($precio != ""){ //hay precio
					if(is_numeric($precio)){
						mactualizarcampo("Precio", $precio, $idproducto);
					}
					else{
						echo "El precio debe ser un número";
						vmostrarmodificarproducto(mmodificarproducto($idproducto));
						break;
					}
				}
				echo "Se ha modificado el producto correctamente";
				vmostrarmodificarproducto(mmodificarproducto($idproducto));
				break;
		}
	}
?>
