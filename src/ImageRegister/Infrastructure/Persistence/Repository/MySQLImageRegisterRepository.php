<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\ImageRegisterRepository;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\Aggregate\ImageRegister;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject\ImageId;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\MysqlDatabase;

class MySQLImageRegisterRepository implements ImageRegisterRepository
{
    /** @var MysqlDatabase */
    private $pdoClient;

    public function __construct()
    {
        $this->pdoClient = MysqlDatabase::instancePDO();
    }

    public function save(ImageRegister $imageRegister): void
    {
        $imageId = $imageRegister->id()->getId();
        $imageName = $imageRegister->imageName();
        $imagePath = $imageRegister->imagePath();
        $imageExt = $imageRegister->imageExt();
        $imageFilter = $imageRegister->imageFilter();
        $tag = $imageRegister->tags();
        $description = $imageRegister->description();

        $sql = 'INSERT INTO ImageRegister VALUES(:imageId, :imageName, :imagePath, :imageExt, :imageFilter, :tag, :description)';
        $statement = $this->pdoClient->prepare($sql);
        $statement->bindValue(':imageId', $imageId);
        $statement->bindValue(':imageName', $imageName);
        $statement->bindValue(':imagePath', $imagePath);
        $statement->bindValue(':imageExt', $imageExt);
        $statement->bindValue(':imageFilter', $imageFilter);
        $statement->bindValue(':tag', $tag);
        $statement->bindValue(':description', $description);
        $statement->execute();
    }

    public function find(ImageId $imageId): ?ImageRegister
    {
        // TODO: Implement find() method.
    }
}