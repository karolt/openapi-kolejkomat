<?php

namespace OpenApi\InfoDeliveryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nazwa'])
            ->add('owner', null, ['label' => 'Użytkownik', 'disabled' => 'disabled'])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OpenApi\InfoDeliveryBundle\Entity\Service'
        ));
    }

    public function getName()
    {
        return 'openapi_infodeliverybundle_servicetype';
    }
}
