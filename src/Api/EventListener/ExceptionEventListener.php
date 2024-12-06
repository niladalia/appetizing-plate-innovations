<?php

namespace App\Api\EventListener;

use App\Shared\Utils\ClassUtils;
use App\Shared\Utils\StringUtils;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Throwable;
use InvalidArgumentException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionEventListener
{
    protected function handleInvalidArgumentException(ExceptionEvent $event, InvalidArgumentException $exception): Response|Throwable
    {
        return new UnprocessableEntityHttpException($exception->getMessage(), $exception);
    }

    protected function handleOptimisticLockException(ExceptionEvent $event, OptimisticLockException $exception): Response|Throwable
    {
        return new ConflictHttpException("State mismatch: {$exception->getMessage()}", $exception);
    }

    protected function handleEntityNotFoundException(ExceptionEvent $event, EntityNotFoundException $exception): Response|Throwable
    {
        return new NotFoundHttpException($exception->getMessage(), $exception);
    }

    protected function shouldHandle(Throwable $current, ?Throwable $previous): ?Throwable
    {
        if (!($current instanceof HttpExceptionInterface)) {
            $previous = $current;
        }
        if (!$previous) {
            return null;
        }
        if ($this->getHandlerMethod($previous) !== null) {
            return $previous;
        }
        return null;
    }

    protected function getHandlerMethod(Throwable $exception): ?string
    {
        $classShortName = StringUtils::getSuffix($exception::class, '\\');
        $methodName = "handle{$classShortName}";
        if (method_exists($this, $methodName) && is_callable([$this, $methodName])) {
            return $methodName;
        }
        return null;
    }

    #[AsEventListener]
    public function onExceptionEvent(ExceptionEvent $event): void
    {
        $current = $event->getThrowable();
        $previous = $current->getPrevious();

        $shouldHandle = $this->shouldHandle($current, $previous);
        if (!$shouldHandle) {
            return;
        }

        $methodName = $this->getHandlerMethod($shouldHandle);
        if (!$methodName) {
            return;
        }
        $result = $this->$methodName($event, $shouldHandle);
        if ($result) {
            if ($result instanceof Response) {
                $event->setResponse($result);
                $event->stopPropagation();
            } elseif ($result instanceof Throwable) {
                $event->setThrowable($result);
            }
            
        }
    }
}