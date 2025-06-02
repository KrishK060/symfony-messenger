<?php

namespace App\MessengerMiddleware;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;

class AuditMiddleware implements MiddlewareInterface
{
    /**
     * @var LoggerInterface
     */
    private $messengerAuditLogger;

    public function __construct(LoggerInterface $messengerAuditLogger)
    {
        $this->messengerAuditLogger = $messengerAuditLogger;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null == $envelope->last(UniqueIdStamps::class)) {
            $envelope = $envelope->with(new UniqueIdStamps());
        }
        /** @var UniqueIdStamps $stamps */
        $stamps = $envelope->last(UniqueIdStamps::class);

        $context = [
            'id' => $stamps->getUniqueId(),
            'class' => get_class($envelope->getMessage()),
        ];

        $envelope = $stack->next()->handle($envelope, $stack);

        if ($envelope->last(ReceivedStamp::class)) {
            $this->messengerAuditLogger->info('[{id}] Received {class}', $context);
        } else if($envelope->last(SentStamp::class)) {
            $this->messengerAuditLogger->info('[{id}] Sent {class}', $context);
        } else {
            $this->messengerAuditLogger->info('[{id}] Handling or sending {class}', $context);
        }
        return $envelope;
    }
}