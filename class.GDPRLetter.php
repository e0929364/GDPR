<?php
require_once('class.PDFLetter.php');
require_once('class.GDPRApplicationContainer.php');
require_once('class.GDPRRequestor.php');

class GDPRLetter extends PDFLetter
{
	private $application_container = NULL, $requestor = NULL, $eidas_text = false, $id_document_text = 'Lichtbildausweis', $controlling_party = NULL, $text_html_file = "text.GDPRLetter.inc";

        public function getApplicationContainer() { return $this->application_container; }
        public function setApplicationContainer($application_container) { $this->application_container = $application_container; }

        public function getRequestor() { return $this->requestor; }
        public function setRequestor($requestor) { $this->requestor = $requestor; }

        public function isEIDASText() { return $this->eidas_text; }
        public function setEIDASText($eidas_text) { $this->eidas_text = $eidas_text; }

        public function getIDDocumentText() { return $this->id_document_text; }
        public function setIDDocumentText($id_document_text) { $this->id_document_text = $id_document_text; }

    	public function getControllingParty() { return $this->controlling_party; }
		public function setControllingParty($controlling_party) { $this->controlling_party = $controlling_party; }

        public function getTextHTMLFile() { return $this->text_html_file; }
        public function setTextHTMLFile($text_html_file) { $this->text_html_file = $text_html_file; }

		public function __construct($application_container = NULL, $requestor = NULL, $date = NULL, $eidas_text = false, $id_document_text = NULL, $controlling_party = NULL, $text_html_file = NULL)
		{
                if($controlling_party instanceof GDPRControllingParty)
				{
                        $this->setControllingParty($controlling_party);
				}
				else
				{
				// Try to identify controlling party from applications, if it is possible - i.e. single controlling party.
				if($application_container instanceof GDPRApplicationContainer && ($controlling_party = $application_container->getControllingParty() ) )
				{
						$this->setControllingParty($controlling_party);
				}

			}
		
			if(is_string($date))
				$this->setDate($date);

			if(is_string($id_document_text))
				$this->setIDDocumentText($id_document_text);

			if($eidas_text == true)
				$this->setEIDASText(true);

			if(is_string($text_html_file))
			{
				$this->setTextHTMLFile($text_html_file);
			}
			else
			{
				if($this->getControllingParty()->isPublicSector())
				{
					if($this->isEIDASText())
						$this->setTextHTMLFile("text.GDPRLetterPublicSectorEIDAS.inc");
					else
						$this->setTextHTMLFile("text.GDPRLetterPublicSector.inc");
				}
				else
				{
					if($this->isEIDASText())
						$this->setTextHTMLFile("text.GDPRLetterPrivateSectorEIDAS.inc");
					else
						$this->setTextHTMLFile("text.GDPRLetterPrivateSector.inc");
				}
			}
			if($application_container instanceof GDPRApplicationContainer)
				$this->setApplicationContainer($application_container);
			else
				throw new Exception("No Application Container provided!");

			if($requestor instanceof GDPRRequestor)
				$this->setRequestor($requestor);
			else
				$this->setRequestor(new GDPRRequestor("Max Mustermann", "Schenkenstrasse 4\n1010 Wien", "max.mustermann@bvt.gv.at", '01.01.1980') );


			$this->createGDPRTextHTML();

	        parent::__construct($this->getControllingParty()->getFullAddress(), $this->getRequestor()->getFullAddress(),  $this->getDate(), "at.gv.bella-ciao-kickl-p", false, $this->getTextHTML());

	}

	public function createGDPRTextHTML()
	{

		$chapter_9_10_text = "";

		$appcon = $this->getApplicationContainer();

		if($appcon->hasChapter9Applications())
			$chapter_9_10_text = " inkl. besonderer Daten i.S.d. Art.&nbsp;9&nbsp;DSGVO";
		if($appcon->hasChapter10Applications())
    	   	$chapter_9_10_text = " inkl. strafrechtlich relevanter Daten i.S.d. Art.&nbsp;10&nbsp;DSGVO";
		if($appcon->hasChapter9Applications() && $appcon->hasChapter10Applications())
        	$chapter_9_10_text = " inkl. besonderer und strafrechtlich relevanter Daten i.S.d. Art.&nbsp;9/10&nbsp;DSGVO";

		$cp = $this->getControllingParty();

		$application_list_formatted = $appcon->outputFormattedForRequest();

		$text_html = file_get_contents($this->getTextHTMLFile());

		$id_dataset_html = $this->getRequestor()->outputFormattedForRequest(); 

		$text_html = str_replace('%ID_DATASET%', $id_dataset_html, $text_html);
		$text_html = str_replace('%ID_DOCUMENTS%', $this->getIDDocumentText(), $text_html);
		$text_html = str_replace('%REQUESTOR_NAME%', $this->getRequestor()->getName(), $text_html);
		$text_html = str_replace('%APPLICATION_LIST%', $application_list_formatted, $text_html);
		$text_html = str_replace('%CHAPTER_9_10_TEXT%', $chapter_9_10_text, $text_html);

		$this->setTextHTML($text_html);
	}


}


	$myapps = GDPRApplicationContainer::createByControllingPartyID(1);
//	print_r($myapps);

	$myGDPRLetter = new GDPRLetter($myapps, false, "01.01.1997", true);
//	print_r($myGDPRLetter);
	$myGDPRLetter->generate();
?>
