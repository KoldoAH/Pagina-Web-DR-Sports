<?php
	include "modelo.php";
	include "vista.php";

	
	//$fecha_actual= getdate();
	//$titulo= $_POST["titulo"];
	//$descripcion= $_POST["descripcion"];

	//vpublicarpost(mpublicarpost($titulo,$descripcion,$fecha_actual));
	if (isset($_POST["titulo"])  && (isset($_POST["descripcion"]))){
		$titulo= $_POST["titulo"];
		$descripcion= $_POST["descripcion"];
		$fecha_actual= getdate();
		if(mpublicarpost($titulo,$descripcion,$fecha_actual)==1){
			vvisualizarposts(mvisualizarposts());
		}	
	}
	else{
		vvisualizarposts(mvisualizarposts());
	}
	

?>