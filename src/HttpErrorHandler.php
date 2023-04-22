<?php

namespace PHPMaker2023\gestion_ECOLE;

use Slim\Routing\RouteContext;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler;
use Exception;
use Throwable;

class HttpErrorHandler extends ErrorHandler
{
    protected $error;
    public $ShowSourceCode = false;
    public $LayoutTemplate = "";
    public $ErrorTemplate = "";

    // Log error
    protected function logError(string $err): void
    {
        Log($err);
    }

    // Set error
    protected function setError($exception)
    {
        global $Language;
        $Language = Container("language");
        $this->error = [
            "statusCode" => 200,
            "error" => [
                "class" => "text-danger",
                "type" => $Language->phrase("Error"),
                "description" => $Language->phrase("ServerError"),
            ],
        ];
        if ($exception instanceof HttpException) {
            $description = $exception->getMessage();
            if (
                $exception instanceof HttpNotFoundException || // 404
                $exception instanceof HttpMethodNotAllowedException || // 405
                $exception instanceof HttpUnauthorizedException || // 401
                $exception instanceof HttpForbiddenException || // 403
                $exception instanceof HttpBadRequestException || // 400
                $exception instanceof HttpInternalServerErrorException || // 500
                $exception instanceof HttpNotImplementedException // 501
            ) {
                $statusCode = $exception->getCode();
                $type = $Language->phrase($statusCode);
                $description = $description ?: $Language->phrase($statusCode . "Desc");
                $this->error = [
                    "statusCode" => $statusCode,
                    "error" => [
                        "class" => ($exception instanceof HttpInternalServerErrorException) ? "text-danger" : "text-warning",
                        "type" => $type,
                        "description" => $description,
                    ],
                ];
            }
        }
        if (!($exception instanceof HttpException) && ($exception instanceof Exception || $exception instanceof Throwable)) {
            if ($exception instanceof \ErrorException) {
                $severity = $exception->getSeverity();
                $this->error["error"]["class"] = "text-warning";
                if ($severity === E_WARNING) {
                    $this->error["error"]["type"] = $Language->phrase("Warning");
                } elseif ($severity === E_NOTICE) {
                    $this->error["error"]["type"] = $Language->phrase("Notice");
                }
            }
            $description = $exception->getFile() . "(" . $exception->getLine() . "): " . $exception->getMessage();
            $this->error["error"]["description"] = $description;
        }
        if ($this->displayErrorDetails) {
            $this->error["error"]["trace"] = $exception->getTraceAsString();
        }
    }

    // Respond
    protected function respond(): ResponseInterface
    {
        global $Language, $Error, $Title;
        $exception = $this->exception;
        $Language = Container("language");

        // Set error message
        $this->setError($exception);

        // Create response object
        $response = $this->responseFactory->createResponse();

        // Show error as JSON
        $routeName = RouteName();
        if (
            IsApi() || // API request
            $routeName && preg_match('/\-preview(\-2)?$/', $routeName) || // Preview page
            $this->request->getParam("modal") == "1" || // Modal request
            $this->request->getParam("d") == "1" // Drilldown request
        ) {
            return $response->withJson(ConvertToUtf8($this->error), $this->error["statusCode"] ?? null);
        }
        if ($this->contentType == "text/html") { // HTML
            $Title = $Language->phrase("Error");
            if ($this->ShowSourceCode && $this->displayErrorDetails && !IsProduction()) { // Only show code if is debug and not production
                $handler = new \Whoops\Handler\PrettyPageHandler;
                $handler->setPageTitle($Title);
                $whoops = new \Whoops\Run;
                $whoops->allowQuit(false);
                $whoops->writeToOutput(false);
                $whoops->pushHandler($handler);
                $html = $whoops->handleException($exception);
            } else {
                $view = Container("view");
                $Error = $this->error;
                try { // Render with layout
                    $view->setLayout($this->LayoutTemplate);
                    $html = $view->fetch($this->ErrorTemplate, $GLOBALS, true); // Use layout
                } catch (Throwable $e) { // Error with layout
                    $this->setError($e);
                    $Error = $this->error;
                    $html = sprintf(
                        '<html>' .
                        '   <head>' .
                        '       <meta charset="utf-8">' .
                        '       <meta name="viewport" content="width=device-width, initial-scale=1">' .
                        '       <title>%s</title>' .
                        '       <link rel="stylesheet" href="adminlte3/css/' . CssFile("adminlte.css") . '">' .
                        '       <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">' .
                        '       <link rel="stylesheet" href="' . CssFile(Config("PROJECT_STYLESHEET_FILENAME")) . '">' .
                        '   </head>' .
                        '   <body class="container-fluid">' .
                        '       <div>%s</div>' .
                        '   </body>' .
                        '</html>',
                        $Title,
                        $view->fetch($this->ErrorTemplate, $GLOBALS)
                    );
                }
            }
            $response->getBody()->write($html);
            return $response;
        } else { // JSON
            return $response->withJson(ConvertToUtf8($this->error), $this->error["statusCode"] ?? null);
        }
    }
}
