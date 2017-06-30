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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class DynamicContentFilterType.
 */
class EmbedType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => false,
                'attr'  => [
                    'class'       => 'form-control',
                    'placeholder' => 'mautic.integration.thecodeine.embed.form.name',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ]
        );

        $builder->add(
            'embed',
            HiddenType::class,
            [
                'empty_data' => function () {
                    return uniqid();
                },
            ]
        );
    }
}
