<?php

namespace Brouzie\Sphinxy;

use Brouzie\Sphinxy\Connection\Driver;
use Brouzie\Sphinxy\Logging\LoggerInterface;
use Brouzie\Sphinxy\Query\MultiResultSet;
use Brouzie\Sphinxy\Query\ResultSet;

class Connection
{
    /**
     * @var Driver
     */
    private $driver;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param LoggerInterface|null $logger
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface|null
     */
    public function getLogger()
    {
        return $this->logger;
    }

    public function executeUpdate($query, array $params = array())
    {
        if (null !== $this->logger) {
            $this->logger->startQuery($query);
        }

        $result = $this->driver->exec($this->prepareQuery($query, $params));

        if (null !== $this->logger) {
            $this->logger->stopQuery();
        }

        return $result;
    }

    public function executeQuery($query, array $params = array())
    {
        if (null !== $this->logger) {
            $this->logger->startQuery($query, $params);
        }

        $result = $this->driver->query($this->prepareQuery($query, $params));

        if (null !== $this->logger) {
            $this->logger->stopQuery();
        }
        $meta = $this->driver->query('SHOW META');

        return new ResultSet($result, $meta);
    }

    public function executeMultiQuery($query, array $params = array(), array $types = array(), array $resultSetNames = array())
    {
        if (null !== $this->logger) {
            $this->logger->startQuery($query, $params);
        }

        $results = $this->driver->multiQuery($this->prepareQuery($query, $params), $resultSetNames);

        if (null !== $this->logger) {
            $this->logger->stopQuery();
        }
        $meta = $this->driver->query('SHOW META');

        return new MultiResultSet($results, $meta);
    }

    public function quote($value)
    {
        return $this->driver->quote($value);
    }

    public function getEscaper()
    {
        if (null === $this->escaper) {
            $this->escaper = new Escaper($this->driver);
        }

        return $this->escaper;
    }

    public function checkConnection()
    {
        $this->driver->checkConnection();
    }

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        return new QueryBuilder($this);
    }

    protected function prepareQuery($query, $params)
    {
        return Util::prepareQuery($query, $params, $this->getEscaper());
    }
}
