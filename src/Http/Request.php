<?php

namespace Lune\Http;

use Lune\Server\Server;

/**
 * HTTP request
 */
class Request {
    /**
     * Request HTTP uri
     *
     * @var string
     */
    protected string $uri;

    /**
     * Request HTTP method
     *
     * @var HttpMethod
     */
    protected HttpMethod $method;

    /**
     * Request HTTP data
     *
     * @var array
     */
    protected array $data;

    /**
     * Query parameters
     *
     * @var array
     */
    protected array $query;

    /**
     * Create a new request from the given `$server`    
     *
     * @param Server $server
     */
    public function __construct(Server $server) {
        $this->uri = $server->requestUri();
        $this->method = $server->requestMethod();
        $this->data = $server->postData();
        $this->query = $server->queryParams();
    }

    /**
     * Get the request uri
     *
     * @return string
     */
    public function uri(): string {
        return $this->uri;
    }

    /**
     * Get the request HTTP method
     *
     * @return HttpMethod
     */
    public function method(): HttpMethod {
        return $this->method;
    }
}