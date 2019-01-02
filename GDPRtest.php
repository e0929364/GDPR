<?php

require_once("class.GDPRApplication.php");
require_once("class.GDPRApplicationContainer.php");


        $test1 = new GDPRApplication (8, 1, 'Test Appl 1', 'Nur zur Probe', '', false, false);
        $test2 = new GDPRApplication (9, 1, 'Test Appl 2', 'Auch nur zum Testen', '', false, false);

        $appl_liste = new GDPRApplicationContainer();
        $appl_liste->addApplication($test1);
        $appl_liste->addApplication($test2);
        $appl_liste->addApplication($test2);

        $appl_liste->deleteApplicationByIndex(2);

        echo "Groesse = ".$appl_liste->getSize()."\n";

        print_r($appl_liste);

//        while($appl = $appl_liste->getNextApplication())
//              echo $appl->getName()."\n";

	$thisappl = new GDPRApplication(1);

	print_r($thisappl);

	$thisappl->update();

	print_r($thisappl);

?>
