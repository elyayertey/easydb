<?php
namespace ParagonIE\EasyDB\Tests;

use ParagonIE\EasyDB\Factory;
use PDO;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

/**
 * Class EasyDBTest
 * @package ParagonIE\EasyDB\Tests
 */
abstract class EasyDBTest
    extends
        PHPUnit_Framework_TestCase
{

    /**
    * Data provider for arguments to be passed to Factory::create
    * These arguments will not result in a valid EasyDB instance
    * @return array
    */
    public function BadFactoryCreateArgumentProvider()
    {
        return [
            [
                'this-dsn-will-fail',
                'username',
                'putastrongpasswordhere'
            ],
        ];
    }

    /**
    * Data provider for arguments to be passed to Factory::create
    * These arguments will result in a valid EasyDB instance
    * @return array
    */
    public function GoodFactoryCreateArgumentProvider()
    {
        return [
            [
                'sqlite::memory:',
                null,
                null,
                [],
                'sqlite'
            ],
        ];
    }

    /**
    * EasyDB data provider
    * Returns an array of callables that return instances of EasyDB
    * @return array
    * @see EasyDBTest::GoodFactoryCreateArgumentProvider()
    */
    public function GoodFactoryCreateArgument2EasyDBProvider()
    {
        return array_map(
            function (array $arguments) {
                $dsn = $arguments[0];
                $username = isset($arguments[1]) ? $arguments[1] : null;
                $password = isset($arguments[2]) ? $arguments[2] : null;
                $options = isset($arguments[3]) ? $arguments[3] : [];
                return function() use ($dsn, $username, $password, $options) {
                    return Factory::create(
                        $dsn,
                        $username,
                        $password,
                        $options
                    );
                };
            },
            $this->GoodFactoryCreateArgumentProvider()
        );
    }

    /**
    * EasyDB data provider
    * Returns an array of callables that return instances of EasyDB
    * @return array
    * @see EasyDBTest::GoodFactoryCreateArgumentProvider()
    */
    public function GoodFactoryCreateArgument2EasyDBWithPDOAttributeProvider()
    {
        $ref = new ReflectionClass(PDO::class);
        $attrs = array_filter(
            $ref->getConstants(),
            function ($attrName) {
                return (strpos($attrName, 'ATTR_') === 0);
            },
            ARRAY_FILTER_USE_KEY
        );
        return array_reduce(
            $this->GoodFactoryCreateArgument2EasyDBProvider(),
            function (array $was, callable $cb) use ($attrs) {
                foreach ($attrs as $attr) {
                    $was[] = [
                        $cb,
                        $attr
                    ];
                }
                return $was;
            },
            []
        );
    }
}
