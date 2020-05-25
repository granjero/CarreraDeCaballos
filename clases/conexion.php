<?php
class claseConexion 
{
    private $servidor;
    private $usuario;
    private $clave;
    private $bd;
    private $sql;
    
    public function __construct() 
    {
        $this->servidor = 'localhost';
        $this->usuario = 'estonoesunaweb_CDC';
        $this->clave = '112233';
        $this->sql = 0;
    }
    
    public function Open() 
    {
        $this->sql = new mysqli($this->servidor, $this->usuario, $this->clave);
		return $this->sql;
    }
    
    public function Close() 
    {
        $this->sql = mysqli_close($this->sql);
    }
}
?>
