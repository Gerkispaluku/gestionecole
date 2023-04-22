<?php

namespace PHPMaker2023\gestion_ECOLE;

// Menu Language
if ($Language && function_exists(PROJECT_NAMESPACE . "Config") && $Language->LanguageFolder == Config("LANGUAGE_FOLDER")) {
    $MenuRelativePath = "";
    $MenuLanguage = &$Language;
} else { // Compat reports
    $LANGUAGE_FOLDER = "../lang/";
    $MenuRelativePath = "../";
    $MenuLanguage = Container("language");
}

// Navbar menu
$topMenu = new Menu("navbar", true, true);
$topMenu->addMenuItem(7, "mi_inscription", $MenuLanguage->MenuPhrase("7", "MenuText"), $MenuRelativePath . "InscriptionList", -1, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}inscription'), false, false, "", "", true, false);
$topMenu->addMenuItem(8, "mi_affectation", $MenuLanguage->MenuPhrase("8", "MenuText"), $MenuRelativePath . "AffectationList", -1, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}affectation'), false, false, "", "", true, false);
$topMenu->addMenuItem(6, "mci_Acceuil", $MenuLanguage->MenuPhrase("6", "MenuText"), "", -1, "", true, false, true, "", "", true, false);
$topMenu->addMenuItem(1, "mi_agent", $MenuLanguage->MenuPhrase("1", "MenuText"), $MenuRelativePath . "AgentList", 6, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}agent'), false, false, "", "", true, false);
$topMenu->addMenuItem(4, "mi_eleve", $MenuLanguage->MenuPhrase("4", "MenuText"), $MenuRelativePath . "EleveList", -1, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}eleve'), false, false, "", "", true, false);
$topMenu->addMenuItem(15, "mci_Administration", $MenuLanguage->MenuPhrase("15", "MenuText"), "", -1, "", true, false, true, "", "", true, false);
$topMenu->addMenuItem(5, "mi_fonction", $MenuLanguage->MenuPhrase("5", "MenuText"), $MenuRelativePath . "FonctionList", 15, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}fonction'), false, false, "", "", true, false);
$topMenu->addMenuItem(3, "mi_classe", $MenuLanguage->MenuPhrase("3", "MenuText"), $MenuRelativePath . "ClasseList", 15, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}classe'), false, false, "", "", true, false);
$topMenu->addMenuItem(2, "mi_annee", $MenuLanguage->MenuPhrase("2", "MenuText"), $MenuRelativePath . "AnneeList", 15, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}annee'), false, false, "", "", true, false);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(7, "mi_inscription", $MenuLanguage->MenuPhrase("7", "MenuText"), $MenuRelativePath . "InscriptionList", -1, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}inscription'), false, false, "", "", true, true);
$sideMenu->addMenuItem(8, "mi_affectation", $MenuLanguage->MenuPhrase("8", "MenuText"), $MenuRelativePath . "AffectationList", -1, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}affectation'), false, false, "", "", true, true);
$sideMenu->addMenuItem(6, "mci_Acceuil", $MenuLanguage->MenuPhrase("6", "MenuText"), "", -1, "", true, false, true, "", "", true, true);
$sideMenu->addMenuItem(1, "mi_agent", $MenuLanguage->MenuPhrase("1", "MenuText"), $MenuRelativePath . "AgentList", 6, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}agent'), false, false, "", "", true, true);
$sideMenu->addMenuItem(4, "mi_eleve", $MenuLanguage->MenuPhrase("4", "MenuText"), $MenuRelativePath . "EleveList", -1, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}eleve'), false, false, "", "", true, true);
$sideMenu->addMenuItem(15, "mci_Administration", $MenuLanguage->MenuPhrase("15", "MenuText"), "", -1, "", true, false, true, "", "", true, true);
$sideMenu->addMenuItem(5, "mi_fonction", $MenuLanguage->MenuPhrase("5", "MenuText"), $MenuRelativePath . "FonctionList", 15, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}fonction'), false, false, "", "", true, true);
$sideMenu->addMenuItem(3, "mi_classe", $MenuLanguage->MenuPhrase("3", "MenuText"), $MenuRelativePath . "ClasseList", 15, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}classe'), false, false, "", "", true, true);
$sideMenu->addMenuItem(2, "mi_annee", $MenuLanguage->MenuPhrase("2", "MenuText"), $MenuRelativePath . "AnneeList", 15, "", IsLoggedIn() || AllowListMenu('{210BB35D-0141-4761-AC04-31A7CA7DF2C3}annee'), false, false, "", "", true, true);
echo $sideMenu->toScript();
