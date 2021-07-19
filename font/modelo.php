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
			$consultaInsert = "insert into Usuarios (NombreUsuario, Contraseña, Email,Administrador) values ('$nombre', '$password', '$email',$admin)";
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
			return $trozos[0] . $cuerpo . $trozos[2];			
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
			return $trozos[0] . $cuerpo . $trozos[2];			
		}
		else{
			return -1;
		}
	}
	function mListaDeporteSeleccionado($deporte){
		$conexion = conexionbd();
		$deporte = intval($deporte); //transformar valor del deporte a entero para comparar con el IDDeporte de la BBDD
		$consulta = "Select IDproducto, Descripción,Fotografía, Precio from Productos where IDdeporte=$deporte";

		if ($resultado = $conexion->query($consulta)) {
			$cadena = file_get_contents("tienda.html");
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
		$consulta = "Select id from Galeria";
		if ($resultado = $conexion->query($consulta)) {
			$cadena = file_get_contents("galeriafotos.html");
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
			echo "ENTRA ELSE\n";
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
			$updatepassword= "Update Usuarios set Contraseña='$newpassword' where NombreUsuario='$nombre'";
			if($resultadoupdate = $conexion->query($updatepassword)){
				return 1;
			}
			else{
				return -1;
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

	function maniadirpedido($nombreusuario, $idproducto, $metodopago, $direccionenvio){
		$conexion = conexionbd();
		$idproducto = intval($idproducto);

		$consulta= "Select Precio from Productos where IDproducto='$idproducto'";
		$resultado = $conexion->query($consulta);
		
		
		if($datos= $resultado->fetch_assoc()){
			$importe= $datos["Precio"];}
		//$stringImporte= array($importe);
		//implode($importe);
		$floatImporte= floatval($importe);
		$consultaInsert = "Insert into Pedidos (NombreUsuario, IDproducto, Direccion,MetodoPago,Importe) values ('$nombreusuario','$idproducto', '$direccionenvio', '$metodopago', '$floatImporte')";
		if($resultado=  $conexion->query($consultaInsert)){
			return 1;
		}
		else{
			return -1;
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
			$pdf->Cell(50,10,"Nombre usuario");
			$pdf->Cell(30,10,"Id producto");
			$pdf->Cell(30,10,"Direccion");
			$pdf->Cell(40,10,"Metodo de pago");
			$pdf->Cell(30,10,"Importe",0,1);
			while ($datos = $resultado->fetch_assoc()){
				$pdf->Cell(30,10,$datos["IDpedido"]);
				$pdf->Cell(55,10,$datos["NombreUsuario"]);
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
	/*function mdatospersonas() {
		$con = conexionbd();

		$consulta = "select * from personas order by nombre";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}
	}

	function mdatosunapersona() {
		$con = conexionbd();
		$idpersona = $_GET["idpersona"];

		$consulta = "select * from personas where idpersona = $idpersona";

		if ($resultado = $con->query($consulta)) {
			return $resultado;
		} else {
			return -1;
		}		
	}

	function mmodificarpersona() {
		$con = conexionbd();

		$nombre = $_POST["nombre"];
		$apellido1 = $_POST["apellido1"];
		$apellido2 = $_POST["apellido2"];
		$poblacion = $_POST["poblacion"];
		$idpersona = $_POST["idpersona"];

		$consulta = "update personas set nombre = '$nombre', apellido1 = '$apellido1', apellido2 = '$apellido2', poblacion = '$poblacion' where idpersona = $idpersona";

		if ($resultado = $con->query($consulta)) {
			return 1;
		} else {
			return -1;
		}
	}


	function meliminarpersona () {
		$con = conexionbd();

		$idpersona = $_POST["idpersona"];

		$consulta = "delete from personas where idpersona = $idpersona";

		if ($resultado = $con->query($consulta)) {
			return 1;
		} else {
			return -1;
		}		
	}	*/
?>
