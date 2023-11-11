<?php
use Slimmvc\DependencyInjection\ApplicationContext;


if (!function_exists("ctx")) {
    function ctx(string $alias = null): mixed {
        if  (is_null($alias)) {
            return ApplicationContext::getInstance();
        }

        return ApplicationContext::getInstance()->resolve($alias);
    }
}

if (!function_exists("createResponse")) {
    function response(string $type = null, string $content = null,
                            ?string $redirect = null, ?int $status = null, ?array $headers = null) {
        $response = ctx("response");

        if (!is_null($type)) {
            $response->setType($type);
        }

        if (!is_null($content)) {
            $response->setType($content);
        }

        if (!is_null($redirect)) {
            $response->setType($redirect);
        }

        if (!is_null($status)) {
            $response->setType($status);
        }

        if (!is_null($headers)) {
            $response->setHeaders($headers);
        }

        return $response;
    }
}