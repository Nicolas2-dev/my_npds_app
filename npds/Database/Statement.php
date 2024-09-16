<?php


namespace Npds\Database;

use Npds\Database\Connection;

use \PDO;
use \PDOStatement;

/**
 * PDOStatement decorator that logs when a PDOStatement is executed.
 */
class Statement
{
    /**
     * The Connection link.
     */
    private $connection;

    /**
     * The PDOStatement we decorate.
     */
    private $statement;

    /**
     * The Query bind parameters.
     */
    private $parameters = array();


    public function __construct(PDOStatement $statement, Connection $connection, array $parameters = array())
    {
        $this->statement  = $statement;
        $this->connection = $connection;
        $this->parameters = $parameters;
    }

    /**
    * When execute is called record the time it takes and
    * then log the query
    * @return PDO result set
    */
    public function execute($params = null)
    {
        $start = microtime(true);

        if(empty($params)) {
            $result = $this->statement->execute();
        } else {
            $this->parameters = $params;

            $result = $this->statement->execute($params);
        }

        $this->logQuery($this->statement->queryString, $start, $this->parameters);

        return $result;
    }

    public function logQuery($sql, $start = 0, array $params = array())
    {
        $this->connection->logQuery($this->statement->queryString, $start, $this->parameters);
    }

    public function bindValue($parameter, $value, $paramType = PDO::PARAM_STR)
    {
        $key = (strpos($parameter, ':') !== 0) ? $parameter : substr($parameter, 1);

        $this->parameters[$key] = ($paramType == PDO::PARAM_INT) ? intval($value) : $value;

        return $this->statement->bindValue($parameter, $value, $paramType);
    }

    /**
    * Other than execute pass all other calls to the PDOStatement object
    * @param string $function_name
    * @param array $parameters arguments
    */
    public function __call($method, $params)
    {
        return call_user_func_array(array($this->statement, $method), $params);
    }

    public function __get($name)
    {
        return $this->statement->$name;
    }

}
