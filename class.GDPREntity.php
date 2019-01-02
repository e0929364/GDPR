<?php
abstract class GDPREntity
{
	protected $name = '', $address = '', $email = '';

	public function __construct($name = '', $address = '', $email = '')
	{
		$this->setName($name);
		$this->setAddress($address);
		$this->setEmail($email);
	}

	public function getName() { return $this->name; }
	public function setName($name) { $this->name = $name; }

	public function getAddress() { return $this->address; }
	public function setAddress($address) { $this->address = $address; }

	public function getEmail() { return $this->email; }
	public function setEmail($email) { $this->email = $email; }

    abstract public function getFullAddress();

}
?>
