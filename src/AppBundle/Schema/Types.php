<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 14.09.17
 * Time: 06:57
 */

namespace AppBundle\Schema;


use AppBundle\Schema\Types\QueryType;
use GraphQL\Type\Definition\StringType;
use GraphQL\Type\Definition\Type;

class Types
{
    private static $query;

    /**
     * @return QueryType
     */
    public static function query() {
        return self::$query ?: (self::$query = new QueryType());
    }

    /**
     * @return StringType
     */
    public static function string() {
        return Type::string();
    }
}