<?php

namespace App\Form;

use App\Entity\Waypoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WaypointType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add(
                'lat',
                NumberType::class,
                [
                    'required' => true,
                    'scale'    => 6,
                    'attr'     => [
                        'min'  => -90,
                        'max'  => 90,
                        'step' => 0.0000001,
                        'class' => 'latlon',
                    ],
                ]
            )
            ->add(
                'lon',
                NumberType::class,
                [
                    'required' => true,
                    'scale'    => 6,
                    'attr'     => [
                        'min'  => -90,
                        'max'  => 90,
                        'step' => 0.0000001,
                        'class' => 'latlon',
                    ],
                ]
            )
            // ->add('city')
            // ->add('imageLink')
            // ->add('guid')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Waypoint::class,
        ]);
    }
}
