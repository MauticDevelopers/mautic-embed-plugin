<?php

namespace MauticPlugin\TheCodeineEmbedBundle\Tests\Controller;

use MauticPlugin\TheCodeineEmbedBundle\Controller\EmbedController;

class EmbedControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EmbedController
     */
    private $controller;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->controller = $this->getMockBuilder(EmbedController::class)
            ->disableOriginalConstructor()
            ->setMethods(['isGranted'])
            ->getMock();

        $this->controller->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValue(false));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testNewWidgetActionAsUserWithoutPermission()
    {
        $this->controller->newWidgetAction('12345');
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testEditWidgetActionAsUserWithoutPermission()
    {
        $this->controller->newWidgetAction('12345');
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function testDeleteWidgetActionAsUserWithoutPermission()
    {
        $this->controller->newWidgetAction('12345');
    }
}
