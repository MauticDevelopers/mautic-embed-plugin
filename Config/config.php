<?php

/*
 * @copyright   2017 Mautic Contributors. All rights reserved
 * @author      TheCodeine
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'name'        => 'Embed',
    'description' => 'Enables embed custom html',
    'version'     => '1.0',
    'author'      => 'The Codeine',

    'routes' => [
        'main' => [
            'mautic_plugin_thecodeine_embed_index' => [
                'path'       => '/embed/{id}',
                'controller' => 'TheCodeineEmbedBundle:Embed:index',
            ],
            'mautic_plugin_thecodeine_embed_widget_new' => [
                'path'       => '/embed/{id}/widgets/new',
                'controller' => 'TheCodeineEmbedBundle:Embed:newWidget',
            ],
            'mautic_plugin_thecodeine_embed_widget_edit' => [
                'path'       => '/embed/widgets/{widgetId}/edit',
                'controller' => 'TheCodeineEmbedBundle:Embed:editWidget',
            ],
            'mautic_plugin_thecodeine_embed_widget_delete' => [
                'path'       => '/embed/widgets/{widgetId}/delete',
                'controller' => 'TheCodeineEmbedBundle:Embed:deleteWidget',
            ],
        ],
    ],

    'services' => [
        'events' => [
            'mautic.form.type.thecodeine.embed.menu.subscriber' => [
                'class'     => 'MauticPlugin\TheCodeineEmbedBundle\EventListener\MenuSubscriber',
                'arguments' => [
                    'mautic.helper.integration',
                ],
            ],
        ],
        'forms' => [
            'mautic.form.type.thecodeine.embed' => [
                'class' => 'MauticPlugin\TheCodeineEmbedBundle\Form\Type\EmbedType',
                'alias' => 'thecodeine_embed',
            ],
            'mautic.form.type.thecodeine.embed.widget' => [
                'class' => 'MauticPlugin\TheCodeineEmbedBundle\Form\Type\WidgetType',
                'alias' => 'thecodeine_embed_widget',
            ],
        ],
        'models' => [
            'mautic.thecodeine.model.embed' => [
                'class' => 'MauticPlugin\TheCodeineEmbedBundle\Model\EmbedModel',
            ],
        ],
    ],
];
