<?php

namespace MauticPlugin\TheCodeineEmbedBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use MauticPlugin\TheCodeineEmbedBundle\Entity\Widget;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EmbedController extends AbstractFormController
{
    public function indexAction($id)
    {
        $integrationHelper = $this->get('mautic.helper.integration');
        $integration       = $integrationHelper->getIntegrationObject('Embed');

        if (!$integration) {
            return $this->notFound();
        }

        $settings = $integration->getEmbedSettings($id);

        if (!$settings) {
            return $this->notFound();
        }

        $model = $this->getModel('thecodeine.embed');

        return $this->delegateView(
            [
                'viewParameters' => [
                    'embed'   => $settings,
                    'widgets' => $model->getWidgetsByEmbedId($settings['embed']),
                ],
                'contentTemplate' => 'TheCodeineEmbedBundle:Embed:index.html.php',
                'passthroughVars' => [
                   'activeLink'    => sprintf('#%s_%s', 'mautic_plugin_thecodeine_embed_index', $id),
                   'mauticContent' => 'embed',
                   'route'         => $this->generateUrl('mautic_plugin_thecodeine_embed_index', ['id' => $id]),
                ],
            ]
        );
    }

    public function newWidgetAction($id)
    {
        $this->denyAccessUnlessGranted('plugin:plugins:manage');

        $widget = new Widget();
        $widget->setEmbed($id);

        $action = $this->generateUrl('mautic_plugin_thecodeine_embed_widget_new', ['id' => $id]);

        return $this->proccessWidget($widget, $action);
    }

    public function editWidgetAction($widgetId)
    {
        $this->denyAccessUnlessGranted('plugin:plugins:manage');

        $model  = $this->getModel('thecodeine.embed');
        $widget = $model->getEntity($widgetId);

        if (!$widget) {
            return $this->notFound();
        }

        $action = $this->generateUrl('mautic_plugin_thecodeine_embed_widget_edit', ['widgetId' => $widgetId]);

        return $this->proccessWidget($widget, $action);
    }

    public function deleteWidgetAction($widgetId)
    {
        $this->denyAccessUnlessGranted('plugin:plugins:manage');

        $success = 0;
        $flashes = [];

        $model  = $this->getModel('thecodeine.embed');
        $widget = $model->getEntity($widgetId);

        if ($widget === null) {
            $route      = 'mautic_dashboard_index';
            $returnUrl  = $this->generateUrl('mautic_dashboard_index');
            $activeLink = '#'.$route;

            $flashes[] = [
              'type'    => 'error',
              'msg'     => 'mautic.api.client.error.notfound',
              'msgVars' => ['%id%' => $widgetId],
          ];
        } else {
            $route     = 'mautic_plugin_thecodeine_embed_index';
            $returnUrl = $this->generateUrl('mautic_plugin_thecodeine_embed_index', [
            'id' => $widget->getEmbed(),
          ]);
            $activeLink = sprintf('#%s_%s', $route, $widget->getEmbed());

            $model->deleteEntity($widget);
            $name      = $widget->getName();
            $flashes[] = [
              'type'    => 'notice',
              'msg'     => 'mautic.core.notice.deleted',
              'msgVars' => [
                  '%name%' => $name,
                  '%id%'   => $widgetId,
              ],
          ];
        }

        return $this->postActionRedirect([
          'returnUrl'       => $returnUrl,
          'contentTemplate' => 'TheCodeineEmbedBundle:Embed:index',
          'passthroughVars' => [
              'activeLink' => $activeLink,
              'success'    => $success,
          ],
          'flashes' => $flashes,
      ]);
    }

    private function proccessWidget(Widget $widget, $action)
    {
        $model = $this->getModel('thecodeine.embed');

        $form       = $model->createForm($widget, $this->get('form.factory'), $action);
        $closeModal = false;
        $valid      = false;

        if ($this->request->isMethod(Request::METHOD_POST)) {
            if (!$cancelled = $this->isFormCancelled($form)) {
                if ($valid = $this->isFormValid($form)) {
                    $closeModal = true;

                    $model->saveEntity($widget);
                }
            } else {
                $closeModal = true;
            }
        }

        if ($closeModal) {
            $passthroughVars = [
                'closeModal'    => 1,
                'mauticContent' => 'widget',
            ];

            if ($valid && !$cancelled) {
                $passthroughVars['upWidgetCount'] = 1;
                $passthroughVars['widgetHtml']    = $this->renderView('TheCodeineEmbedBundle:Widget:detail.html.php', [
                    'widget' => $widget,
                ]);
                $passthroughVars['widgetId']     = $widget->getId();
                $passthroughVars['widgetWidth']  = $widget->getWidth();
                $passthroughVars['widgetHeight'] = $widget->getHeight();
            }

            $response = new JsonResponse($passthroughVars);

            return $response;
        }

        return $this->delegateView([
            'viewParameters' => [
                'form' => $form->createView(),
            ],
            'contentTemplate' => 'TheCodeineEmbedBundle:Widget:form.html.php',
        ]);
    }

    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied object.
     *
     * @param mixed $attributes The attributes
     * @param mixed $object     The object
     *
     * @return bool
     *
     * @throws \LogicException
     */
    protected function isGranted($attributes, $object = null)
    {
        if (!$this->container->has('mautic.security')) {
            throw new \LogicException('The MauticCoreBundle is not registered in your application.');
        }

        return $this->container->get('mautic.security')->isGranted($attributes, 'MATCH_ALL', $object);
    }
}
