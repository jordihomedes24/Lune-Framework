<?php

namespace Lune\Http;

use function PHPUnit\Framework\isNull;

/**
 * HTTP response that will be sent to the client.
 */
class Response
{
    /**
     * Response HTTP status code
     *
     * @var integer
     */
    protected int $status = 200;

    /**
     * Response HTTP headers
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * Response HTTP content
     *
     * @var string|null
     */
    protected ?string $content = null;

    /**
     * Get response HTTP status code
     *
     * @return integer
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * Sets the status of the response to `$status`
     *
     * @param integer $status
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get response HTTP headers
     *
     * @return array<string,string>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Sets the header `$header` into the value `$value` of the response
     *
     * @param string $header
     * @param string $value
     * @return self
     */
    public function setHeader(string $header, string $value): self
    {
        $this->headers[strtolower($header)] = $value;
        return $this;
    }

    /**
     * removes the header `$header` from the response
     *
     * @param string $header
     * @return self
     */
    public function removeHeader(string $header): self
    {
        unset($this->headers[strtolower($header)]);
        return $this;
    }

    /**
     * Get response HTTP content
     *
     * @return string|null
     */
    public function content(): ?string
    {
        return $this->content;
    }

    /**
     * Sets the content of the response to `$content`
     *
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Sets the `setContentType` header to `$value` of the response
     *
     * @param string $value
     * @return self
     */
    public function setContentType(string $value): self
    {
        $this->setHeader("Content-Type", $value);
        return $this;
    }

    /**
     * Prepares the `Content-Type` and `Content-Length` headers properly
     *
     * @return void
     */
    public function prepare()
    {
        if (is_null($this->content)) {
            $this->removeHeader("Content-Type");
            $this->removeHeader("Content-Length");
        } else {
            $this->setHeader("Content-Length", strlen($this->content));
        }
    }

    /**
     * Prepares the response to send a json
     *
     * @param array $data
     * @return self
     */
    public static function json(array $data): self
    {
        return (new self())
            ->setContentType("application/json")
            ->setContent(json_encode($data));
    }

    /**
     * Prepares the response to send a plain text
     *
     * @param string $text
     * @return self
     */
    public static function text(string $text): self
    {
        return (new self())
            ->setContentType("text/plain")
            ->setContent($text);
    }

    /**
     * Prepares the response to redirect to `$uri`
     *
     * @param string $uri
     * @return self
     */
    public static function redirect(string $uri): self
    {
        return (new self())
            ->setStatus(302)
            ->setHeader("Location", $uri);
    }
}
