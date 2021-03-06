<?php

namespace MyApp\Plugins;

use Phalcon\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;
use Phalcon\Mvc\User\Plugin;

/**
 * NotFoundPlugin
 *
 * Handles not-found controller/actions
 */
class NotFoundPlugin extends Plugin
{

    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param MvcDispatcher $dispatcher
     * @param Exception $exception
     * @return boolean
     */
    public function beforeException(Event $event, MvcDispatcher $dispatcher, Exception $exception)
    {
        error_log($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());

        if ($exception instanceof DispatcherException) {
            switch ($exception->getCode()) {
                case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                    $dispatcher->forward(array(
                        'namespace'  => 'MyApp\Controllers',
                        'controller' => 'errors',
                        'action'     => 'show404',
                    ));
                    return false;
            }
        }

        $dispatcher->forward(array(
            'namespace'  => 'MyApp\Controllers',
            'controller' => 'errors',
            'action'     => 'show500',
        ));
        return false;
    }
}
