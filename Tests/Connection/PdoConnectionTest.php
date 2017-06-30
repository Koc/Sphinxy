<?php

namespace Brouzie\Sphinxy\Tests\Connection;

use Brouzie\Sphinxy\Connection\PdoDriver;

class PdoConnectionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorIsLazy()
    {
        try {
            new PdoDriver('invalid dsn');
        } catch (\Exception $e) {
            $this->fail('Constructor shouldn\'t connect');
        }
    }

    /**
     * @expectedException \Brouzie\Sphinxy\Exception\ConnectionException
     */
    public function testExceptionWhenCouldNotConnect()
    {
        $conn = new PdoDriver('invalid dsn');
        $conn->query('SELECT 1 FROM products');
    }

    public function testConnection()
    {
        $conn = new PdoDriver($_ENV['sphinx_dsn']);
        $result = $conn->query('SHOW TABLES');

        $this->assertContains(array('Index' => 'products', 'Type' => 'rt'), $result);
    }
}
