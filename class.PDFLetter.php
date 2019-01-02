<?php
require_once('tcpdf/tcpdf.php');

class MYPDF extends TCPDF
{
	protected $footer_left = '', $footer_right = '';

	public function getFooterLeft() { return $this->footer_left; }
	public function setFooterLeft($footer_left) { $this->footer_left = $footer_left; }

	public function getFooterRight() { return $this->footer_right; }
	public function setFooterRight($footer_right) { $this->footer_right = $footer_right; }

	// Page header
	public function Header()
	{
		//$this->SetFont('Helvetica', '', 8);
		//$this->Cell(185, 6, 'Sag "NEIN!" zum Register-, Kontroll-, Polizei- und Überwachungsstaat!', 0, false, 'C', 0, '', 0, false, 'T', 'C');
		//$this->Cell(30, 6, 'Dokumenten ID:'.bin2hex(random_bytes(5)), 0, false, 'L', 0, '', 0, false, 'T', 'M');
		//$this->Cell(125, 6, $this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'C');
		//$this->Cell(30, 6, date('d.m.Y H:i:s'), 0, false, 'L', 0, '', 0, false, 'T', 'M');
	}

	// Page footer
	public function Footer()
	{
		// Position at 15 mm from bottom
		$this->SetY(-15);
		$this->SetFont('helvetica', '', 8);
		$this->Cell(30, 6, $this->getFooterLeft() , 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->Cell(125, 6, $this->getAliasNumPage() . '/' . $this->getAliasNbPages() , 0, false, 'C', 0, '', 0, false, 'T', 'C');
		$this->Cell(30, 6, $this->getFooterRight() , 0, false, 'L', 0, '', 0, false, 'T', 'M');
	}
}

class PDFLetter
{
	protected $address_receiver = '', $address_sender = '', $date = '', $footer_left = '', $footer_right = '', $text_html = '';

	public function __construct($address_receiver = '', $address_sender = '', $date = NULL, $footer_left = '', $footer_right = NULL, $text_html = '', $pdf_title = '', $pdf_creator = '')
	{
		$this->setAddressReceiver($address_receiver);
		$this->setAddressSender($address_sender);

		if (is_string($date) == false)
			$date = date('d.m.Y');

		$this->setDate($date);
		$this->setFooterLeft($footer_left);

		if (is_string($this->footer_right))
			$footer_right = date('d.m.Y H:i:s');

		$this->setFooterRight($footer_right);
		$this->setTextHTML($text_html);
		$this->setPDFCreator($pdf_creator);
		$this->setPDFTitle($pdf_title);
	}

	public function getAddressReceiver() { return $this->address_receiver; }
	public function setAddressReceiver($address_receiver) { $this->address_receiver = $address_receiver; }

	public function getAddressSender() { return $this->address_sender; }
	public function setAddressSender($address_sender) { $this->address_sender = $address_sender; }


	public function getDate() { return $this->date; }
	public function setDate($date) { $this->date = $date; }

	public function getFooterLeft() { return $this->footer_left; }
	public function setFooterLeft($footer_left) { $this->footer_left = $footer_left; }

	public function getFooterRight() { return $this->footer_right; }
	public function setFooterRight($footer_right) { $this->footer_right = $footer_right; }


	public function getTextHTML() { return $this->text_html; }
	public function setTextHTML($text_html) { $this->text_html = $text_html; }

	public function getPDFCreator() { return $this->pdf_creator; }
	public function setPDFCreator($pdf_creator) { $this->pdf_creator = $pdf_creator; }

	public function getPDFTitle() { return $this->pdf_title; }
	public function setPDFTitle($pdf_title) { $this->pdf_title = $pdf_title; }

	public function generate()

	{
		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setFooterLeft($this->getFooterLeft());
		$pdf->setFooterRight($this->getFooterRight());
		// set document information
		$pdf->SetCreator($this->getPDFCreator());
		// $pdf->SetAuthor('ABC');
		$pdf->SetTitle($this->getPDFTitle());
		// $pdf->SetSubject('DSGVO');
		// $pdf->SetKeywords('ABC, PDF, example, test, guide');
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__) . '/lang/eng.php'))
		{
			require_once (dirname(__FILE__) . '/lang/eng.php');
			$pdf->setLanguageArray($l);
		}
		$pdf->AddPage();
		$pdf->SetFont('helvetica', '', 11);
		$pdf->SetY(48);
		$pdf->SetX(23);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->MultiCell(60, 20, $this->getAddressReceiver() , 0, 'L', 1, 0, '', '', true);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetY(10);
		$pdf->SetX(15);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->MultiCell(60, 20, $this->getAddressSender() , 0, 'L', 1, 0, '', '', true);
		$pdf->SetY(85);
		$pdf->SetX(-40);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->Cell(25, 6, $this->getDate() . ' ', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$pdf->SetX(15);
		$pdf->writeHTML($this->getTextHTML() , true, false, true, false, '');
		$pdf->Output('dokument_' . date('Ymd_His') . '.pdf');
	}
}

?>