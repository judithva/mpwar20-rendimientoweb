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
        $filters = ['prd', 'bw', 'ctr', 'frm','flp'];

        foreach ($filters as $filter) {
            $newMessage = $this->setFormat($message, $filter);
            $this->producerInterface->publish($newMessage);
        }
    }

    /**
     * @param  string $parameters
     * @param  string $filter
     *
     * @return array
     */
    private function setFormat(string $parameters, string $filter) :string
    {
        $messageFilter = json_decode($parameters);
        $messageFilter->filter = $filter;
        $messageFilter->textFilter = $this->getFilter($filter);
        $messageFilter->tag = $messageFilter->tag.' '.$this->getFilter($filter);
        $messageFilter->processedImage = $messageFilter->imageName.'_'.$filter;
        $messageFilter->processedImagePath =  $messageFilter->processedImagePath.$messageFilter->processedImage.'.'.$messageFilter->imageExt;
        return json_encode($messageFilter, JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param string $filter
     * @return string
     */
    private function getFilter(string $filter): string
    {
        $textFilter = null;

        switch ($filter)
        {
            case 'prd':
                $textFilter = 'sepia';
                break;
            case 'bw':
                $textFilter = 'blanco y negro';
                break;
            case 'ctr':
                $textFilter = 'citrico';
                break;
            case 'frm':
                $textFilter = 'con bordes';
                break;
            case 'flp':
                $textFilter = 'invertida';
                break;
        }
        return $textFilter;
    }
}