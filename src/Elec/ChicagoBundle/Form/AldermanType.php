<?php

namespace Elec\ChicagoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AldermanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('party')
            ->add('firstElected')
            ->add('ward')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Elec\ChicagoBundle\Entity\Alderman'
        ));
    }

    public function getName()
    {
        return 'elec_chicagobundle_alderman';
    }
}
