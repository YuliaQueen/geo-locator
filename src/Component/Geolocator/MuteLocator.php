<?php

namespace Qween\Location\Component\Geolocator;

class MuteLocator implements LocatorInterface
{
    private $next;
    private $handler;

    public function __construct(
        LocatorInterface $next,
        ErrorHandler     $handler
    )
    {
        $this->next    = $next;
        $this->handler = $handler;
    }

    /**
     * @param Ip $ip
     * @return Location|null
     */
    public function locate(Ip $ip): ?Location
    {
        try {
            return $this->next->locate($ip);
        } catch (\Exception $e) {
            $this->handler->handle($e);
            return new Location('unknown', 'unknown', 'unknown', $ip->getValue());
        }
    }
}