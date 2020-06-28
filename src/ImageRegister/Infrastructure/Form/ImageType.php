<?php
declare(strict_type=1);

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class)
            ->add('tags', TextType::class)
            ->add('description', TextType::class)
            ->add('submit', SubmitType::class);
    }
}