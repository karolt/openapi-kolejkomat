<?php

namespace OpenApi\InfoDeliveryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('service', null, ['label' => 'Usługa'])
            ->add('subject', null, ['label' => 'Przedmiot usługi/zamówienia'])
            ->add('value', null, ['label' => 'Wartość'])
            ->add('customer', null, ['label' => 'Klient'])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OpenApi\InfoDeliveryBundle\Entity\OrderEntry'
        ));
    }

    public function getName()
    {
        return 'openapi_infodeliverybundle_orderentrytype';
    }
}
