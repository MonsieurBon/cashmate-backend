<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 14.09.17
 * Time: 06:57
 */

namespace AppBundle\Schema;


use AppBundle\Schema\Types\AccountType;
use AppBundle\Schema\Types\QueryType;
use Doctrine\Bundle\DoctrineBundle\Registry;
use GraphQL\Type\Definition\IDType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\StringType;
use GraphQL\Type\Definition\Type;

class Types
{
    private static $query;
    private static $account;

    /**
     * @return QueryType
     */
    public static function query(Registry $doctrine)
    {
        return self::$query ?: (self::$query = new QueryType($doctrine));
    }

    /**
     * @return AccountType
     */
    public static function account()
    {
        return self::$account ?: (self::$account = new AccountType());
    }

    /**
     * @return IDType
     */
    public static function id()
    {
        return Type::id();
    }

    /**
     * @param $type
     * @return ListOfType
     */
    public static function listOf($type)
    {
        return new ListOfType($type);
    }

    /**
     * @param $type
     * @return NonNull
     */
    public static function nonNull($type)
    {
        return new NonNull($type);
    }

    /**
     * @return StringType
     */
    public static function string()
    {
        return Type::string();
    }
}