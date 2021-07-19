<?php
	//require('/fpdf/fpdf.php');
	require('./fpdf.php');
	function conexionbd () {
		$dblink = mysqli_connect("dbserver", "grupo38", "Ieth7ootie", "db_grupo38");
		return $dblink;
	}

	function mRegistrarCuenta () {
		$conexion = conexionbd();
		$nombre = $_POST["nombre"];
		$email = $_POST["email"];
		$password = $_POST["contraseña"];
		if ($nombre=="" || $email=="" || $password== ""){
			return -3;
		}
		$consultaNombre = "Select NombreUsuario,Email from Usuarios where NombreUsuario='$nombre' or Email='$email'";
		$resultado = $conexion->query($consultaNombre);
		if (mysqli_num_rows($resultado) > 0) {
			return -1;
		}

		else{
			$admin= intval("0");
			$fotoPerfil= "icono_predeterminado.png";
			$consultaInsert = "insert into Usuarios (NombreUsuario, Contraseña, Email,Administrador,Foto,Descripcion) values ('$nombre', '$password', '$email','$admin','$fotoPerfil','')";
			if($resultado = $conexion->query($consultaInsert)) {
				return 1;
			}
			else{
				return -2;
			}
		}
	}
	function mdesplegableDeportes(){
		$cadena=file_get_contents("menuprincipal.html");
		$conexion = conexionbd();
		$consultaListaDeportes = "Select IDdeporte,Nombre from Deportes";
		if ($resultado = $conexion->query($consultaListaDeportes)) {
			
			$trozos = explode("##filadeporte##", $cadena);
			$cuerpo = "";
			while ($datos = $resultado->fetch_assoc()) {
				$aux = $trozos[	1];
				$aux = str_replace("IDdeporte", $datos["IDdeporte"], $aux);
				$aux = str_replace("##deporte##", $datos["Nombre"], $aux);

				$cuerpo .= $aux;
			}
			$cadenares = $trozos[0] . $cuerpo . $trozos[2];

			
		}
		$consultaProductos = "Select Descripción, Fotografía, Precio, IDproducto from Productos";
		if ($resultado2 = $conexion->query($consultaProductos)){

			$trozos2 = explode("##filaproducto##", $cadenares);
			$cuerpo2 = "";
			while ($datos2 = $resultado2->fetch_assoc()) {
				$aux = $trozos2[1];
				$aux = str_replace("##descripcion##", $datos2["Descripción"], $aux);
				$aux = str_replace("##fotografia##", $datos2["Fotografía"], $aux);
				$aux = str_replace("##precio##", $datos2["Precio"], $aux);
				$aux = str_replace("##idproducto##", $datos2["IDproducto"], $aux);

				$cuerpo2 .= $aux;
			}
			return $trozos2[0] . $cuerpo2 . $trozos2[2];	
		}
		else{
			return -1;
		}
	}
	function mdesplegableDeportesMenuUsuario(){
		$cadena=file_get_contents("menuprincipalusuario.html");
		$conexion = conexionbd();
		$consultaListaDeportes = "Select IDdeporte,Nombre from Deportes";
		if ($resultado = $conexion->query($consultaListaDeportes)) {
			
			$trozos = explode("##filadeporte##", $cadena);
			$cuerpo = "";
			while ($datos = $resultado->fetch_assoc()) {
				$aux = $trozos[	1];
				$aux = str_replace("IDdeporte", $datos["IDdeporte"], $aux);
				$aux = str_replace("##deporte##", $datos["Nombre"], $aux);

				$cuerpo .= $aux;
			}
			$cadenares = $trozos[0] . $cuerpo . $trozos[2];

			
		}
		$consultaProductos = "Select Descripción, Fotografía, Precio, IDproducto from Productos";
		if ($resultado2 = $conexion->query($consultaProductos)){

			$trozos2 = explode("##filaproducto##", $cadenares);
			$cuerpo2 = "";
			while ($datos2 = $resultado2->fetch_assoc()) {
				$aux = $trozos2[1];
				$aux = str_replace("##descripcion##", $datos2["Descripción"], $aux);
				$aux = str_replace("##fotografia##", $datos2["Fotografía"], $aux);
				$aux = str_replace("##precio##", $datos2["Precio"], $aux);
				$aux = str_replace("##idproducto##", $datos2["IDproducto"], $aux);

				$cuerpo2 .= $aux;
			}
			return $trozos2[0] . $cuerpo2 . $trozos2[2];	
		}
		else{
			return -1;
		}
	}
	function mListaDeporteSeleccionado($deporte){
		$conexion = conexionbd();
		$deporte = intval($deporte); //transformar valor del deporte a entero para comparar con el IDDeporte de la BBDD
		$consulta = "Select IDproducto, Descripción,Fotografía, Precio from Productos where IDdeporte=$deporte";
		$consultadeporte = "Select Nombre from Deportes where IDdeporte=$deporte";
		if($nombredeporte = $conexion->query($consultadeporte)){
			$cadena = file_get_contents("tienda.html");
			$datos = $nombredeporte->fetch_assoc();
			$cadena = str_replace("##deporte##", $datos["Nombre"],$cadena);
		}

		if ($resultado = $conexion->query($consulta)) {
			//$cadena = file_get_contents("tienda.html");
			$trozos = explode("##filatienda##", $cadena);
			$cuerpo = "";

			while ($datos = $resultado->fetch_assoc()){
				$aux = $trozos[1];
				$aux = str_replace("##descripcion##", $datos["Descripción"], $aux);
				$aux = str_replace("##fotografia##", $datos["Fotografía"], $aux);
				$aux = str_replace("##precio##", $datos["Precio"], $aux);
				$aux = str_replace("##idboton##", $datos["IDproducto"], $aux);

				$cuerpo .= $aux;
				
			
			}
			return $trozos[0] . $cuerpo . $trozos[2];
		
		}
		else{
			return -1;
		}
	}

	function mListaGaleria(){
		$conexion = conexionbd();
		$consultamediana= "Select id from Galeria where tamanio=1";
		if ($resultado = $conexion->query($consultamediana)) {
			$cadena = file_get_contents("galeriafotos.html");
			$datos = $resultado->fetch_assoc();
			$cadena= str_replace("##fotografiamediana##", $datos["id"], $cadena);
		

			$consulta = "Select id from Galeria where tamanio=0";
			if ($resultado = $conexion->query($consulta)) {
				//$cadena = file_get_contents("galeriafotos.html");
				$trozos = explode("##filagaleria##", $cadena);
				$cuerpo = "";
				while ($datos = $resultado->fetch_assoc()){
					$aux = $trozos[1];
					$aux = str_replace("##fotografia##", $datos["id"], $aux);
					$cuerpo .= $aux;
				}

				return $trozos[0] . $cuerpo . $trozos[2];
			}
			else{
				return -1;
			}
		}
		else{
			return -1;
		}
	}

	function mListaGaleriaAdmin(){
		$conexion = conexionbd();
		$consultamediana= "Select id from Galeria where tamanio=1";
		if ($resultado = $conexion->query($consultamediana)) {
			$cadena = file_get_contents("galeriafotosAdmin.html");
			$datos = $resultado->fetch_assoc();
			$cadena= str_replace("##fotografiamediana##", $datos["id"], $cadena);
		

			$consulta = "Select id from Galeria where tamanio=0";
			if ($resultado = $conexion->query($consulta)) {
				//$cadena = file_get_contents("galeriafotosAdmin.html");
				$trozos = explode("##filagaleria##", $cadena);
				$cuerpo = "";
				while ($datos = $resultado->fetch_assoc()){
					$aux = $trozos[1];
					$aux = str_replace("##fotografia##", $datos["id"], $aux);
					$cuerpo .= $aux;
				}
				return $trozos[0] . $cuerpo . $trozos[2];
			}
			else{
				return -1;
			}
		}
		else{
			return -1;
		}
	}

	function mLogin(){
		$conexion = conexionbd();
		$nombre = $_POST["nombre"];
		$password = $_POST["contraseña"];
		
		$consultaNombrePassword = "Select NombreUsuario,Contraseña,Administrador from Usuarios where NombreUsuario='$nombre' and Contraseña='$password'"; 
		$resultado = mysqli_query($conexion, $consultaNombrePassword);
		if (mysqli_num_rows($resultado) > 0){
			$datos = $resultado->fetch_assoc();
			if($datos["Administrador"]==0){
				$_SESSION["nombreusuario"]= $nombre;
			
				return 1;
			}
			else if($datos["Administrador"]==1){
				$_SESSION["nombreadmin"]= $nombre;
				return 2;
			}
		}
		else{
			return -1;
		}
		
	}

	function mCambiarPassword($nombreusuario){
		$conexion = conexionbd();
		$nombre = $nombreusuario;
		$password = $_POST["contraseña"];
		$newpassword = $_POST["nuevacontraseña"];
		
		$consultaNombrePassword = "Select NombreUsuario,Contraseña from Usuarios where NombreUsuario='$nombre' and Contraseña='$password'"; 
		$resultado = mysqli_query($conexion, $consultaNombrePassword);
		if (mysqli_num_rows($resultado) > 0){
			if($newpassword==""){
				return -3;
			}
			else{
				$updatepassword= "Update Usuarios set Contraseña='$newpassword' where NombreUsuario='$nombre'";
				if($resultadoupdate = $conexion->query($updatepassword)){
					return 1;
				}
				else{
					return -1;
				}
			}
		}
		else{
			return -2;
		}
	}

	function mBorrarCuenta($nombreusuario,$password){
		$conexion = conexionbd();
		$nombre = $nombreusuario;
		
		$consultaNombrePassword = "Select NombreUsuario,Contraseña from Usuarios where NombreUsuario='$nombre' and Contraseña='$password'";
		$resultado = mysqli_query($conexion, $consultaNombrePassword);
		if (mysqli_num_rows($resultado) > 0){
			return 1;
		}
		else{
			return -1;
		}
	}
	function mBorrarDefinitivamenteCuenta($nombreusuario){
		$conexion = conexionbd();
		$nombre = $nombreusuario;
		$borrarusuario= "Delete from Usuarios where NombreUsuario='$nombre'";
		if ($resultado = $conexion->query($borrarusuario)) {
			return 1;
		}
		else{
			return -1;
		}
	}
	
	function mComprar($producto){
		$conexion = conexionbd();
		$producto = intval($producto); 
		$consulta = "Select IDproducto, Descripción,Fotografía, Precio from Productos where IDproducto=$producto";

		if ($resultado = $conexion->query($consulta)) {
			$cadena = file_get_contents("comprar.html");
			$trozos = explode("##filacompra##", $cadena);
			$cuerpo = "";
			
			while ($datos = $resultado->fetch_assoc()){
				$aux = $trozos[1];
				$aux = str_replace("##idproducto##", $datos["IDproducto"], $aux);
				$aux = str_replace("##descripcion##", $datos["Descripción"], $aux);
				$aux = str_replace("##fotografia##", $datos["Fotografía"], $aux);
				$aux = str_replace("##precio##", $datos["Precio"], $aux);

				$cuerpo .= $aux;
				
			
			}
			return $trozos[0] . $cuerpo . $trozos[2];
		
		}
		else{
			return -1;
		}
		
	}

	function mmostrarposts(){
		$conexion = conexionbd();
		$consulta="Select Titulo, Fecha, Descripcion from Publicaciones";
		if ($resultado = $conexion->query($consulta)) {
			$cadena= file_get_contents("publicaciones.html");
			$trozos = explode("##filapublicacion##", $cadena);
			$cuerpo = "";
			while ($datos = $resultado->fetch_assoc()){
				$aux = $trozos[1];
				$aux = str_replace("##titulo##", $datos["Titulo"], $aux);
				$aux = str_replace("##fecha##", $datos["Fecha"], $aux);
				$aux = str_replace("##descripcion##", $datos["Descripcion"], $aux);
				$cuerpo .= $aux;
			}
			return  $trozos[0].$cuerpo.$trozos[2];
			//return $cadena;
		}
		else{
			return -1;
		}
	}
	function mmostrarpostsadmin(){
		/*
		$conexion = conexionbd();
		$consulta="Select IDPublicacion, Titulo, Fecha, Descripcion from Publicaciones";
		if ($resultado = $conexion->query($consulta)) {
			$cadena= file_get_contents("publicacionesAdmin.html");
			$trozos = explode("##filapublicacion##", $cadena);
			$cuerpo = "";
			while ($datos = $resultado->fetch_assoc()){
				$aux = $trozos[1];
				$aux = str_replace("##titulo##", $datos["Titulo"], $aux);
				$aux = str_replace("##fecha##", $datos["Fecha"], $aux);
				$aux = str_replace("##descripcion##", $datos["Descripcion"], $aux);
				$aux = str_replace("##publicacion##", $datos["IDPublicacion"], $aux);
				$cuerpo .= $aux;
			}
			return  $trozos[0] . $cuerpo. $trozos[2];
		}
		else{
			return -1;
		}*/
		return file_get_contents("publicacionesAdmin.html");
	}

	function mmostrarnuevopost(){
		$conexion = conexionbd();
		$consultamax= "Select max(IDPublicacion) as maxIDPublicacion from Publicaciones";
		$resultadomax = $conexion->query($consultamax);
		$datosmax= $resultadomax->fetch_assoc();
		$idnuevapublicacion=$datosmax["maxIDPublicacion"];
		$consulta="Select IDPublicacion,Titulo, Fecha, Descripcion from Publicaciones where IDPublicacion= '$idnuevapublicacion'";
		//$consulta="Select IDPublicacion,Titulo, Fecha, Descripcion from Publicaciones";
		if ($resultado = $conexion->query($consulta)) {
			$datos = $resultado->fetch_assoc();
			$idpublicacion= $datos["IDPublicacion"];
			$titulo= $datos["Titulo"];
			$fecha= $datos["Fecha"];
			$descripcion= $datos["Descripcion"];
			$cadena="";

			$cadena= "<h4>".$titulo."</h4>"." Fecha:".$fecha."<br>".$descripcion;
			$cadena=$cadena. "<form><input type='hidden' name='publicacion' id= 'idpublicacion' value='".$idpublicacion."'>
			<button type='button' id='Eliminarpublicacion'>Eliminar publicación</button> </form>
			";
			$cadena=$cadena."<div id='nuevapublicacion'></div><br><br>";


			return $cadena;
		}
		else{
			return -1;
		}
	}
	function mpublicarpost($titulo,$descripcion,$fecha_actual){
		$fecha= $fecha_actual["mday"]."/".$fecha_actual["mon"]."/".$fecha_actual["year"];
		$conexion = conexionbd();
		$consultainsert= "Insert into Publicaciones (Titulo,Descripcion,Fecha) values('$titulo','$descripcion','$fecha')";
		if ($resultado = $conexion->query($consultainsert)) {
			//return mmostrarnuevopost();
			return 1;
		}
		else{
			return -1;
		}
	}

	function mOpinionesUsuarios($producto){
		$conexion = conexionbd();
		$producto = intval($producto); 
		$consulta = "Select IDproducto, Descripción,Fotografía, Precio from Productos where IDproducto=$producto";

		if ($resultado = $conexion->query($consulta)) {
			$cadena = file_get_contents("opinionesusuarios.html");
			$trozos = explode("##filaproducto##", $cadena);
			$cuerpo = "";
			
			while ($datos = $resultado->fetch_assoc()){
				$aux = $trozos[1];
				$aux = str_replace("##idproducto##", $datos["IDproducto"], $aux);
				$aux = str_replace("##descripcion##", $datos["Descripción"], $aux);
				$aux = str_replace("##fotografia##", $datos["Fotografía"], $aux);
				$aux = str_replace("##precio##", $datos["Precio"], $aux);
				$cuerpo .= $aux;
			}
			$returnVista =  $trozos[0] . $cuerpo. $trozos[2];
			$consulta = "Select NombreUsuario, Mensaje,IDProducto from Comentarios where IDProducto= $producto";
			if ($resultado = $conexion->query($consulta)) {
				$trozos = explode("##filaopinion##", $returnVista);
				$cuerpo = "";
				while ($datos = $resultado->fetch_assoc()){
					$aux = $trozos[1];
					$aux = str_replace("##nombreusuario##", $datos["NombreUsuario"], $aux);
					$aux = str_replace("##mensaje##", $datos["Mensaje"], $aux);
					$cuerpo .= $aux;
				}
			}
			$returnVista =  $trozos[0] . $cuerpo;
			return $returnVista;
		}
		else{
			return -1;
		}
	}

	function mpublicarmensaje($nombreusuario,$mensajeusuario,$idproducto){
		$conexion = conexionbd();
		$idproducto = intval($idproducto); 
		$consultaInsert = "insert into Comentarios (NombreUsuario, Mensaje,IDProducto) values ('$nombreusuario', '$mensajeusuario','$idproducto')";
		if($resultado = $conexion->query($consultaInsert)) {
				return 1;
			}
			else{
				return -1;
			}
	}

	function maniadirpedido($nombreusuario, $idproducto, $metodopago, $direccionenvio, $pin){
		$conexion = conexionbd();
		if ($metodopago=="0"|| $direccionenvio==""||$pin==""){
			return -3;
		}
		else{
			$idproducto = intval($idproducto);
			$consulta= "Select Precio from Productos where IDproducto='$idproducto'";
			$resultado = $conexion->query($consulta);
			
			
			if($datos= $resultado->fetch_assoc()){
				$importe= $datos["Precio"];}
			$floatImporte= floatval($importe);
			$consultaInsert = "Insert into Pedidos (NombreUsuario, IDproducto, Direccion,MetodoPago,Importe) values ('$nombreusuario','$idproducto', '$direccionenvio', '$metodopago', '$floatImporte')";
			if($resultado=  $conexion->query($consultaInsert)){
				return 1;
			}
			else{
				return -1;
			}
		}
	}
	function mmostrarpedidos(){
		$conexion = conexionbd();
		$consulta= "Select * from Pedidos";
		if ($resultado = $conexion->query($consulta)) {
			$cadena = file_get_contents("pedidos.html");
			$trozos = explode("##pedidos##", $cadena);
			$cuerpo = "";
			while ($datos = $resultado->fetch_assoc()){
				$aux = $trozos[1];
				$aux = str_replace("##idpedido##", $datos["IDpedido"], $aux);
				$aux = str_replace("##nombreusuario##", $datos["NombreUsuario"], $aux);
				$aux = str_replace("##idproducto##", $datos["IDproducto"], $aux);
				$aux = str_replace("##direccionenvio##", $datos["Direccion"], $aux);
				$aux = str_replace("##metodopago##", $datos["MetodoPago"], $aux);
				$aux = str_replace("##importe##", $datos["Importe"], $aux);
				$cuerpo .= $aux;
			}
			return $trozos[0].$cuerpo.$trozos[2];
		}
		else{
			return -1;
		}

	}
	function mgenerarpedidospdf(){
		$conexion = conexionbd();
		$consulta= "Select * from Pedidos";
		if ($resultado = $conexion->query($consulta)) {
			$pdf=new FPDF();
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(30,10,"ID pedido");
			$pdf->Cell(45,10,"Nombre usuario");
			$pdf->Cell(30,10,"Id producto");
			$pdf->Cell(30,10,"Direccion");
			$pdf->Cell(40,10,"Metodo de pago");
			$pdf->Cell(30,10,"Importe",0,1);
			while ($datos = $resultado->fetch_assoc()){
				$pdf->Cell(30,10,$datos["IDpedido"]);
				$pdf->Cell(45,10,$datos["NombreUsuario"]);
				$pdf->Cell(30,10,$datos["IDproducto"]);
				$pdf->Cell(30,10,$datos["Direccion"]);
				$pdf->Cell(40,10,$datos["MetodoPago"]);
				$pdf->Cell(30,10,$datos["Importe"],0,1);
			}
			$pdf->Output();


		}
		else{
			return -1;
		}
	}

	function mmostrarlistausuarios(){
		$conexion = conexionbd();
		$consulta= "Select IDusuario,NombreUsuario,Email from Usuarios where Administrador=0";
		if ($resultado = $conexion->query($consulta)) {
			$cadena = file_get_contents("listausuarios.html");
			$trozos = explode("##listausuarios##", $cadena);
			$cuerpo = "";
			while ($datos = $resultado->fetch_assoc()){
				$aux = $trozos[1];
				$aux = str_replace("##idusuario##", $datos["IDusuario"], $aux);
				$aux = str_replace("##nombreusuario##", $datos["NombreUsuario"], $aux);
				$aux = str_replace("##email##", $datos["Email"], $aux);

				$cuerpo .= $aux;
			}

			return $trozos[0].$cuerpo.$trozos[2];
		}
		else{
			return -1;
		}
	}

	function mgenerarlistausuariosjson(){
		$conexion = conexionbd();
		$consulta= "Select IDusuario,NombreUsuario,Email from Usuarios where Administrador=0";
		if ($resultado = $conexion->query($consulta)) {
			$rawdata = array(); //creamos un array
			//guardamos en un array multidimensional todos los datos de la consulta
    		$i=0;
    		while($row = mysqli_fetch_array($resultado))
		    {
		        $rawdata[$i] = $row;
		        $i++;
		    }
		    return $rawdata;
		}
		else{
			return -1;
		}
	}

	function msubirarchivo(){
		echo file_get_contents("subirarchivo.html");
	}
	function mconversionCSVaSQL($file){
		$conexion = conexionbd();
		
		$handle = fopen($file,"r");

	    while (($fileop = fgetcsv($handle, 100000, ";")) !== false) {
	    	$NombreUsuario = $fileop[0];
	    	$Contraseña = $fileop[1];
	    	$Email = $fileop[2];
	    	$Administrador = intval($fileop[3]);
	    	$fotoPerfil= "icono_predeterminado.png";
	    	$consultaInsert = "Insert into Usuarios (NombreUsuario, Contraseña, Email,Administrador,Foto,Descripcion) values ('$NombreUsuario','$Contraseña', '$Email', '$Administrador', '$fotoPerfil','')";
	    	$conexion->query($consultaInsert);
			
			//if($resultado=  $conexion->query($consultaInsert))
	      //$sql = mysql_query("INSERT INTO Usuarios (NombreUsuario,Contraseña,Email,Administrador) VALUES ('$NombreUsuario','$Contraseña','$Email','$Administrador')");
	    }

	    /*if($sql){
	      echo "Done!";
	    }*/

	}

	function msubirImagenes($nombreImagen,$tamanio){
		$conexion = conexionbd();
		$consultaInsert = "Insert into Galeria (id,tamanio) values ('$nombreImagen','$tamanio')";
		$conexion->query($consultaInsert);

		//move_uploaded_file ($dest_path , "/home/grupo38/web/www/html/PaginaWebMVC/$dest_path");
	}

	function mActualizarIcono($archivo, $nombreUsuario){
		$conexion = conexionbd();
		$consulta = "Update Usuarios SET Foto= '$archivo' WHERE NombreUsuario = '$nombreUsuario'";
		$conexion->query($consulta);
	}

	function mActualizarDescripcion($desc, $nombreUsuario){
		$conexion = conexionbd();
		$consulta = "Update Usuarios SET Descripcion= '$desc' WHERE NombreUsuario = '$nombreUsuario'";
		$conexion->query($consulta);
	}

	function mCargarPerfil($nombreUsuario){
		$conexion = conexionbd();
		$consulta = "Select Foto, Descripcion from Usuarios Where NombreUsuario = '$nombreUsuario'";
		if ($resultado = $conexion->query($consulta)) {
			$datos = $resultado->fetch_assoc();
			$cadena = file_get_contents("perfil.html");
			$cadena = str_replace("##icono##", $datos["Foto"], $cadena);
			$cadena = str_replace("##descripcion##", $datos["Descripcion"], $cadena);
		}

		$consultaHistorial = "Select p.Descripción, p.Fotografía, p.Precio from Productos p, Pedidos pe where pe.NombreUsuario = '$nombreUsuario' and p.IDproducto = pe.IDproducto";
		if ($resultado = $conexion->query($consultaHistorial)) {
			$trozos = explode("##filaproducto##", $cadena);
			$cuerpo = "";
			while ($datos2 = $resultado->fetch_assoc()){
				$aux = $trozos[1];
				$aux = str_replace("##descripcionproducto##", $datos2["Descripción"], $aux);
				$aux = str_replace("##fotografia##", $datos2["Fotografía"], $aux);
				$aux = str_replace("##precio##", $datos2["Precio"], $aux);
				$cuerpo .= $aux;
			}
		}
		return $trozos[0].$cuerpo.$trozos[2];
	}

	function mCargarPerfilBuscado($nombreUsuario){
		$conexion = conexionbd();
		$cadena = file_get_contents("perfilbuscado.html");
		$comprobarperfil = "select * from Usuarios where NombreUsuario = '$nombreUsuario'";
		$result = mysqli_query($conexion, $comprobarperfil);
		if (mysqli_num_rows($result) > 0){
			$consulta = "Select Foto, Descripcion from Usuarios Where NombreUsuario = '$nombreUsuario'";
			$cadena = str_replace("##nombre##", $nombreUsuario, $cadena);
			if ($resultado = $conexion->query($consulta)) {
				$datos = $resultado->fetch_assoc();
				$cadena = str_replace("##icono##", $datos["Foto"], $cadena);
				$cadena = str_replace("##descripcion##", $datos["Descripcion"], $cadena);
			}
			$consultaHistorial = "Select p.Descripción, p.Fotografía, p.Precio from Productos p, Pedidos pe where pe.NombreUsuario = '$nombreUsuario' and p.IDproducto = pe.IDproducto";
			if ($resultado = $conexion->query($consultaHistorial)) {
				$trozos = explode("##filaproducto##", $cadena);
				$cuerpo = "";
				while ($datos2 = $resultado->fetch_assoc()){
					$aux = $trozos[1];
					$aux = str_replace("##descripcionproducto##", $datos2["Descripción"], $aux);
					$aux = str_replace("##fotografia##", $datos2["Fotografía"], $aux);
					$aux = str_replace("##precio##", $datos2["Precio"], $aux);
					$cuerpo .= $aux;
				}
			}
			return $trozos[0].$cuerpo.$trozos[2];
		}
		else{
			return -1;
		}
	}


	function maniadirproducto($categoriadeporte,$descripcionproducto,$imagen,$precio,$color){
		$conexion = conexionbd();
		$consultainsert= "Insert into Productos (Descripción,Fotografía,Precio,IDDeporte,Color) values ('$descripcionproducto','$imagen','$precio','$categoriadeporte','$color')";
		if($resultado = $conexion->query($consultainsert)){
			return 1;
		}
		else{
			return -1;
		}
	}
	function meliminarproducto($idproducto){
		$conexion = conexionbd();
		$consultadelete= "Delete from Productos where IDProducto= '$idproducto'";
		if($resultado = $conexion->query($consultadelete)){
			return 1;
		}
		else{
			return -1;
		}
	}

	function mmenuprincipaladmin(){
		$cadena = file_get_contents("menuprincipaladmin.html");
		$conexion = conexionbd();
		$consultaProductos = "Select Descripción, Fotografía, Precio, IDproducto from Productos";
		if ($resultado = $conexion->query($consultaProductos)){

			$trozos = explode("##filaproducto##", $cadena);
			$cuerpo= "";
			while ($datos = $resultado->fetch_assoc()) {
				$aux = $trozos[1];
				$aux = str_replace("##descripcion##", $datos["Descripción"], $aux);
				$aux = str_replace("##fotografia##", $datos["Fotografía"], $aux);
				$aux = str_replace("##precio##", $datos["Precio"], $aux);
				$aux = str_replace("##idproducto##", $datos["IDproducto"], $aux);

				$cuerpo .= $aux;
			}
			return $trozos[0] . $cuerpo . $trozos[2];	
		}
	}

	function mmodificarproducto($idproducto){
		$cadena = file_get_contents("modificarproducto.html");
		$conexion = conexionbd();
		$consultaProductos = "Select Descripción, Fotografía, Precio, IDproducto from Productos where IDProducto= '$idproducto'";
		if ($resultado = $conexion->query($consultaProductos)){
			$datos = $resultado->fetch_assoc();
			$cadena = str_replace("##descripcion##", $datos["Descripción"], $cadena);
			$cadena = str_replace("##fotografia##", $datos["Fotografía"], $cadena);
			$cadena = str_replace("##precio##", $datos["Precio"], $cadena);
			$cadena = str_replace("##idproducto##", $datos["IDproducto"], $cadena);
			return $cadena;
		}
		else{
			return -1;
		}
	}

	function mactualizarcampo($campo, $valor, $idproducto){
		$conexion = conexionbd();
		$consulta = "Update Productos set $campo = '$valor' where IDproducto = '$idproducto'";
		if($resultado = $conexion->query($consulta)){
			return 1;
		}
		else{
			return -1;
		}
	}

	function mvisualizarposts(){
		$conexion = conexionbd();
		$cadena=  file_get_contents("mostrarPublicaciones.html");
		$consulta= "Select iDPublicacion, Titulo,Descripcion,Fecha from Publicaciones";
		if ($resultado = $conexion->query($consulta)){
			$cadena = file_get_contents("mostrarPublicaciones.html");
			$trozos = explode("##filapublicacion##", $cadena);
			$cuerpo = "";
			while ($datos = $resultado->fetch_assoc()){
				$aux = $trozos[1];
				$aux = str_replace("##titulo##", $datos["Titulo"], $aux);
				$aux = str_replace("##fecha##", $datos["Fecha"], $aux);
				$aux = str_replace("##descripcion##", $datos["Descripcion"], $aux);
				$aux = str_replace("##idpublicacion##", $datos["iDPublicacion"], $aux);

				$cuerpo .= $aux;
			}

			return $trozos[0].$cuerpo.$trozos[2];
		
		}
		else{
			return -1;
		}
	}

	function meliminarpublicacion($idpublicacion){
		$conexion = conexionbd();
		$consultadelete= "Delete from Publicaciones where IDPublicacion= '$idpublicacion'";
		if($resultado = $conexion->query($consultadelete)){
			return 1;
		}
		else{
			return -1;
		}
	}

	function mborrarimagen($imagenpequenia, $imagenmediana, $imagengrande){
		$conexion = conexionbd();
		$consultadelete1 = "Delete from Galeria where id = '$imagenpequenia'";
		$consultadelete2 = "Delete from Galeria where id = '$imagenmediana'";
		$consultadelete3 = "Delete from Galeria where id = '$imagengrande'";
		if($resultado1 = $conexion->query($consultadelete1)){
			if($resultado2 = $conexion->query($consultadelete2)){
				if($resultado3 = $conexion->query($consultadelete3)){
					return 1;
				}
				else{
					return -1;
				}
			}
			else{
				return -1;
			}
		}
		else{
			return -1;
		}
	}
?>
