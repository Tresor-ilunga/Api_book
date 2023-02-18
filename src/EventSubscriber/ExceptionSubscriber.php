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
 * @author Tresor-ilunga <19im065@esisalama.org>
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
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

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
