<?php

namespace BlogBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parentId', HiddenType::class, array('attr' => array('class' => 'form-control', 'id' => false)))
            ->add('content', TextareaType::class, array('label' => false,
                    'attr' => array('class' => 'for1m-control', 'id' => false)))
            ->add('add', SubmitType::class, array('label' => 'Add', 'attr' => array('class' => 'btn')))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Comments',
            'csrf_protection' => false
        ));
    }
}