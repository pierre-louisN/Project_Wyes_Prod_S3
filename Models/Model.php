<?php
class Model
{
  private $bd;

  private static $instance=null;

  private function __construct()
  {
    try {
      include('Utils/log.php');
      $this->bd=new PDO($dsn,$login,$mdp);
      $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->bd->query("SET nameS 'utf8'");
    } catch (PDOException $e) {
      die('Echec connexion, erreur n°' . $e->getCode() . ' : ' . $e->getMessage());
    }
  }


  public static function getModel()
  {
    if (is_null(self::$instance)) {
        self::$instance = new Model();
    }
    return self::$instance;
  }

  public function getNbLunettes()
  {
    try {
      //overview des quantitées des lunettes
      $requete=$this->bd->prepare('SELECT COUNT(*) FROM PRODUCT');
      $requete->execute();
      $tab = $requete->fetch(PDO::FETCH_NUM);
      return $tab[0];
    } catch (PDOException $e) {
      die('Echec getNbLunettes, erreur n°' . $e->getCode() . ' : ' . $e->getMessage());
    }
  }
  public function addComponent($infos)
  {

      try {
          //Préparation de la requête
          $requete = $this->bd->prepare('INSERT INTO nobels (year, category, name, birthdate, birthplace, county, motivation) VALUES (:year, :category, :name, :birthdate, :birthplace, :county, :motivation)');

          //Remplacement des marqueurs de place par les valeurs
          $marqueurs = ['year', 'category', 'name', 'birthdate','birthplace', 'county', 'motivation'];
          foreach ($marqueurs as $value) {
              $requete->bindValue(':' . $value, $infos[$value]);
          }

          //Exécution de la requête
          return $requete->execute();
      } catch (PDOException $e) {
          die('Echec addNobelPrize, erreur n°' . $e->getCode() . ':' . $e->getMessage());
      }
  }

  public function less_than_20()
  {
    $nb_lunette=getNbLunettes();
    if($nb_lunette<20)
    {
      return true;
    }else{
      return false;
    }
  }

  public function getNbComponent()
  {
    try {
      //overview des quantitées des composants
      $requete=$this->bd->prepare('select quantity, name from COMPONENT;');
      $requete->execute();
      return $requete->fetchAll(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
      die('Echec getNbComponent, erreur n°' . $e->getCode() . ' : ' . $e->getMessage());
    }
  }

  public function getNamePatient($nom){
    try {
      $requete = $this->bd->prepare('SELECT * FROM PATIENT WHERE name = :m ');
      $requete->bindValue(":m",$nom);
      $requete->execute();
      return $requete->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      die('Echec getNamePatient, erreur n°' . $e->getCode() . ' : ' . $e->getMessage());
    }
  }

  public function getLastPatient()
  {
    try {
        $req = $this->bd->prepare('SELECT * FROM PATIENT ORDER BY id_patient DESC LIMIT 25');
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('Echec getLastPatient, erreur n°' . $e->getCode() . ':' . $e->getMessage());
      }
  }


}

 ?>
