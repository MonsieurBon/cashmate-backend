<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 14.09.17
 * Time: 07:00
 */

namespace AppBundle\Schema\Types;


use AppBundle\Entity\Account;
use AppBundle\Schema\Types;
use Doctrine\Bundle\DoctrineBundle\Registry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class QueryType extends ObjectType
{
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;

        $config = [
            'name' => 'Query',
            'fields' => [
                'hello' => Types::string(),
                'accounts' => [
                    'type' => Types::listOf(Types::account()),
                    'description' => 'Returns all accounts'
                ],
                'account' => [
                    'type' => Types::account(),
                    'description' => 'Returns account filtered by name',
                    'args' => [
                        'name' => Types::nonNull(Types::string())
                    ]
                ]
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

    public function accounts() {
        return $this->doctrine->getRepository(Account::class)->findAll();
    }

    public function account($val, $args) {
        return $this->doctrine->getRepository(Account::class)->findOneByName($args['name']);
    }
}