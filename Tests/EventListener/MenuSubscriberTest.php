<?php

namespace MauticPlugin\TheCodeineEmbedBundle\Tests\EventListener;

use Mautic\CoreBundle\CoreEvents;
use Mautic\CoreBundle\Event\MenuEvent;
use Mautic\CoreBundle\Menu\MenuHelper;
use Mautic\PluginBundle\Entity\Integration;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\TheCodeineEmbedBundle\EventListener\MenuSubscriber;
use MauticPlugin\TheCodeineEmbedBundle\Integration\EmbedIntegration;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MenuSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var MenuHelper
     */
    private $menuHelper;

    /**
     * @var EmbedIntegration
     */
    private $embedIntegration;

    /**
     * @var Integration
     */
    private $integration;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->eventDispatcher = $this->getMockBuilder(EventDispatcher::class)
            ->setMethods(null)
            ->getMock();

        $this->integration = new Integration();

        $this->embedIntegration = $this->getMockBuilder(EmbedIntegration::class)
            ->disableOriginalConstructor()
            ->setMethods(['getIntegrationSettings'])
            ->getMock();

        $this->embedIntegration->expects($this->any())
            ->method('getIntegrationSettings')
            ->will($this->returnValue($this->integration));

        /** @var IntegrationHelper $integrationHelper */
        $integrationHelper = $this->getMockBuilder(IntegrationHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(['getIntegrationObject'])
            ->getMock();

        $integrationHelper->expects($this->any())
            ->method('getIntegrationObject')
            ->will($this->returnValue($this->embedIntegration));

        $subscriber = new MenuSubscriber($integrationHelper);
        $this->eventDispatcher->addSubscriber($subscriber);

        /* @var MenuHelper $menuHelper */
        $this->menuHelper = $this->getMockBuilder(MenuHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testOnBuildMenuWhenMenuTypeIsNotSupported()
    {
        $menuEvent = new MenuEvent($this->menuHelper, 'other');

        $this->eventDispatcher->dispatch(CoreEvents::BUILD_MENU, $menuEvent);

        $this->assertEquals(['children' => []], $menuEvent->getMenuItems());
    }

    public function testOnBuildMenuWhenPluginIsDisabled()
    {
        $menuEvent = new MenuEvent($this->menuHelper, 'main');

        $this->integration->setIsPublished(false);

        $this->eventDispatcher->dispatch(CoreEvents::BUILD_MENU, $menuEvent);

        $this->assertEquals(['children' => []], $menuEvent->getMenuItems());
    }

    public function testOnBuildMenuWhenPluginDoesNotHaveEmbeds()
    {
        $menuEvent = new MenuEvent($this->menuHelper, 'main');

        $this->integration->setIsPublished(true);
        $this->integration->setFeatureSettings(['embeds' => []]);

        $this->eventDispatcher->dispatch(CoreEvents::BUILD_MENU, $menuEvent);

        $this->assertEquals(['children' => []], $menuEvent->getMenuItems());
    }

    public function testOnBuildMenu()
    {
        $menuEvent = new MenuEvent($this->menuHelper, 'main');

        $this->integration->setIsPublished(true);
        $this->integration->setFeatureSettings(['embeds' => [
            [
                'name'  => 'foo',
                'embed' => '123',
            ],
            [
                'name'  => 'bar',
                'embed' => '456',
            ],
        ]]);

        $this->eventDispatcher->dispatch(CoreEvents::BUILD_MENU, $menuEvent);

        $route = 'mautic_plugin_thecodeine_embed_index';
        $this->assertEquals(['children' => [
            'foo' => [
                'route'           => $route,
                'id'              => sprintf('%s_%s', $route, '123'),
                'iconClass'       => 'fa-folder',
                'routeParameters' => [
                    'id' => '123',
                ],
            ],
            'bar' => [
                'route'           => $route,
                'id'              => sprintf('%s_%s', $route, '456'),
                'iconClass'       => 'fa-folder',
                'routeParameters' => [
                    'id' => '456',
                ],
            ],
        ]], $menuEvent->getMenuItems());
    }
}
