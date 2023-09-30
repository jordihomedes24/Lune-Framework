<?php

namespace Lune\Http;

use Lune\Routing\Route;
use Lune\Server\Server;

/**
 * HTTP request
 */
class Request
{
    /**
     * Request HTTP uri
     *
     * @var string
     */
    protected string $uri;

    /**
     * Route matched by uri
     *
     * @var Route
     */
    protected Route $route;

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
     * Get the request uri
     *
     * @return string
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Set request uri
     *
     * @param string $uri
     * @return self
     */
    public function setUri(string $uri): self {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Get route matched by the uri of the request
     *
     * @return Route
     */
    public function route(): Route {
        return $this->route;
    }

    /**
     * Set request route
     *
     * @param Route $route
     * @return self
     */
    public function setRoute(Route $route): self {
        $this->route = $route;
        return $this;
    } 

    /**
     * Get the request HTTP method
     *
     * @return HttpMethod
     */
    public function method(): HttpMethod
    {
        return $this->method;
    }

    /**
     * Sets the request HTTP method
     *
     * @param HttpMethod $method
     * @return self
     */
    public function setMethod(HttpMethod $method): self {
        $this->method = $method;
        return $this;
    }

    /**
     * Get POST data
     *
     * @return array
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * Set Post Data
     *
     * @param array $data
     * @return self
     */
    public function setPostData(array $data): self {
        $this->data = $data;
        return $this;
    }

    /**
     * Get query parameters
     *
     * @return array
     */
    public function query(): array
    {
        return $this->query;
    }

    /**
     * Sets request query parameters
     *
     * @param array $data
     * @return self
     */
    public function setQueryParameters(array $query): self {
        $this->query = $query;
        return $this;
    }

    /**
     * Get all route parameters
     *
     * @return array
     */
    public function routeParameters(): array {
        return $this->route->parseParameters($this->uri);
    }
}
