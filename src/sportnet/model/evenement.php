<?php
namespace sportnet\model;
class evenement extends AbstractModel {
	protected $id;
	protected $nom;
	protected $description;
	protected $etat;
	protected $dateheureLimiteInscription;
	protected $tarif;
	protected $discipline;
	protected $organisateur;
	

	public function __construct()
	{
		$connect = new \sportnet\utils\ConnectionFactory();
		$connect->setConfig("conf/config.ini");
		self::$db = $connect->makeConnection();
		self::$db->query("SET CHARACTER SET utf8");
	}
	
	protected function update()
	{
		$update = "UPDATE evenement SET nom = :nom, description = :description, etat = :etat, dateheureLimiteInscription = :dateheureLimiteInscription,
		tarif = :tarif, id_discipline = :id_discipline, id_organisateur = :id_organisateur WHERE id = :id";
		$update_prep = self::$db->prepare($update);
		
		$idDiscipline = $this->discipline->id;
		$idOrganisateur = $this->organisateur->id;
		$dateHeureString = date_format(date_create($this->dateheureLimiteInscription),"Y-m-d H:i:s");
		
		$update_prep->bindParam(':nom', $this->nom, \PDO::PARAM_STR);
        $update_prep->bindParam(':description', $this->description, \PDO::PARAM_STR);
        $update_prep->bindParam(':etat', $this->etat, \PDO::PARAM_INT);
        $update_prep->bindParam(':dateheureLimiteInscription', $dateHeureString, \PDO::PARAM_STR);
		$update_prep->bindParam(':tarif', $this->tarif, \PDO::PARAM_STR);
		$update_prep->bindParam(':id_discipline', $idDiscipline, \PDO::PARAM_INT);
		$update_prep->bindParam(':id_organisateur', $idOrganisateur, \PDO::PARAM_INT);
		$update_prep->bindParam(':id', $this->id, \PDO::PARAM_INT);
		if($update_prep->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	protected function insert()
	{
		$insert = "INSERT INTO evenement VALUES(NULL, :nom, :description, :etat, :dateheureLimiteInscription, :tarif, :id_discipline, :id_organisateur)";
        $insert_prep = self::$db->prepare($insert);
		
		$dateHeureString = date_format(date_create($this->dateheureLimiteInscription),"Y-m-d H:i:s");
		$idDiscipline = $this->discipline->id;
		$idOrganisateur = $this->organisateur->id;
		
		$insert_prep->bindParam(':nom', $this->nom, \PDO::PARAM_STR);
        $insert_prep->bindParam(':description', $this->description, \PDO::PARAM_STR);
        $insert_prep->bindParam(':etat', $this->etat, \PDO::PARAM_INT);
        $insert_prep->bindParam(':dateheureLimiteInscription', $dateHeureString, \PDO::PARAM_STR);
		$insert_prep->bindParam(':tarif', $this->tarif, \PDO::PARAM_STR);
		$insert_prep->bindParam(':id_discipline', $idDiscipline, \PDO::PARAM_INT);
		$insert_prep->bindParam(':id_organisateur', $idOrganisateur, \PDO::PARAM_INT);
		if($insert_prep->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function save()
	{
		if(is_null($this->id)){
			return $this->insert();
		}
		else{
			return $this->update();
		}
	}
	
	public function delete()
	{
		$delete = "DELETE FROM evenement WHERE id = :id";
        $delete_prep = self::$db->prepare($delete);
		$delete_prep->bindParam(':id', $this->id, \PDO::PARAM_INT);
		if($delete_prep->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	public static function findById($leId)
	{
		if(self::$db == null)
		{
			$connect = new \sportnet\utils\ConnectionFactory();
			$connect->setConfig("conf/config.ini");
			self::$db = $connect->makeConnection();
			self::$db->query("SET CHARACTER SET utf8");
		}
        $selectById = "SELECT * FROM evenement WHERE id = :id";
        $selectById_prep = self::$db->prepare($selectById);
        $selectById_prep->bindParam(':id', $leId, \PDO::PARAM_INT);
		$selectById_prep->execute();
        $obj = null;
		while ($ligne = $selectById_prep->fetch(\PDO::FETCH_ASSOC))
		{
			$obj = new evenement();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->description  = $ligne['description'];
			$obj->etat = $ligne['etat'];
			$obj->dateheureLimiteInscription = date_create($ligne['dateheureLimiteInscription']);
			$obj->tarif = $ligne['tarif'];
			$obj->discipline = \sportnet\model\discipline::findById($ligne['id_discipline']);
			$obj->organisateur = \sportnet\model\organisateur::findById($ligne['id_organisateur']);
		}
		return $obj;
	}
	
	public static function findAll()
	{
		if(self::$db == null)
		{
			$connect = new \sportnet\utils\ConnectionFactory();
			$connect->setConfig("conf/config.ini");
			self::$db = $connect->makeConnection();
			self::$db->query("SET CHARACTER SET utf8");
		}
        $select = "SELECT * FROM evenement";
        $select_prep = self::$db->prepare($select);
        $select_prep->execute();
		$tab = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new evenement();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->description  = $ligne['description'];
			$obj->etat = $ligne['etat'];
			$obj->dateheureLimiteInscription = date_create($ligne['dateheureLimiteInscription']);
			$obj->tarif = $ligne['tarif'];
			$obj->discipline = \sportnet\model\discipline::findById($ligne['id_discipline']);
			$obj->organisateur = \sportnet\model\organisateur::findById($ligne['id_organisateur']);
			
			$tab[] = $obj;
		}
		return $tab;
	}
	
	public static function findByName($leNom)
	{
		if(self::$db == null)
		{
			$connect = new \sportnet\utils\ConnectionFactory();
			$connect->setConfig("conf/config.ini");
			self::$db = $connect->makeConnection();
			self::$db->query("SET CHARACTER SET utf8");
		}
        $selectByName = "SELECT * FROM evenement WHERE nom = :nom";
        $selectByName_prep = self::$db->prepare($selectById);
        $selectByName_prep->bindParam(':nom', $leNom, \PDO::PARAM_STR);
		$selectById_prep->execute();
        $obj = null;
		while ($ligne = $selectByName_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new evenement();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->description  = $ligne['description'];
			$obj->etat = $ligne['etat'];
			$obj->dateheureLimiteInscription = date_create($ligne['dateheureLimiteInscription']);
			$obj->tarif = $ligne['tarif'];
			$obj->discipline = \sportnet\model\discipline::findById($ligne['id_discipline']);
			$obj->organisateur = \sportnet\model\organisateur::findById($ligne['id_organisateur']);
		}
		return $obj;
	}
	
	public function getEpreuves()
	{
		$select = "SELECT * FROM epreuve WHERE id_evenement = :idevenement";
        $select_prep = self::$db->prepare($select);
        $select_prep->bindParam(":idevenement", $this->id, \PDO::PARAM_INT);
		$select_prep->execute();
        $tab = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC))
		{
			$obj = new epreuve();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->distance  = $ligne['distance'];
			$obj->dateheure = date_create($ligne['dateheure']);
			
			$obj->evenement = \sportnet\model\evenement::findById($ligne['id_evenement']);

			$tab[] = $obj;
		}
		return $tab;
	}
	
	public function getOrganisateur()
	{
		$select = "SELECT * FROM organisateur where id = :id";
        $select_prep = self::$db->prepare($select);
		$idOrganisateur = $this->organisateur->id;
        $select_prep->bindParam(":id", $idOrganisateur, \PDO::PARAM_INT);
		$select_prep->execute();
        $obj = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new organisateur();

			$obj->id = $ligne['id'];
			$obj->login = $ligne['login'];
			$obj->mdp  = $ligne['mdp'];
			$obj->nom = $ligne['nom'];
			$obj->prenom = $ligne['prenom'];
			$obj->adresse = $ligne['adresse'];
			$obj->cp = $ligne['cp'];
			$obj->ville = $ligne['ville'];
			$obj->tel = $ligne['tel'];
		}
		return $obj;
	}
	
	public function getDiscipline()
	{
		$select = "SELECT * FROM discipline where id = :id";
        $select_prep = self::$db->prepare($select);
		$idDiscipline = $this->discipline->id;
        $select_prep->bindParam(":id", $idDiscipline, \PDO::PARAM_INT);
		$select_prep->execute();
        $obj = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new discipline();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
		}
		return $obj;
	}
	
	public static function getLastEvenement()
	{
		$select = "SELECT * FROM evenement ORDER BY id DESC LIMIT 0, 1";
        $select_prep = self::$db->prepare($select);
		$select_prep->execute();
        $obj = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new evenement();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->description  = $ligne['description'];
			$obj->etat = $ligne['etat'];
			$obj->dateheureLimiteInscription = date_create($ligne['dateheureLimiteInscription']);
			$obj->tarif = $ligne['tarif'];
			$obj->discipline = \sportnet\model\discipline::findById($ligne['id_discipline']);
			$obj->organisateur = \sportnet\model\organisateur::findById($ligne['id_organisateur']);
		}
		return $obj;
	}
}
?>