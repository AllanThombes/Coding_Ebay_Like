<?php

namespace SocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class PriceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('actualPrice')
            ->add('immediatePrice')
            ->add('startingPrice')
            ->add('minBid')
            // ->add('endDate', 'datetime')
            ->add('endDate', DateTimeType::class, array('date_widget' => "single_text", 'time_widget' => "single_text", 'required' => false ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SocBundle\Entity\Price'
        ));
    }
}
