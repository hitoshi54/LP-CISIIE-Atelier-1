<?php
namespace sportnet\model;
class participant extends AbstractModel {
	protected  $id;
	protected  $nom;
	protected  $prenom;
	protected  $rue;
	protected  $cp;
	protected  $ville;
	protected  $tel;
	
	
	public function __construct()
	{
		$connect = new \sportnet\utils\ConnectionFactory();
		$connect->setConfig("conf/config.ini");
		self::$db = $connect->makeConnection();
		self::$db->query("SET CHARACTER SET utf8");
	}
	
	protected function update()
	{
		$update = "UPDATE participant SET nom = :nom, prenom = :prenom, rue = :rue, cp = :cp, ville = :ville, tel = :tel WHERE id = :id";
		$update_prep = self::$db->prepare($update);
		$update_prep->bindParam(':nom', $this->nom, \PDO::PARAM_STR);
        $update_prep->bindParam(':prenom', $this->prenom, \PDO::PARAM_STR);
        $update_prep->bindParam(':rue', $this->rue, \PDO::PARAM_STR);
        $update_prep->bindParam(':cp', $this->cp, \PDO::PARAM_STR);
		$update_prep->bindParam(':ville', $this->ville, \PDO::PARAM_STR);
		$update_prep->bindParam(':tel', $this->tel, \PDO::PARAM_STR);
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
		$insert = "INSERT INTO participant VALUES(NULL, :nom, :prenom, :rue, :cp, :ville, :tel)";
        $insert_prep = self::$db->prepare($insert);
		$insert_prep->bindParam(':nom', $this->nom, \PDO::PARAM_STR);
        $insert_prep->bindParam(':prenom', $this->prenom, \PDO::PARAM_STR);
        $insert_prep->bindParam(':rue', $this->rue, \PDO::PARAM_STR);
        $insert_prep->bindParam(':cp', $this->cp, \PDO::PARAM_STR);
		$insert_prep->bindParam(':ville', $this->ville, \PDO::PARAM_STR);
		$insert_prep->bindParam(':tel', $this->tel, \PDO::PARAM_STR);
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
		$delete = "DELETE FROM participant WHERE id = :id";
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
        $selectById = "SELECT * FROM participant WHERE id = :id";
        $selectById_prep = self::$db->prepare($selectById);
        $selectById_prep->bindParam(':id', $leId, \PDO::PARAM_INT);
        $selectById_prep->execute();
		$obj = null;
		while ($ligne = $selectById_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new participant();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->prenom = $ligne['prenom'];
			$obj->rue = $ligne['rue'];
			$obj->cp = $ligne['cp'];
			$obj->ville = $ligne['ville'];
			$obj->tel = $ligne['tel'];
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
        $select = "SELECT * FROM participant";
        $select_prep = self::$db->prepare($select);
		$select_prep->execute();
        $tab = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new participant();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->prenom = $ligne['prenom'];
			$obj->rue = $ligne['rue'];
			$obj->cp = $ligne['cp'];
			$obj->ville = $ligne['ville'];
			$obj->tel = $ligne['tel'];

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
        $selectByName = "SELECT * FROM participant WHERE nom = :nom";
        $selectByName_prep = self::$db->prepare($selectByName);
        $selectByName_prep->bindParam(':nom', $leNom, \PDO::PARAM_STR);
        $selectByName_prep->execute();
		$obj = null;
		while ($ligne = $selectByName_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new participant();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->prenom = $ligne['prenom'];
			$obj->rue = $ligne['rue'];
			$obj->cp = $ligne['cp'];
			$obj->ville = $ligne['ville'];
			$obj->tel = $ligne['tel'];
		}
		return $obj;
	}
	
	public static function getLastParticipant()
	{
		$select = "SELECT * FROM participant ORDER BY id DESC LIMIT 0, 1";
        $select_prep = self::$db->prepare($select);
		$select_prep->execute();
        $obj = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new participant();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->prenom = $ligne['prenom'];
			$obj->rue = $ligne['rue'];
			$obj->cp = $ligne['cp'];
			$obj->ville = $ligne['ville'];
			$obj->tel = $ligne['tel'];
		}
		return $obj;
	}
}
?>