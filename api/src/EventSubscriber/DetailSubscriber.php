<?php

namespace App\EventSubscriber;

use App\Entity\Detail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class DetailSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addPriceToOrder', EventPriorities::PRE_WRITE],
        ];
    }

    public function addPriceToOrder(ViewEvent $event): void
    {
        $detail = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if(!$detail instanceof Detail || Request::METHOD_POST !== $method){
            return;
        }

        $order = $detail->getOrderDelivery();
        $order->setTotal($order->getTotal() + $detail->getPrice());
    }
}