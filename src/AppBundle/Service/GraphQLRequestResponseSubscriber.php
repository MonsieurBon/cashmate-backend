<?php
/**
 * Created by PhpStorm.
 * User: fabian
 * Date: 14.09.17
 * Time: 07:09
 */

namespace AppBundle\Service;


use GraphQL\Type\Definition\Config;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class GraphQLRequestResponseSubscriber implements EventSubscriberInterface
{
    private static $phpErrors = [];
    private static $statusCode = 200;

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(
                array('enableDebug', 0)
            ),
            KernelEvents::VIEW => array(
                array('logError', 40)
            ),
            KernelEvents::RESPONSE => array(
                array('setStatusCode', 10)
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
        if ($route === 'app_api_index') {

            $result = (array)$event->getControllerResult();
            if (!empty($result['errors'])) {
                static::$statusCode = 400;
            }

            $debug_api = $event->getRequest()->query->get('debug_api');
            if ($debug_api === '1'
                && !empty(static::$phpErrors)) {
                $result['extensions']['phpErrors'] = array_map(
                    ['GraphQL\Error\FormattedError', 'createFromException'],
                    static::$phpErrors
                );
                static::$statusCode = 500;
            }
        }
    }

    public function setStatusCode(FilterResponseEvent $event) {
        $route = $event->getRequest()->attributes->get('_route');
        if ($route === 'app_api_index') {
            $response = $event->getResponse();
            if ($response->getStatusCode() === 200 && static::$statusCode !== 200) {
                $response->setStatusCode(static::$statusCode);
            }
        }
    }
}