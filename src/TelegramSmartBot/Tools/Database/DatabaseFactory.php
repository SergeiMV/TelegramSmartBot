<?php

namespace TelegramSmartBot\Tools\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require 'vendor/autoload.php';


class DatabaseFactory
{
    static ?EntityManager $entityManager = null;

    static function getEntityManager() : EntityManager
    {

        if (self::$entityManager) {
            return self::$entityManager;
        } else {
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: array(__DIR__."/../../../"),
                isDevMode: true,
            );
            
            $connection = DriverManager::getConnection([
                'driver' => 'pdo_sqlite',
                'path' => __DIR__ . '/db.sqlite',
            ], $config);
            
            self::$entityManager = new EntityManager($connection, $config);

            return self::$entityManager;
        }
    }
}