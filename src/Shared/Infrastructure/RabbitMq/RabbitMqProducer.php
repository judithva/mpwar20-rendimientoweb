<?php

namespace LaSalle\Rendimiento\JudithVilela\Shared\Infrastructure\Bus\Event\RabbitMq;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Service\ImageProcess;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

final class RabbitMqProducer implements ImageProcess
{
    /** @var ProducerInterface */
    private $producerInterface;

    public function __construct(ProducerInterface $producerInterface)
    {
        $this->producerInterface = $producerInterface;
    }

    /**
     * @inheritDoc
     */
    public function send(string $message)
    {
        $filters = ['sepia', 'bw', 'drk', 'frm','flp'];

        foreach ($filters as $filter) {
            $messageFilter = json_decode($message);
            $messageFilter->filter = $filter;
            $messageFilter->tag = $messageFilter->tag.' '.$filter;
            $messageFilter->processedImage = $messageFilter->imageName.'_'.$filter;
            $messageFilter->processedImagePath =  $messageFilter->processedImagePath.$messageFilter->processedImage.'.'.$messageFilter->imageExt;
            $newMessage = json_encode($messageFilter, JSON_UNESCAPED_SLASHES);

            $this->producerInterface->publish($newMessage);
        }
    }
}