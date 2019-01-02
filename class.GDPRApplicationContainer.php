<?php
require_once ("class.ConnectionFactory.php");
require_once ("class.GDPRApplication.php");
require_once ("class.GDPRControllingParty.php");

class GDPRApplicationContainer

{
	private $array_application, $index = 0;

	public function __construct()
	{
	}

	public static function createByControllingPartyID($controlling_party_id)
	{
		$conn = ConnectionFactory::CreateConnection();
		$sql = "SELECT `id` FROM `application` WHERE `controlling_party_id` = " . $conn->real_escape_string($controlling_party_id) . ";";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			$i = 0;
			$instance = new self();
			while ($row = $result->fetch_assoc())
			{
				foreach($row as $key => $value)
				{
					$instance->array_application[] = GDPRApplication::createById($value);
				}
			}
			return $instance;
		}
	}

	public function getApplication($index)
	{
		$this->index = $index;
		return $this->array_application[$index];
	}

	public function getNextApplication()

	{
		if ($this->index + 1 <= $this->size()) return $this->array_application[$this->index++];
		else
		{
			echo "Out of bounds";
			return false;
		}
	}

	public function resetIndex() { $this->index = 0; }

	public function getControllingPartyID()

	{
		$controlling_party_id = NULL;
		foreach($this->array_application as $application)
		{
			if (is_null($controlling_party_id) == false) // There is already a value
			{
				if ($application->getControllingPartyID() != $controlling_party_id) // Is it same?
				{
					// throw new Exception('Non homogenous controlling parties.');
					return false;
				}
			}
			else
			{
				$controlling_party_id = $application->getControllingPartyID();
			}
		}
		return $controlling_party_id;
	}

	public function getControllingParty()

	{
		$controlling_party_id = NULL;
		if ($controlling_party_id = $this->getControllingPartyID()) return GDPRControllingParty::createById($controlling_party_id);
		else return false;
	}

	public function addApplication($application)

	{
		$this->array_application[] = $application;
	}

	public function deleteApplicationByIndex($index)

	{
		if (is_object($this->array_application[$index]) && $index < $this->size()) unset($this->array_application[$index]);
		else throw new Exception('Index out of bounds.');
		$this->array_application = array_values($this->array_application);
	}
	public function getApplications() { return $this->array_application; }

	public function setApplications($array_application) { $this->array_application = $array_application; }

	public function outputFormattedForRequest()
	{
		$text = "";
		foreach($this->array_application as $application)
		{
			$text.= "<li>" . $application->getName();
			if ($application->getRequestRemarks()) $text.= " - <i>" . $application->getRequestRemarks() . "</i>";
			$text.= "</li>\n";
		}
		return $text;
	}

	public function hasChapter9Applications()
	{
		foreach($this->array_application as $application) if ($application->isChapter9()) return true;
		return false;
	}

	public function hasChapter10Applications()
	{
		foreach($this->array_application as $application) if ($application->isChapter10()) return true;
		return false;
	}

	public function size() { return count($this->array_application); }

	public function getSize() { return count($this->array_application); }


	public function test2()
	{
		print_r($this);
	}
}
//      $myapps = GDPRApplicationContainer::createByControllingPartyID(1);
//      print_r($myapps);
//      print_r($myapps->getNextApplication());
/*       $myapp = new GDPRApplication();

$myapp->setId(3);
$myapp->setName("Bissiger Kater");
$myapp->setDescription("Kralle");
$myapp->setControllingPartyID(2);

//      $myapps->addApplication($myapp);
// print_r($myapps);
print_r($myapps->getControllingParty());
*/
?>