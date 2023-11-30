<?php

namespace Qween\Location\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcher implements EventDispatcherInterface
{
    private array $listeners = [];

    public function dispatch(object $event): object
    {
        foreach ($this->getListenersForEvents($event) as $listener) {

            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                return $event;
            }

            $listener($event);
        }

        return $event;
    }

    public function addListener(string $eventName, callable $listener): self
    {
        $this->listeners[$eventName][] = $listener;

        return $this;
    }

    public function getListenersForEvents(object $event): iterable
    {
        return $this->listeners[$event::class] ?? [];
    }
}