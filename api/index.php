<?php

namespace PHPMaker2023\gestion_ECOLE;

use PHPMaker2023\gestion_ECOLE\{UserProfile, Language, AdvancedSecurity, Timer, HttpErrorHandler};
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use DI\Container as Container;
use DI\ContainerBuilder;
use Selective\SameSiteCookie\SameSiteCookieConfiguration;
use Selective\SameSiteCookie\SameSiteCookieMiddleware;
use Selective\SameSiteCookie\SameSiteSessionMiddleware;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Exception\HttpInternalServerErrorException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Middlewares\Whoops;

// Relative path
$RELATIVE_PATH = "../";

// Require files
require_once "../vendor/autoload.php";
require_once "../src/constants.php";
require_once "../src/config.php";
require_once "../src/phpfn.php";
require_once "../src/userfn.php";

// Check PHP extensions
$exts = array_filter(Config("PHP_EXTENSIONS"), fn($ext) => !extension_loaded($ext), ARRAY_FILTER_USE_KEY);
if (count($exts)) {
    $exts = array_map(fn($ext) => '<p><a href="https://www.php.net/manual/en/book.' . $exts[$ext] . '.php" target="_blank">' . $ext . '</a></p>', array_keys($exts));
    die("<p>Missing PHP extension(s)! Please install or enable the following required PHP extension(s) first:</p>" . implode("", $exts));
} elseif (!defined("LIBXML_HTML_NODEFDTD")) {
    die('<p>Missing PHP <a href="https://www.php.net/manual/en/book.libxml.php" target="_blank">Libxml</a> extension (>= 2.7.8). Please install or enable it first.</p>');
}

// Environment
$isProduction = IsProduction();
$isDebug = IsDebug();

// Set warnings and notices as errors
if ($isDebug && Config("REPORT_ALL_ERRORS")) {
    error_reporting(E_ALL);
    set_error_handler(function ($severity, $message, $file, $line) {
        if (error_reporting() & $severity) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        }
    });
}

// Instantiate PHP-DI container builder
$containerBuilder = new ContainerBuilder();

// Enable container compilation
if ($isProduction && Config("COMPILE_CONTAINER") && !IsRemote(Config("UPLOAD_DEST_PATH"))) {
    $cacheFolder = UploadPath(false) . "cache";
    if (CreateFolder($cacheFolder)) {
        $containerBuilder->enableCompilation($cacheFolder);
    }
}

// Add definitions
$containerBuilder->addDefinitions("../src/definitions.php");

// Call Container Build event
if (function_exists(PROJECT_NAMESPACE . "Container_Build")) {
    Container_Build($containerBuilder);
}

// Build PHP-DI container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

// Display error details
$displayErrorDetails = $isDebug;
$logErrorToFile = Config("LOG_ERROR_TO_FILE");
$logErrors = $logErrorToFile || $isDebug;
$logErrorDetails = $logErrorToFile || $isDebug || Config("LOG_ERROR_DETAILS");

// Create request object
$serverRequestCreator = ServerRequestCreatorFactory::create();
$Request = $serverRequestCreator->createServerRequestFromGlobals();

// Create error handler
$ResponseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $ResponseFactory);
$errorHandler->LayoutTemplate = "layout.php";
$errorHandler->ErrorTemplate = "Error.php";

// Set base path
$app->setBasePath(BasePath());

// Add body parsing middleware
$app->addBodyParsingMiddleware();

// Add CORS middleware
$app->add(new CorsMiddleware([
    "Access-Control-Allow-Origin" => "*",
    "Access-Control-Allow-Headers" => "X-Requested-With, Origin, X-Authorization"
]));

// Add routing middleware (after CORS middleware so routing is performed first)
$app->addRoutingMiddleware();

// Is API
$IsApi = true;

// Register routes (Add permission middleware)
(require_once "../src/apiroutes.php")($app);

// Add SameSite cookie/session middleware
if (UseSession($Request)) {
    $cookieConfiguration = new SameSiteCookieConfiguration();
    $cookieConfiguration->sameSite = Config("COOKIE_SAMESITE");
    $cookieConfiguration->httpOnly = Config("COOKIE_HTTP_ONLY");
    $cookieConfiguration->secure = Config("COOKIE_SECURE");
    $app->add(new SameSiteCookieMiddleware($cookieConfiguration));
    $app->add(new SameSiteSessionMiddleware());
}

// Add error handling middlewares
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, $logErrors, $logErrorDetails);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run app
$app->run();
