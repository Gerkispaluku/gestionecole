<?php

namespace PHPMaker2023\gestion_ECOLE;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Slim\Exception\HttpNotFoundException;

// Handle Routes
return function (App $app) {
    // agent
    $app->map(["GET","POST","OPTIONS"], '/AgentList[/{IdAgent}]', AgentController::class . ':list')->add(PermissionMiddleware::class)->setName('AgentList-agent-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/AgentAdd[/{IdAgent}]', AgentController::class . ':add')->add(PermissionMiddleware::class)->setName('AgentAdd-agent-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/AgentView[/{IdAgent}]', AgentController::class . ':view')->add(PermissionMiddleware::class)->setName('AgentView-agent-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/AgentEdit[/{IdAgent}]', AgentController::class . ':edit')->add(PermissionMiddleware::class)->setName('AgentEdit-agent-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/AgentDelete[/{IdAgent}]', AgentController::class . ':delete')->add(PermissionMiddleware::class)->setName('AgentDelete-agent-delete'); // delete
    $app->group(
        '/agent',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{IdAgent}]', AgentController::class . ':list')->add(PermissionMiddleware::class)->setName('agent/list-agent-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{IdAgent}]', AgentController::class . ':add')->add(PermissionMiddleware::class)->setName('agent/add-agent-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{IdAgent}]', AgentController::class . ':view')->add(PermissionMiddleware::class)->setName('agent/view-agent-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{IdAgent}]', AgentController::class . ':edit')->add(PermissionMiddleware::class)->setName('agent/edit-agent-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{IdAgent}]', AgentController::class . ':delete')->add(PermissionMiddleware::class)->setName('agent/delete-agent-delete-2'); // delete
        }
    );

    // annee
    $app->map(["GET","POST","OPTIONS"], '/AnneeList[/{IdAnnee}]', AnneeController::class . ':list')->add(PermissionMiddleware::class)->setName('AnneeList-annee-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/AnneeAdd[/{IdAnnee}]', AnneeController::class . ':add')->add(PermissionMiddleware::class)->setName('AnneeAdd-annee-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/AnneeView[/{IdAnnee}]', AnneeController::class . ':view')->add(PermissionMiddleware::class)->setName('AnneeView-annee-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/AnneeEdit[/{IdAnnee}]', AnneeController::class . ':edit')->add(PermissionMiddleware::class)->setName('AnneeEdit-annee-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/AnneeDelete[/{IdAnnee}]', AnneeController::class . ':delete')->add(PermissionMiddleware::class)->setName('AnneeDelete-annee-delete'); // delete
    $app->group(
        '/annee',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{IdAnnee}]', AnneeController::class . ':list')->add(PermissionMiddleware::class)->setName('annee/list-annee-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{IdAnnee}]', AnneeController::class . ':add')->add(PermissionMiddleware::class)->setName('annee/add-annee-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{IdAnnee}]', AnneeController::class . ':view')->add(PermissionMiddleware::class)->setName('annee/view-annee-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{IdAnnee}]', AnneeController::class . ':edit')->add(PermissionMiddleware::class)->setName('annee/edit-annee-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{IdAnnee}]', AnneeController::class . ':delete')->add(PermissionMiddleware::class)->setName('annee/delete-annee-delete-2'); // delete
        }
    );

    // classe
    $app->map(["GET","POST","OPTIONS"], '/ClasseList[/{IdClasse}]', ClasseController::class . ':list')->add(PermissionMiddleware::class)->setName('ClasseList-classe-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/ClasseAdd[/{IdClasse}]', ClasseController::class . ':add')->add(PermissionMiddleware::class)->setName('ClasseAdd-classe-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/ClasseView[/{IdClasse}]', ClasseController::class . ':view')->add(PermissionMiddleware::class)->setName('ClasseView-classe-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/ClasseEdit[/{IdClasse}]', ClasseController::class . ':edit')->add(PermissionMiddleware::class)->setName('ClasseEdit-classe-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/ClasseDelete[/{IdClasse}]', ClasseController::class . ':delete')->add(PermissionMiddleware::class)->setName('ClasseDelete-classe-delete'); // delete
    $app->group(
        '/classe',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{IdClasse}]', ClasseController::class . ':list')->add(PermissionMiddleware::class)->setName('classe/list-classe-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{IdClasse}]', ClasseController::class . ':add')->add(PermissionMiddleware::class)->setName('classe/add-classe-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{IdClasse}]', ClasseController::class . ':view')->add(PermissionMiddleware::class)->setName('classe/view-classe-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{IdClasse}]', ClasseController::class . ':edit')->add(PermissionMiddleware::class)->setName('classe/edit-classe-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{IdClasse}]', ClasseController::class . ':delete')->add(PermissionMiddleware::class)->setName('classe/delete-classe-delete-2'); // delete
        }
    );

    // eleve
    $app->map(["GET","POST","OPTIONS"], '/EleveList[/{IdEleve}]', EleveController::class . ':list')->add(PermissionMiddleware::class)->setName('EleveList-eleve-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/EleveAdd[/{IdEleve}]', EleveController::class . ':add')->add(PermissionMiddleware::class)->setName('EleveAdd-eleve-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/EleveView[/{IdEleve}]', EleveController::class . ':view')->add(PermissionMiddleware::class)->setName('EleveView-eleve-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/EleveEdit[/{IdEleve}]', EleveController::class . ':edit')->add(PermissionMiddleware::class)->setName('EleveEdit-eleve-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/EleveDelete[/{IdEleve}]', EleveController::class . ':delete')->add(PermissionMiddleware::class)->setName('EleveDelete-eleve-delete'); // delete
    $app->group(
        '/eleve',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{IdEleve}]', EleveController::class . ':list')->add(PermissionMiddleware::class)->setName('eleve/list-eleve-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{IdEleve}]', EleveController::class . ':add')->add(PermissionMiddleware::class)->setName('eleve/add-eleve-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{IdEleve}]', EleveController::class . ':view')->add(PermissionMiddleware::class)->setName('eleve/view-eleve-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{IdEleve}]', EleveController::class . ':edit')->add(PermissionMiddleware::class)->setName('eleve/edit-eleve-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{IdEleve}]', EleveController::class . ':delete')->add(PermissionMiddleware::class)->setName('eleve/delete-eleve-delete-2'); // delete
        }
    );

    // fonction
    $app->map(["GET","POST","OPTIONS"], '/FonctionList[/{IdFonction}]', FonctionController::class . ':list')->add(PermissionMiddleware::class)->setName('FonctionList-fonction-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/FonctionAdd[/{IdFonction}]', FonctionController::class . ':add')->add(PermissionMiddleware::class)->setName('FonctionAdd-fonction-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/FonctionAddopt', FonctionController::class . ':addopt')->add(PermissionMiddleware::class)->setName('FonctionAddopt-fonction-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/FonctionView[/{IdFonction}]', FonctionController::class . ':view')->add(PermissionMiddleware::class)->setName('FonctionView-fonction-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/FonctionEdit[/{IdFonction}]', FonctionController::class . ':edit')->add(PermissionMiddleware::class)->setName('FonctionEdit-fonction-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/FonctionDelete[/{IdFonction}]', FonctionController::class . ':delete')->add(PermissionMiddleware::class)->setName('FonctionDelete-fonction-delete'); // delete
    $app->group(
        '/fonction',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{IdFonction}]', FonctionController::class . ':list')->add(PermissionMiddleware::class)->setName('fonction/list-fonction-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{IdFonction}]', FonctionController::class . ':add')->add(PermissionMiddleware::class)->setName('fonction/add-fonction-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADDOPT_ACTION') . '', FonctionController::class . ':addopt')->add(PermissionMiddleware::class)->setName('fonction/addopt-fonction-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{IdFonction}]', FonctionController::class . ':view')->add(PermissionMiddleware::class)->setName('fonction/view-fonction-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{IdFonction}]', FonctionController::class . ':edit')->add(PermissionMiddleware::class)->setName('fonction/edit-fonction-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{IdFonction}]', FonctionController::class . ':delete')->add(PermissionMiddleware::class)->setName('fonction/delete-fonction-delete-2'); // delete
        }
    );

    // inscription
    $app->map(["GET","POST","OPTIONS"], '/InscriptionList[/{IdInscription}]', InscriptionController::class . ':list')->add(PermissionMiddleware::class)->setName('InscriptionList-inscription-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/InscriptionAdd[/{IdInscription}]', InscriptionController::class . ':add')->add(PermissionMiddleware::class)->setName('InscriptionAdd-inscription-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/InscriptionView[/{IdInscription}]', InscriptionController::class . ':view')->add(PermissionMiddleware::class)->setName('InscriptionView-inscription-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/InscriptionEdit[/{IdInscription}]', InscriptionController::class . ':edit')->add(PermissionMiddleware::class)->setName('InscriptionEdit-inscription-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/InscriptionDelete[/{IdInscription}]', InscriptionController::class . ':delete')->add(PermissionMiddleware::class)->setName('InscriptionDelete-inscription-delete'); // delete
    $app->group(
        '/inscription',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '[/{IdInscription}]', InscriptionController::class . ':list')->add(PermissionMiddleware::class)->setName('inscription/list-inscription-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '[/{IdInscription}]', InscriptionController::class . ':add')->add(PermissionMiddleware::class)->setName('inscription/add-inscription-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config('VIEW_ACTION') . '[/{IdInscription}]', InscriptionController::class . ':view')->add(PermissionMiddleware::class)->setName('inscription/view-inscription-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config('EDIT_ACTION') . '[/{IdInscription}]', InscriptionController::class . ':edit')->add(PermissionMiddleware::class)->setName('inscription/edit-inscription-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config('DELETE_ACTION') . '[/{IdInscription}]', InscriptionController::class . ':delete')->add(PermissionMiddleware::class)->setName('inscription/delete-inscription-delete-2'); // delete
        }
    );

    // affectation
    $app->map(["GET","POST","OPTIONS"], '/AffectationList', AffectationController::class . ':list')->add(PermissionMiddleware::class)->setName('AffectationList-affectation-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/AffectationAdd', AffectationController::class . ':add')->add(PermissionMiddleware::class)->setName('AffectationAdd-affectation-add'); // add
    $app->group(
        '/affectation',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config('LIST_ACTION') . '', AffectationController::class . ':list')->add(PermissionMiddleware::class)->setName('affectation/list-affectation-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config('ADD_ACTION') . '', AffectationController::class . ':add')->add(PermissionMiddleware::class)->setName('affectation/add-affectation-add-2'); // add
        }
    );

    // login
    $app->map(["GET","POST","OPTIONS"], '/login[/{provider}]', OthersController::class . ':login')->add(PermissionMiddleware::class)->setName('login');

    // logout
    $app->map(["GET","POST","OPTIONS"], '/logout', OthersController::class . ':logout')->add(PermissionMiddleware::class)->setName('logout');

    // captcha
    $app->map(["GET","OPTIONS"], '/captcha[/{page}]', OthersController::class . ':captcha')->add(PermissionMiddleware::class)->setName('captcha');

    // Swagger
    $app->get('/' . Config("SWAGGER_ACTION"), OthersController::class . ':swagger')->setName(Config("SWAGGER_ACTION")); // Swagger

    // Index
    $app->get('/[index]', OthersController::class . ':index')->add(PermissionMiddleware::class)->setName('index');

    // Route Action event
    if (function_exists(PROJECT_NAMESPACE . "Route_Action")) {
        if (Route_Action($app) === false) {
            return;
        }
    }

    /**
     * Catch-all route to serve a 404 Not Found page if none of the routes match
     * NOTE: Make sure this route is defined last.
     */
    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        function ($request, $response, $params) {
            throw new HttpNotFoundException($request, str_replace("%p", $params["routes"], Container("language")->phrase("PageNotFound")));
        }
    );
};
