<?php

namespace Slimmvc\Http;

use InvalidArgumentException;

class Response
{
    const REDIRECT = 'REDIRECT';
    const HTML = 'HTML';
    const JSON = 'JSON';

    private string $type = Response::HTML;
    private ?string $redirect = null;
    private mixed $content = '';
    private int $status = 200;
    private array $headers = [];


    public function status(int $status = null): int|static
    {
        if (is_null($status)) {
            return $this->status;
        }

        $this->status = $status;

        return $this;
    }

    public function redirect(string $redirect = null): mixed
    {
        if (is_null($redirect)) {
            return $this->redirect;
        }

        $this->redirect = $redirect;
        $this->type = static::REDIRECT;
        return $this;
    }

    public function json(mixed $content): static
    {
        $this->content = $content;
        $this->type = static::JSON;
        return $this;
    }

    public function type(string $type = null): string|static
    {
        if (is_null($type)) {
            return $this->type;
        }

        $this->type = $type;

        return $this;
    }

    public function send(): void
    {
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }

        if ($this->type === static::HTML) {
            header('Content-Type: text/html');
            http_response_code($this->status);
            print $this->content;
            return;
        }

        if ($this->type === static::JSON) {
            header('Content-Type: application/json');
            http_response_code($this->status);
            print json_encode($this->content);
            return;
        }


        if ($this->type === static::REDIRECT) {
            header("Location: {$this->redirect}");
            return;
        }

        throw new InvalidArgumentException("{$this->type} is not a recognised type");
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getRedirect(): ?string
    {
        return $this->redirect;
    }

    public function setRedirect(?string $redirect): void
    {
        $this->redirect = $redirect;
    }

    public function getContent(): mixed
    {
        return $this->content;
    }

    public function setContent(mixed $content): void
    {
        $this->content = $content;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function addHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    public function addHeaders(array $headers): void {
        foreach ($headers as $key => $value) {
            $this->headers[$key] = $value;
        }
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }


}