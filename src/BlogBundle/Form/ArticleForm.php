<?php

namespace BlogBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('contentTexts', CollectionType::class, array('entry_type' => ArticleTextForm::class,
                'allow_add' => true, 'by_reference' => false, 'label' => false, 'allow_delete' => true, 'prototype' => true))
            ->add('contentFiles', CollectionType::class, array('entry_type' => ArticleFileForm::class, 'error_bubbling'=>true,
                'allow_add' => true, 'by_reference' => false, 'label' => false, 'allow_delete' => true, 'prototype' => true))
            ->add('add', SubmitType::class, array('label' => 'Add', 'attr' => array('class' => 'btn')))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Article'
        ));
    }
}