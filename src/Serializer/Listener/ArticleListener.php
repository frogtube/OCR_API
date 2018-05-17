<?php
/**
 * Created by PhpStorm.
 * User: uprad
 * Date: 16/05/2018
 * Time: 16:33
 */

namespace App\Serializer\Listener;


use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class ArticleListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => Events::POST_SERIALIZE,
                'format' => 'json',
                'class' => 'App\Entity\Article',
                'method' => 'onPostSerialize',
            ]
        ];
    }

    public static function onPostSerialize(ObjectEvent $event)
    {
        $object = $event->getObject();

        $date = new \DateTime();
        $visitor = $event->getVisitor();
        $visitor->addData('delivered_at', $date->format('l jS \of F Y h:i:s A'));
    }

}