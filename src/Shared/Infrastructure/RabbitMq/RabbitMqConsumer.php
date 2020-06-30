<?php

namespace LaSalle\Rendimiento\JudithVilela\Shared\Infrastructure\RabbitMq;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Service\ClaviskaImageProcessing;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

final class RabbitMqConsumer implements ConsumerInterface
{
    /** @var ClaviskaImageProcessing  */
    public $claviskaImage;

    public function __construct(ClaviskaImageProcessing $claviskaImage)
    {
        $this->claviskaImage = $claviskaImage;
    }

    /**
     * @inheritDoc
     */
    public function execute(AMQPMessage $msg)
    {
        $message = json_decode($msg->body, true);

        $this->claviskaImage->processing($message);

        //Llamar metodo Save, Guardar transformaciones  var_dump($message);
    }
}