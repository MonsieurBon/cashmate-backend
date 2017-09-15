<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 14.09.17
 * Time: 07:00
 */

namespace AppBundle\Schema\Types;


use AppBundle\Schema\Types;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class QueryType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Query',
            'fields' => [
                'hello' => Types::string()
            ],
            'resolveField' => function($val, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($val, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }

    public function hello() {
        return "Your GraphQL endpoint is ready! Use GraphiQL to browse API.";
    }
}