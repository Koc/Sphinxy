<?php

namespace Brouzie\Sphinxy\Connection;

use Brouzie\Sphinxy\Exception\ConnectionException;

interface Driver
{
    /**
     * @param string $query
     *
     * @return mixed
     *
     * @throws ConnectionException
     */
    public function query($query);

    /**
     * @param $query
     * @param array $resultSetNames
     *
     * @return mixed
     *
     * @throws ConnectionException
     */
    public function multiQuery($query, array $resultSetNames = array());

    /**
     * @param $query
     *
     * @return mixed
     *
     * @throws ConnectionException
     */
    public function exec($query);

    /**
     * @param $value
     *
     * @return mixed
     *
     * @throws ConnectionException
     */
    public function quote($value);

    public function checkConnection();
}
