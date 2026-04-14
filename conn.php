<?php
Class ConexionBD {
    private $dsn;
    private $username;
    private $password;
    private $conexion;
    private $mensaje;

	public function __construct( ) {
        $this->dsn = "mysql:host=localhost:3306; dbname=asesoria";
	    $this->username  = 'root';
	    $this->password  = '';

        try{ 
            $this->conexion = new PDO( $this->dsn, $this->username, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->conexion->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        catch(PDOException $e){         
            $this->mensaje = "ERROR EN LA CONEXIÓN";
        }
    }
    
    //Cierra la conexion a la Base de Datos
    public function __destruct() {
		$this->conexion = NULL;
	}

    //Funcion getConexion
    //Regresa una conexion a la Base de Datos usando PDO.
    public function getConexion() {
    	return $this->conexion;
    }

    public function getMensaje() {
    	return $this->mensaje;
    }
/*
    private function convertirUTF8($arreglo){
        array_walk_recursive($arreglo, function(&$elemento, $llave){
            if(!mb_detect_encoding($elemento, 'utf-8', true)){
                $elemento = utf8_encode($elemento);
            }
        });
    }
*/	
}
?>