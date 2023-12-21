<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Profile;
use App\Enum\Identity\Gender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('age')
            ->add('twitterUrl')
            ->add('website_url')
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Do not specify' => null,
                    ucfirst(Gender::MALE->value) => Gender::MALE,
                    ucfirst(Gender::FEMALE->value) => Gender::FEMALE,
                    ucfirst(Gender::OTHER->value) => Gender::OTHER,
                ],
            ])
            ->add('bio')
            ->add('profilePicture', FileType::class, [
                'label' => 'Profile picture (JPG or PNG file)',
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
