<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'item.name',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'empty_data' => null,
                'label' => 'item.description',
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'item.is_active',
            ])
            ->add('sku', TextType::class, [
                'label' => 'item.sku',
            ])
            ->add('image', FileType::class, [
                'attr' => [
                    'data-allowed-file-extensions' => '["jpg", "png"]',
                ],
                'required' => false,
                'empty_data' => null,
                'label' => 'item.image',
            ])
            ->add('itemId1', IntegerType::class, [
                'required' => false,
                'label' => 'item.item_id1',
            ])
            ->add('itemId2', IntegerType::class, [
                'required' => false,
                'label' => 'item.item_id2',
            ])
            ->add('itemId3', IntegerType::class, [
                'required' => false,
                'label' => 'item.item_id3',
            ])
            ->add('category');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Item'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_item';
    }


}
