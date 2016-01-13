<?php

namespace Alcaeus\MongoDbAdapter\Tests;

use MongoDB\Client;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        $this->getCheckDatabase()->drop();
    }

    /**
     * @return \MongoDB\Database
     */
    protected function getCheckDatabase()
    {
        $client = new Client('mongodb://localhost', ['connect' => true]);
        return $client->selectDatabase('mongo-php-adapter');
    }

    /**
     * @param array|null $options
     * @return \MongoClient
     */
    protected function getClient($options = null)
    {
        $args = ['mongodb://localhost'];
        if ($options !== null) {
            $args[] = $options;
        }

        $reflection = new \ReflectionClass('MongoClient');

        return $reflection->newInstanceArgs($args);
    }

    /**
     * @param \MongoClient|null $client
     * @return \MongoDB
     */
    protected function getDatabase(\MongoClient $client = null)
    {
        if ($client === null) {
            $client = $this->getClient();
        }

        return $client->selectDB('mongo-php-adapter');
    }

    /**
     * @param string $name
     * @param \MongoDB|null $database
     * @return \MongoCollection
     */
    protected function getCollection($name = 'test', \MongoDB $database = null)
    {
        if ($database === null) {
            $database = $this->getDatabase();
        }

        return $database->selectCollection($name);
    }

    /**
     * @param string $prefix
     * @param \MongoDB|null $database
     * @return \MongoGridFS
     */
    protected function getGridFS($prefix = 'fs', \MongoDB $database = null)
    {
        if ($database === null) {
            $database = $this->getDatabase();
        }

        return $database->getGridFS($prefix);
    }

    /**
     * @return \MongoCollection
     */
    protected function prepareData()
    {
        $collection = $this->getCollection();

        $document = ['foo' => 'bar'];
        $collection->insert($document);

        unset($document['_id']);
        $collection->insert($document);

        $document = ['foo' => 'foo'];
        $collection->insert($document);

        return $collection;
    }
}
