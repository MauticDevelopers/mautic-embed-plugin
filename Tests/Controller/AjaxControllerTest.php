<?php

namespace MauticPlugin\TheCodeineEmbedBundle\Tests\Controller;

use MauticPlugin\TheCodeineEmbedBundle\Controller\AjaxController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\IdentityTranslator;

class AjaxControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AjaxController
     */
    private $controller;

    /**
     * @var IdentityTranslator
     */
    private $translator;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->controller = $this->getMockBuilder(AjaxController::class)
            ->disableOriginalConstructor()
            ->setMethods(['isGranted'])
            ->getMock();

        $this->controller->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValue(false));

        /* @var IdentityTranslator translator */
        $this->translator = $this->getMockBuilder(IdentityTranslator::class)
            ->disableOriginalConstructor()
            ->setMethods(['trans'])
            ->getMock();

        $this->translator->expects($this->any())
            ->method('trans')
            ->will($this->returnValue('Access Denied'));

        $this->controller->setTranslator($this->translator);
    }

    public function testUpdateWidgetOrderingActionAsUserWithoutPermission()
    {
        $request = new Request();

        $response = $this->controller->executeAjaxAction('updateWidgetOrdering', $request);

        $this->assertJsonResponse(['error' => 'Access Denied'], $response);
    }

    public function testDeleteWidgetActionAsUserWithoutPermission()
    {
        $request = new Request();

        $response = $this->controller->executeAjaxAction('deleteWidget', $request);

        $this->assertJsonResponse(['error' => 'Access Denied'], $response);
    }

    public function testDeleteActionAsUserWithoutPermission()
    {
        $request = new Request();

        $response = $this->controller->executeAjaxAction('delete', $request);

        $this->assertJsonResponse(['error' => 'Access Denied'], $response);
    }

    /**
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    private function assertJsonResponse($expected, $actual, $message = '')
    {
        $this->assertEquals(new JsonResponse($expected), $actual, $message);
    }
}
