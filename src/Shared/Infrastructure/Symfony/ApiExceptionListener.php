<?php

namespace App\Shared\Infrastructure\Symfony;

use DomainException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApiExceptionListener
{
    public function __construct() {}

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface || $exception instanceof DomainException) {
            $status = $exception->getStatusCode();
        } else {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $message = $exception->getMessage();
        $decodedMessage = json_decode($message, true);

        $responseData = [
            'error' => [
                'status' => $status,
                'message' => is_array($decodedMessage) ? $decodedMessage : $message,
            ],
        ];

        $event->setResponse(new JsonResponse($responseData, $status));
    }
}
