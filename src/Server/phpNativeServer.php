<?php

namespace Lune\Server;

use Lune\Http\HttpMethod;
use Lune\Http\Request;
use Lune\Http\Response;

/**
 * PHP native server that uses `$_SERVER` global
 */
class phpNativeServer implements Server
{
    /**
     * @inheritDoc
     */
    public function getRequest(): Request {
        return (new Request())
            ->setUri(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH))
            ->setMethod(HttpMethod::from($_SERVER["REQUEST_METHOD"]))
            ->setPostData($_POST)
            ->setQueryParameters($_GET);
    }

    /**
     * @inheritDoc
     */
    public function sendResponse(Response $response)
    {
        //PHP sends Content-Type header by default, but it has to be removed if it has no content.
        //The content-Type header can't be removed if it is not set before.
        header("Content-Type: None");
        header_remove("Content-Type");

        $response->prepare();
        http_response_code($response->status());
        foreach ($response->headers() as $header => $value) {
            header("$header: $value");
        }
        print($response->content());
    }
}
