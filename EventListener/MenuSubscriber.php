<?php

/*
 * @copyright   2017 Mautic Contributors. All rights reserved
 * @author      TheCodeine
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\TheCodeineEmbedBundle\EventListener;

use Mautic\CoreBundle\CoreEvents;
use Mautic\CoreBundle\Event\MenuEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\PluginBundle\Helper\IntegrationHelper;

/**
 * Class MenuSubscriber.
 */
class MenuSubscriber extends CommonSubscriber
{
    /**
     * @var IntegrationHelper
     */
    private $integrationHelper;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CoreEvents::BUILD_MENU => 'onBuildMenu',
        ];
    }

    /**
     * MenuSubscriber constructor.
     *
     * @param IntegrationHelper $integrationHelper
     */
    public function __construct(IntegrationHelper $integrationHelper)
    {
        $this->integrationHelper = $integrationHelper;
    }

    /**
     * @param MenuEvent $event
     */
    public function onBuildMenu(MenuEvent $event)
    {
        if ($event->getType() != 'main') {
            return;
        }

        $integration = $this->integrationHelper->getIntegrationObject('Embed');

        if (!$integration) {
            return;
        }

        $settings = $integration->getIntegrationSettings();

        if (!$settings->isPublished()) {
            return;
        }

        $featureSettings = $settings->getFeatureSettings();

        if (!isset($featureSettings['embeds']) || empty($featureSettings['embeds'])) {
            return;
        }

        $event->addMenuItems([
            'priority' => 1,
            'items'    => $this->getMenuItems($featureSettings['embeds']),
        ]);
    }

    /**
     * @param array $embeds
     *
     * @return array
     */
    private function getMenuItems(array $embeds)
    {
        $items = [];

        foreach ($embeds as $key => $item) {
            $name         = $item['name'];
            $route        = 'mautic_plugin_thecodeine_embed_index';
            $items[$name] = [
                'route'           => $route,
                'id'              => sprintf('%s_%s', $route, $item['embed']),
                'iconClass'       => 'fa-folder',
                'routeParameters' => [
                    'id' => $item['embed'],
                ],
            ];
        }

        return $items;
    }
}
