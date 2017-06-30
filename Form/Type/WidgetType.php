<?php

/*
 * @copyright   2017 Mautic Contributors. All rights reserved
 * @author      TheCodeine
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\TheCodeineEmbedBundle\Form\Type;

use Mautic\CoreBundle\Form\Type\FormButtonsType;
use MauticPlugin\TheCodeineEmbedBundle\Entity\Widget;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class WidgetType.
 */
class WidgetType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label'      => 'mautic.integration.thecodeine.embed.form.name',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control'],
        ]);

        $builder->add('data', UrlType::class, [
            'label'      => 'mautic.integration.thecodeine.embed.form.url',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control'],
        ]);

        $builder->add('width', ChoiceType::class, [
           'label'   => 'mautic.integration.thecodeine.embed.form.width',
           'choices' => [
               '25'  => '25%',
               '50'  => '50%',
               '75'  => '75%',
               '100' => '100%',
           ],
           'empty_data' => '100',
           'label_attr' => ['class' => 'control-label'],
           'attr'       => ['class' => 'form-control'],
           'required'   => false,
       ]);

        $builder->add('height', ChoiceType::class, [
           'label'   => 'mautic.integration.thecodeine.embed.form.height',
           'choices' => [
               '215' => '215px',
               '330' => '330px',
               '445' => '445px',
               '560' => '560px',
               '675' => '675px',
           ],
           'empty_data' => '330',
           'label_attr' => ['class' => 'control-label'],
           'attr'       => ['class' => 'form-control'],
           'required'   => false,
       ]);

        $builder->add('id', HiddenType::class, [
            'mapped' => false,
        ]);

        $builder->add('type', HiddenType::class, [
            'data' => Widget::TYPE_IFRAME,
        ]);

        $builder->add('buttons', FormButtonsType::class, [
            'apply_text' => false,
            'save_text'  => 'mautic.core.form.save',
        ]);

        if (!empty($options['action'])) {
            $builder->setAction($options['action']);
        }
    }
}
