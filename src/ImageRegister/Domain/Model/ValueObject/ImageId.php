<?php
declare(strict_type=1);

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject;


use Ramsey\Uuid\Uuid;

final class ImageId
{
    /** @var string */
    private $id;

    public function __construct($id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return static
     */
    public static function generate()
    {
        return new self();
    }
}