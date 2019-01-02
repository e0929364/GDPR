<?php
require_once("class.GDPREntity.php");

class GDPRRequestor extends GDPREntity
{
	private $birthdate = '';

	public function __construct($name = '', $address = '', $email = '', $birthdate = '')
	{
		parent::__construct($name, $address, $email);
		$this->setBirthdate($birthdate);
	}

	public function getBirthdate() { return $this->birthdate; }
	public function setBirthdate($birthdate) { $this->birthdate = $birthdate; }

	public function getFullAddress() { return $this->getName() . "\n" . $this->getAddress(); }

	public function outputFormattedForRequest()
	{
		$text = '';
		if (strlen($this->getName())) $text.= "Name: " . $this->getName() . "<br />";
		if (strlen($this->getBirthdate())) $text.= "Geburtsdatum: " . $this->getBirthdate() . "<br />";
		if (strlen($this->getEmail())) $text.= "e-Mail: " . $this->getEmail() . "<br />";
		return $text;
	}

	public function test2()
	{
		var_dump($this);
	}
}
// $mycp = GDPRControllingParty::createById(1);
// print_r($mycp);
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
