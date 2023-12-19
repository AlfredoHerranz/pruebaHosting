<?php
     include_once("usuario.php");
     //CONEXION A LA BASE DE DATOS
     define("DB_HOST", "localhost");
     define("DB_NAME", "empresa2");
     define("DB_USER", "root");
     define("DB_PASSWORD", "");
 
     class db{
         private $host;
         private $dbName;
         private $user;
         private $password;
 
         private $db_handler; //Manejador de la bd
 
         private function __construct(){
 
         }
 
         public static function conectar(){ //static para no depender de ningun objeto 
             $host = DB_HOST;
             $dbName = DB_NAME;
             $user = DB_USER;
             $password = DB_PASSWORD;
 
             $db_handler = null;
             try{
                 $dns = "mysql:host=$host; dbname=$dbName";
                 $db_handler = new PDO($dns, $user, $password);
                 $db_handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Tiene en cuenta las excepciones
                 $db_handler->exec("set names utf8");
               //  echo "Conexión realizada...";
 
             }catch (Exception $e){
                 die("Error en la conexión ". $e->getMessage());//Termina el proceso
             }
 
             return $db_handler;
         }
     }
    
    class CrudUsuario{
        private $db_handler;

        public function __construct(){
            $this->db_handler = Db::conectar();
        }

        public function insertar(Usuario $usuario){
            $contrasenia = password_hash($usuario->getPassword(),PASSWORD_BCRYPT);
            $parametros = array(":login"=>$usuario->getLogin(), ":password"=>$contrasenia, ":email"=>$usuario->getEmail(), ":cod_dep"=>$usuario->getCod_dep());
            $pdo = $this->db_handler->prepare("INSERT INTO usuarios VALUES (:login, :password, :email, :cod_dep)");
            try{
                $pdo->execute($parametros);
            }catch(PDOException $e){
                echo "Error";
            }
        
        }

        public function validar($login, $passwd){
            $parametros = array(":login"=>$login);
            $pdo = $this->db_handler->prepare("SELECT * FROM usuarios WHERE login = :login");
            try{
                $pdo->execute($parametros);
                $resultado = $pdo->fetch(PDO::FETCH_ASSOC);
                if ($resultado){
                    if (!password_verify($passwd, $resultado["password"])){
                        $valido = 0;
                    }else{
                        $valido = 1;
                    }
                }else{
                    //echo "No existe ningun usuario con ese nombre";
                    $valido = 0;
                }
                
            }catch(PDOException $e){
                echo "No existe ningún usuario con el nombre {$login} en la base de datos";
            }           
            return $valido;
        }

        public function cambiar_contra($login, $old_contra, $new_contra){
            $crud = new CrudUsuario();
            if($crud->validar($login, $old_contra) == 1){
                $contrasenia = password_hash($new_contra,PASSWORD_BCRYPT);
                $parametros = array(":login"=>$login,":new_contra"=>$contrasenia);
                $pdo = $this->db_handler->prepare("UPDATE usuarios SET password = :new_contra WHERE login = :login");
                $pdo->execute($parametros);
                echo "Contraseña cambiada correctamente";
            }else{
                echo "Datos de login incorrectos";
            }
        }
    }
    /*
    $crud = new CrudUsuario();
    $us1 = new Usuario("alfredo", "prueba", "a@gmail.com", "23");
    $crud->insertar($us1);
    $crud->validar("alfredo", "prueba");
    $crud->cambiar_contra("alfredo", "prueba", "pruebaa");
    */
    
?>