<?php

namespace PHPMaker2023\gestion_ECOLE;

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;

/**
 * JWT middleware
 */
class JwtMiddleware implements MiddlewareInterface
{
    // Create JWT token
    public function create(Request $request, RequestHandler $handler): Response
    {
        global $Security, $ResponseFactory;

        // Get response
        $response = $handler->handle($request);

        // Authorize
        $Security = Container("security");
        if ($Security->isLoggedIn()) {
            if ($request->isGet()) {
                $expire = $request->getQueryParam(Config("API_LOGIN_EXPIRE"));
                $permission = $request->getQueryParam(Config("API_LOGIN_PERMISSION"));
            } else {
                $expire = $request->getParsedBodyParam(Config("API_LOGIN_EXPIRE"));
                $permission = $request->getParsedBodyParam(Config("API_LOGIN_PERMISSION"));
            }
            $expire = ParseInteger($expire); // Get expire time in hours
            $permission = ParseInteger($permission); // Get allowed permission
            $minExpiry = $expire ? time() + $expire * 60 * 60 : 0;
            $jwt = $Security->createJwt($minExpiry, $permission);
            $response = $ResponseFactory->createResponse();
            return $response->withJson($jwt); // Return JWT token
        } elseif (StartsString("application/json", $response->getHeaderLine("Content-type") ?? "")) { // JSON error response
            return $response;
        } else {
            return $response->withStatus(401); // Not authorized
        }
    }

    // Validate JWT token
    public function process(Request $request, RequestHandler $handler): Response
    {
        // Login user against default expiry time
        return $this->loginUser($request, $handler);
    }

    // Login user
    private function loginUser(Request $request, RequestHandler $handler): Response
    {
        global $UserProfile, $Security, $ResponseFactory;

        // Set up security from HTTP header or cookie
        $UserProfile = Container("profile");
        $Security = Container("security");
        $bearerToken = preg_replace('/^Bearer\s+/', "", $request->getHeaderLine(Config("JWT.AUTH_HEADER"))); // Get bearer token from HTTP header
        $token = $bearerToken ?: Get(Config("API_JWT_TOKEN_NAME")); // Try query parameter if no bearer token
        $token = $token ?: ReadCookie("JWT"); // Try cookie if no token
        if ($token) {
            $jwt = DecodeJwt($token);
            if (is_array($jwt) && count($jwt) > 0) {
                if (array_key_exists("username", $jwt)) { // User name exists
                    $Security->loginUser($jwt["username"], @$jwt["userid"], @$jwt["parentuserid"], @$jwt["userlevelid"], @$jwt["permission"]); // Login user
                } else { // JWT error
                    $response = $ResponseFactory->createResponse();
                    $json = array_merge($jwt, ["success" => false, "version" => PRODUCT_VERSION]);
                    return $response->withJson($json);
                }
            }
        }

        // Process request
        return $handler->handle($request);
    }
}
