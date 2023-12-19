<?php 
    class Usuario{
        private $login;
        private $password;
        private $email;
        private $cod_dep;

        function __construct($login, $password, $email, $cod_dep){
            $this->login = $login;
            $this->password = $password;
            $this->email = $email;
            $this->cod_dep = $cod_dep;
        }

        public function getLogin() {return $this->login;}

        public function setLogin($login) {
            $this->login = $login;
                return $this;
        }

        public function getPassword(){return $this->password;}

        public function setPassword($password){
            $this->password = $password;
            return $this;
        }

        public function getEmail(){return $this->email;}

        public function setEmail($email){
            $this->email = $email;
            return $this;
        }

        public function getCod_dep(){return $this->cod_dep;}

        public function setCod_dep($cod_dep){
            $this->cod_dep = $cod_dep;
            return $this;
        }
    }

    /*$us2 = new Usuario("alfredo", "prueba", "a@gmail.com", "23");
    echo $us2->getEmail();
    print_r($us2);*/
?>