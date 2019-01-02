<?php
require_once ("class.ConnectionFactory.php");

class GDPRApplication

{
	private $id = 0, $controlling_party_id = 0, $name = '', $description = '', $request_remarks = '', $chapter_9 = false, $chapter_10 = false;
	public function __construct($id = 0, $controlling_party_id = 0, $name = '', $description = '', $request_remarks = '', $chapter_9 = false, $chapter_10 = false)

	{
		$this->setId($id);
		$this->setControllingPartyID($controlling_party_id);
		$this->setName($name);
		$this->setDescription($description);
		$this->setRequestRemarks($request_remarks);
		$this->setChapter9($chapter_9);
		$this->setChapter10($chapter_10);
	}
	public static function createByID($id)

	{
		$instance = new self();
		$instance->id = $id;
		$instance->update();
		return $instance;
	}

	public function getId() { return $this->id; }
	public function setId($id) { if(is_numeric($this->id) == true) $this->id = $id;  }

	public function getControllingPartyID() { return $this->controlling_party_id; }
	public function setControllingPartyID($controlling_party_id) { $this->controlling_party_id = $controlling_party_id; }

	public function getName() { return $this->name; }
	public function setName($name) { $this->name = $name; }

	public function getDescription() { return $this->description; }
	public function setDescription($description) { $this->description = $description; }

	public function getRequestRemarks() { return $this->request_remarks; }
	public function setRequestRemarks($request_remarks) { $this->request_remarks = $request_remarks; }

	public function isChapter9() { return $this->chapter_9; }
	public function setChapter9($chapter_9) { $this->chapter_9 = $chapter_9; }

	public function isChapter10() { return $this->chapter_10; }
	public function setChapter10($chapter_10) { $this->chapter_10 = $chapter_10; }

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
			$sql = "INSERT INTO `application` (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $values) . ");";
		}
		else
		{
			$sql_inserts = array();
			foreach($vars as $key => $value) if (is_null($value) == false) $sql_inserts[] = "`" . $conn->real_escape_string($key) . "` = '" . $conn->real_escape_string($value) . "'";
			$sql = "INSERT INTO `application` SET " . implode(", ", $sql_inserts) . " ON DUPLICATE KEY UPDATE " . implode(", ", $sql_inserts) . ";";
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
		$sql = $conn->real_escape_string("SELECT * FROM `application` WHERE `id` = " . $this->id . ";");
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			foreach($row as $key => $value) $this->$key = $value;
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
/*	$myapp = new GDPRApplication();

$myapp->setId(0);
$myapp->setName("Bissiger Kater");
$myapp->setDescription("Kralle");
$myapp->setControllingPartyID(2);
echo $myapp->getId()."\n";
$myapp->commit();
echo $myapp->getId()."\n";
$myapp->update();
*/
/*	$myapp = GDPRApplication::createById(1);
$myapp->test2();
echo "\n\n";
echo $myapp->outputFormattedForRequest();
*/
//	$myapps = GDPRApplicationList::createByControllingPartyID(1);
//	print_r($myapps);
//	print_r($myapps->getNextApplication());
//	print_r($myapps->getNextApplication());
//	echo $myapps->outputFormattedForRequest();
//	echo $myapps->hasChapter9Applications();

?>