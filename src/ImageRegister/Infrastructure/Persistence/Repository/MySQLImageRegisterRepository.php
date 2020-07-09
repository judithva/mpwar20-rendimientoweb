<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\ImageRegisterRepository;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\Aggregate\ImageRegister;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject\ImageId;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\MysqlDatabase;
use PDO;

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
        $sql = 'SELECT * FROM ImageRegister where idImage = :idImage';
        $statement = $this->pdoClient->prepare($sql);
        $statement->bindValue(':idImage', $imageId->getId());
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if(!$result) {
            return null;
        }

        $idImage = new ImageId($result['idImage']);
        $imageName = $result['imageName'];
        $imagePath = $result['imagePath'];
        $imageExt = $result['imageExt'];
        $imageFilter = $result['imageFilter'];
        $tags = $result['tags'];
        $description = $result['description'];

        var_dump($idImage);
        var_dump($imageName);
        var_dump($imagePath);
        var_dump($imageExt);
        var_dump($imageFilter);
        var_dump($tags);
        var_dump($description);

        return new ImageRegister($idImage, $imageName, $imagePath, $imageExt, $imageFilter, $tags, $description);
    }

    public function findAll(): array
    {
        $sql = 'SELECT idImage, imageName, imagePath, imageExt, imageFilter, tags, description FROM ImageRegister order by imageName DESC';
        $statement = $this->pdoClient->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            return $results;
        }

        return [];
    }
}