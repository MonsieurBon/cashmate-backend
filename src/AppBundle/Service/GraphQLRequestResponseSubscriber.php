<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 14.09.17
 * Time: 07:09
 */

namespace AppBundle\Service;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class GraphQLRequestResponseSubscriber implements EventSubscriberInterface
{
    private static $phpErrors = [];

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(
                array('enableDebug', 0)
            ),
            KernelEvents::VIEW => array(
                array('logError', 40)
            )
        );
    }

    public function enableDebug(GetResponseEvent $event)
    {
        $route = $event->getRequest()->attributes->get('_route');
        $debug_api = $event->getRequest()->query->get('debug_api');
        if ($route === 'app_api_index'
            && $debug_api === '1')
        {
            ini_set('display_errors', 0);

            set_error_handler(function($severity, $message, $file, $line) {
                static::$phpErrors[] = new \ErrorException($message, 0, $severity, $file, $line);
            });
        }
    }

    public function logError(GetResponseForControllerResultEvent $event)
    {
        $route = $event->getRequest()->attributes->get('_route');
        $debug_api = $event->getRequest()->query->get('debug_api');
        if ($route === 'app_api_index'
            && $debug_api === '1'
            && !empty(static::$phpErrors))
        {
            $result = (array) $event->getControllerResult();
            $result['extensions']['phpErrors'] = array_map(
                ['GraphQL\Error\FormattedError', 'createFromException'],
                static::$phpErrors
            );
        }
    }
}