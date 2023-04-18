 <?php

   // Déclaration d'une nouvelle classe

   class Database
   {

      private $host    = '91.170.83.130';   // nom de l'host

      private $port   = '5089';       // port de connexion

      private $name    = 'blog';     // nom de la base de donnée

      private $user    = 'axel';        // utilisateur

      private $pass    = 'axel';        // mot de passe

      protected $connexion;



      function __construct($host = null, $port = null, $name = null, $user = null, $pass = null)
      {

         if ($host != null) {

            $this->host = $host;

            $this->port = $port;

            $this->name = $name;

            $this->user = $user;

            $this->pass = $pass;
         }

         try {

            $this->connexion = new PDO(
               'mysql:host=' . $this->host . ';dbname=' . $this->name . ';port=' . $this->port,
               $this->user,
               $this->pass,
               array(
                  PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',

                  PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
               )
            );
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
    // // Déclaration d'une nouvelle classe

    // class Database
    // {

    //     private $host    = 'localhost';   // nom de l'host

    //     private $port   = '9090';       // port de connexion

    //     private $name    = 'blog';     // nom de la base de donnée

    //     private $user    = 'axel';        // utilisateur

    //     private $pass    = '';        // mot de passe

    //     protected $connexion;



    //     function __construct($host = null, $port = null, $name = null, $user = null, $pass = null)
    //     {

    //         if ($host != null) {

    //             $this->host = $host;

    //             $this->port = $port;

    //             $this->name = $name;

    //             $this->user = $user;

    //             $this->pass = $pass;
    //         }

    //         try {

    //             $this->connexion = new PDO(
    //                 'mysql:host=' . $this->host . ';dbname=' . $this->name . ';port=' . $this->port,
    //                 $this->user,
    //                 $this->pass,
    //                 array(
    //                     PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',

    //                     PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
    //                 )
    //             );
    //         } catch (PDOException $e) {

    //             var_dump($e->getMessage());
    //             echo 'Erreur : Impossible de se connecter à la BDD !';

    //             die();
    //         }
    //     }



    //     public function getConnexion()
    //     {
    //         return $this->connexion;
    //     }
    // }