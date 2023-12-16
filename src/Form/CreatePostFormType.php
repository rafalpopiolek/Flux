<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Post;
use App\Enum\Post\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreatePostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    Type::PUBLIC->value => Type::PUBLIC,
                    Type::PRIVATE->value => Type::PRIVATE,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
