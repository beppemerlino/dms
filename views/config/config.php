<?php


$array_route = array(
    array("Categoria" => "GESTIONE", "ListaMenu" =>
        array(
            array("Gruppo" => array("SuperAdmins", "Admins"), "Scheda" => "UTENTI", "SchedaLang" => strtoupper("utenti"), "Icon" => "users-solid",
                "Submenu" => array(
                    array("href" => "gestisci_utenti", "caption" => "Gestione Utenti"),
                    array("href" => "gestisci_teams", "caption" => "Gestione Teams")
                )
            ),
            array("Gruppo" => array("SuperAdmins", "Admins", "Users", "SuperUsers"), "Scheda" => "COMMESSE", "SchedaLang" => strtoupper("commesse"), "Icon" => "hand-shake",
                "Submenu" => array(
                    array("href" => "crea_commessa", "caption" => "Crea/Modifica Commessa"),
                    array("href" => "rendicontazione", "caption" => "Rendicontazione"),
                    array("href" => "report", "caption" => "Report")
                )
            ),
            array("Gruppo" => array("SuperAdmins", "Admins"), "Scheda" => "HARDWARE", "SchedaLang" => strtoupper("gestione hardware"), "Icon" => "workstation",
                "Submenu" => array(
                    array("href" => "gestione_computer", "caption" => "Computer"),
                    array("href" => "gestione_monitor", "caption" => "Monitor"),
                    array("href" => "gestione_workstation", "caption" => "Workstation"),
                    array("href" => "gestione_printer", "caption" => "Printer"),
                    array("href" => "gestione_nas", "caption" => "NAS"),
                    array("href" => "gestione_device", "caption" => "Device"),
                    array("href" => "inventario", "caption" => "Inventario")

                )
            ),
            array("Gruppo" => array("SuperAdmins", "Admins"), "Scheda" => "SOFTWARE", "SchedaLang" => strtoupper("gestione_software"), "Icon" => "software",
                "Submenu" => array(
                    array("href" => "gestione_software", "caption" => "Software")
                )
            ),
        )
    )
);

define('ARRAY_ROUTE', $array_route);




