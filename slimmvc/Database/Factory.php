<?php
namespace Database;
use Closure;
use Exception;
use Database\Connection\Connection;

class Factory {
    private array $connectors;
    public function addConnector(string $alias, Closure $connector): static {
        $this->connectors[$alias] = $connector;
        return $this;
    }

    public function connect(array $config): Connection {
        if (!isset($config["type"])) {
            throw new Exception("type of database connection is not define");
        }

        $type = $config["type"];

        if ($this->connectors[$type]) {
            return $this->connectors[$type]($config);
        }

        throw new Exception("unrecognized type");
    }
}
