<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 15.09.17
 * Time: 21:59
 */

namespace AppBundle\Schema\Types;


use AppBundle\Schema\Types;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class AccountType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Account',
            'fields' => function() {
                return [
                    'id' => Types::id(),
                    'name' => Types::string()
                ];
            },
            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                $method = 'resolve' . ucfirst($info->fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($value, $args, $context, $info);
                } else {
                    return $value->{$info->fieldName};
                }
            }
        ];
        parent::__construct($config);
    }

    private function resolveId($value) {
        return $value->getId();
    }

    private function resolveName($value) {
        return $value->getName();
    }
}