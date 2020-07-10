<?php

namespace LaSalle\Rendimiento\JudithVilela\Shared\Infrastructure\RabbitMq;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\ImageRegisterRepository;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\Aggregate\ImageRegister;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject\ImageId;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository\ElasticSearchRepository;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Service\ClaviskaImageProcessing;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

final class RabbitMqConsumer implements ConsumerInterface
{
    /** @var ClaviskaImageProcessing  */
    private $claviskaImage;

    /** @var ImageRegisterRepository */
    private $imageRegisterRepository;

    /** @var ElasticSearchRepository */
    private $elasticRepository;

    public function __construct(ClaviskaImageProcessing $claviskaImage, ImageRegisterRepository $imageRegisterRepository)
    {
        $this->claviskaImage = $claviskaImage;
        $this->imageRegisterRepository = $imageRegisterRepository;
        $this->elasticRepository = new ElasticSearchRepository();
    }

    /**
     * @inheritDoc
     */
    public function execute(AMQPMessage $msg)
    {
        $message = json_decode($msg->body, true);

        $this->claviskaImage->processing($message);

        $imageRegister = $this->setFormat($message);
        $this->imageRegisterRepository->save($imageRegister);

        $this->elasticRepository->save($imageRegister);
    }

    /**
     * @param  array $parameters
     * @return ImageRegister
     */
    private function setFormat(array $parameters) :ImageRegister
    {
        return new ImageRegister(
            ImageId::generate(),
            $parameters['processedImage'].'.'.$parameters['imageExt'],
            $parameters['processedImagePath'],
            $parameters['imageExt'],
            $parameters['textFilter'],
            $parameters['tag'],
            $parameters['description'],
            ''
        );
    }
}