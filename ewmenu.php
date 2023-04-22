<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(2, "mi_agent", $Language->MenuPhrase("2", "MenuText"), "agentlist.php", -1, "", AllowListMenu('{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}agent'), FALSE, FALSE);
$RootMenu->AddMenuItem(1, "mi_affectation", $Language->MenuPhrase("1", "MenuText"), "affectationlist.php", -1, "", AllowListMenu('{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}affectation'), FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mi_classe", $Language->MenuPhrase("4", "MenuText"), "classelist.php", -1, "", AllowListMenu('{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}classe'), FALSE, FALSE);
$RootMenu->AddMenuItem(5, "mi_eleve", $Language->MenuPhrase("5", "MenuText"), "elevelist.php", -1, "", AllowListMenu('{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}eleve'), FALSE, FALSE);
$RootMenu->AddMenuItem(7, "mi_inscription", $Language->MenuPhrase("7", "MenuText"), "inscriptionlist.php", -1, "", AllowListMenu('{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}inscription'), FALSE, FALSE);
$RootMenu->AddMenuItem(8, "mci_Administration", $Language->MenuPhrase("8", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(6, "mi_fonction", $Language->MenuPhrase("6", "MenuText"), "fonctionlist.php", 8, "", AllowListMenu('{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}fonction'), FALSE, FALSE);
$RootMenu->AddMenuItem(9, "mci_Ajout_Anne9e", $Language->MenuPhrase("9", "MenuText"), "", 8, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(3, "mi_annee", $Language->MenuPhrase("3", "MenuText"), "anneelist.php", 9, "", AllowListMenu('{84EC96E8-6D54-4BAB-B15E-1EA379B0779B}annee'), FALSE, FALSE);
$RootMenu->AddMenuItem(12, "mi_userlevels", $Language->MenuPhrase("12", "MenuText"), "userlevelslist.php", -1, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE, FALSE);
$RootMenu->AddMenuItem(11, "mi_userlevelpermissions", $Language->MenuPhrase("11", "MenuText"), "userlevelpermissionslist.php", -1, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE, FALSE);
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
