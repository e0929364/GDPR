<?php
require_once ("class.ConnectionFactory.php");
require_once("class.GDPREntity.php");

class GDPRControllingParty extends GDPREntity
{
	private $id = 0, $unit = '', $public_sector = 0;

	public function __construct($id = 0, $unit = '', $public_sector = 0)
	{
		parent::__construct($name, $address, $email);
		$this->setId($id);
		$this->setUnit($unit);

	}

	public static function createById($id)
	{
		$instance = new self();
		$instance->id = $id;
		$instance->update();
		return $instance;
	}

	public function getId() { return $this->id; }
	public function setId($id) { if (is_numeric($this->id) == true) $this->id = $id; }

	public function getUnit() { return $this->unit; }
	public function setUnit($unit) { $this->unit = $unit; }

	public function isPublicSector() { return $this->public_sector; }
	public function setPublicSector($public_sector) { $this->public_sector = $public_sector; }

	public function getFullAddress()
	{
		if (strlen($this->getUnit())) return $this->getName() . "\n" . $this->getUnit() . "\n" . $this->getAddress();
		else return $this->getName() . "\n" . $this->getAddress();
	}

	public function commit()
	{
		$conn = ConnectionFactory::CreateConnection();
		$sql_inserts = array();
		$vars = get_object_vars($this);
		if ($this->id == 0)
		{
			$keys = array();
			$values = array();
			echo "No ID\n";
			foreach($vars as $key => $value)
			{
				if (is_null($value) == false)
				{
					$keys[] = "`" . $conn->real_escape_string($key) . "`";
					$values[] = "'" . $conn->real_escape_string($value) . "'";
				}
			}

			$sql = "INSERT INTO `controlling_party` (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $values) . ");";
		}
		else
		{
			$sql_inserts = array();
			foreach($vars as $key => $value)
			if (is_null($value) == false) $sql_inserts[] = "`" . $conn->real_escape_string($key) . "` = '" . $conn->real_escape_string($value) . "'";
			$sql = "INSERT INTO `controlling_party` SET " . implode(", ", $sql_inserts) . " ON DUPLICATE KEY UPDATE " . implode(", ", $sql_inserts) . ";";
		}

		$result = $conn->query($sql);
		if ($this->id == 0)
		{
			$sql = "SELECT LAST_INSERT_ID();";
			$result = $conn->query($sql);
			if ($result->num_rows > 0)
			{
				$row = $result->fetch_row();
				if (is_numeric($row[0])) $this->id = $row[0];
			}
		}
	}

	public function update()
	{
		$conn = ConnectionFactory::CreateConnection();
		$sql = $conn->real_escape_string("SELECT * FROM `controlling_party` WHERE `id` = " . $this->id . ";");
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			foreach($row as $key => $value)
				$this->$key = $value;
			return true;
		}
		else
		{
			return false;
		}
	}

	public function test2()
	{
		var_dump($this);
	}
}

//$mycp = GDPRControllingParty::createById(1);
///rint_r($mycp);

/*	$myapp = new GDPRApplication();

$myapp->setId(0);
$myapp->setName("Bissiger Kater");
$myapp->setDescription("Kralle");
$myapp->setControllingPartyId(2);
echo $myapp->getId()."\n";
$myapp->commit();
echo $myapp->getId()."\n";
$myapp->update();
*/

//	$myapps = GDPRApplicationList::createByControllingPartyId(1);
//	print_r($myapps);
//	print_r($myapps->getNextApplication());
//	print_r($myapps->getNextApplication());
//	echo $myapps->outputFormattedForRequest();
//	echo $myapps->hasChapter9Applications();

?>
