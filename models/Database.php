 <?php

    // Déclaration d'une nouvelle classe

    class Database
    {

        private $host    = '127.0.0.1';   // nom de l'host

        private $port   = '3306';       // port de connexion

        private $name    = 'blog';     // nom de la base de donnée

        private $user    = 'axel';        // utilisateur

        private $pass    = '';        // mot de passe

        protected $connexion;



        function __construct($host = null, $name = null, $user = null, $pass = null)
        {

            if ($host != null) {

                $this->host = $host;

                $this->name = $name;

                $this->user = $user;

                $this->pass = $pass;
            }

            try {

                // $this->connexion = new PDO(
                //     'mysql:host=' . $this->host . ';dbname=' . $this->name,
                //     $this->user,
                //     $this->pass,
                //     array(
                //         PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',

                //         PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
                //     )
                // );
                $dbh = new PDO('mysql:host=172.20.0.4;dbname=blog', 'axel', 'axel');
            } catch (PDOException $e) {

                var_dump($e->getMessage());
                echo 'Erreur : Impossible de se connecter à la BDD !';

                die();
            }
        }



        public function getConnexion()
        {
            return $this->connexion;
        }
    }