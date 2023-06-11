<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ExceptionSubscriber
 *
 *
 * @author Tresor-ilunga <ilungat82@gmail.com>
 */
class ExceptionSubscriber implements EventSubscriberInterface
{

    /**
     * This method is called when the KernelEvents::EXCEPTION event is dispatched.
     *
     * @param ExceptionEvent $event
     * @return void
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

            if($exception instanceof HttpException)
            {
                $data = [
                    'status' => $exception->getStatusCode(),
                    'message' => $exception->getMessage()
                ];
                $event->setResponse(new JsonResponse($data));
            }
            else
            {
                $data = [
                    'status' => 500, // Le status n'existe pas car ce n'est pas une exception http, donc on met 500
                    'message' => $exception->getMessage()
                ];
                $event->setResponse(new JsonResponse($data));
            }
    }

    /**
     * This method returns the events to which the current class is subscribed.
     *
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
