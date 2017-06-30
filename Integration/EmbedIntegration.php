<?php

/*
 * @copyright   2017 Mautic Contributors. All rights reserved
 * @author      TheCodeine
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\TheCodeineEmbedBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;
use MauticPlugin\TheCodeineEmbedBundle\Form\Type\EmbedType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EmbedIntegration.
 */
class EmbedIntegration extends AbstractIntegration
{
    public function getName()
    {
        return 'Embed';
    }

    /**
     * Return's authentication method such as oauth2, oauth1a, key, etc.
     *
     * @return string
     */
    public function getAuthenticationType()
    {
        return 'none';
    }

    /**
     * Allows integration to set a custom form template.
     *
     * @return string
     */
    public function getFormTemplate()
    {
        return 'TheCodeineEmbedBundle:Integration:form.html.php';
    }

    /**
     * Allows integration to set a custom theme folder.
     *
     * @return string
     */
    public function getFormTheme()
    {
        return 'TheCodeineEmbedBundle:FormTheme\Integration';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $data
     * @param string               $formArea
     */
    public function appendToForm(&$builder, $data, $formArea)
    {
        if ($formArea != 'features') {
            return;
        }

        $builder->add('embeds', CollectionType::class, [
            'entry_type' => EmbedType::class,
            'label'      => false,
            'options'    => [
                'label' => false,
            ],
            'delete_empty' => true,
            'allow_add'    => true,
            'allow_delete' => true,
        ]);
    }

    public function getEmbedSettings($id)
    {
        $settings = $this->settings->getFeatureSettings();

        if (!isset($settings['embeds'])) {
            return null;
        }

        foreach ($settings['embeds'] as $embed) {
            if ($embed['embed'] === $id) {
                return $embed;
            }
        }

        return null;
    }
}
