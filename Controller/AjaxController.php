<?php

/*
 * @copyright   2017 Mautic Contributors. All rights reserved
 * @author      TheCodeine
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\TheCodeineEmbedBundle\Controller;

use Mautic\CoreBundle\Controller\AjaxController as CommonAjaxController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AjaxController.
 */
class AjaxController extends CommonAjaxController
{
    /**
     * Saves the new ordering of dashboard widgets.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function updateWidgetOrderingAction(Request $request)
    {
        if (!$this->isGranted('plugin:plugins:manage')) {
            return $this->modalAccessDenied();
        }

        $data    = $request->request->get('ordering');
        $embedId = $request->request->get('embed');
        $repo    = $this->getModel('thecodeine.embed')->getRepository();
        $repo->updateOrdering(array_flip($data), $embedId);
        $dataArray = ['success' => 1];

        return $this->sendJsonResponse($dataArray);
    }

    /**
     * Deletes the widget.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function deleteWidgetAction(Request $request)
    {
        if (!$this->isGranted('plugin:plugins:manage')) {
            return $this->modalAccessDenied();
        }

        $widgetId  = $request->request->get('widget');
        $dataArray = ['success' => 0];

        $model  = $this->getModel('thecodeine.embed');
        $widget = $model->getEntity($widgetId);
        if ($widget) {
            $model->deleteEntity($widget);
            $dataArray['success'] = 1;
        }

        return $this->sendJsonResponse($dataArray);
    }

    /**
     * Deletes the widgets by embed id.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function deleteAction(Request $request)
    {
        if (!$this->isGranted('plugin:plugins:manage')) {
            return $this->modalAccessDenied();
        }

        $embedId   = $request->request->get('embed');
        $dataArray = ['success' => 1];

        $model = $this->getModel('thecodeine.embed');
        $model->deleteWidgetsByEmbedId($embedId);

        return $this->sendJsonResponse($dataArray);
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
