<?php

namespace KingFoo\BookmarkerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BookmarkType extends AbstractType
{
    public function getName()
    {
        return 'bookmark';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', 'url')
            ->add('description', 'textarea')
            ->add('tags', 'entity', array(
                'class' => 'KingFooBookmarkerBundle:Tag',
                'multiple' => true,
                'expanded' => true
            ))
            ->add('submit', 'submit');
    }
}
