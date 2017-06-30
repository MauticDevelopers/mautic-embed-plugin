<?php

/*
 * @copyright   2017 Mautic Contributors. All rights reserved
 * @author      TheCodeine
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\TheCodeineEmbedBundle\Model;

use Mautic\CoreBundle\Model\FormModel;
use MauticPlugin\TheCodeineEmbedBundle\Entity\Widget;
use MauticPlugin\TheCodeineEmbedBundle\Form\Type\WidgetType;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class EmbedModel extends FormModel
{
    /**
     * {@inheritdoc}
     *
     * @param Widget                              $entity
     * @param \Symfony\Component\Form\FormFactory $formFactory
     * @param string|null                         $action
     * @param array                               $options
     *
     * @return \Symfony\Component\Form\Form
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     */
    public function createForm($entity, $formFactory, $action = null, $options = [])
    {
        if (!$entity instanceof Widget) {
            throw new MethodNotAllowedHttpException(['Widget'], printf('Entity must be instance of %s', Widget::class));
        }

        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create(WidgetType::class, $entity, $options);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getRepository()
    {
        return $this->em->getRepository('TheCodeineEmbedBundle:Widget');
    }

    public function deleteWidgetsByEmbedId($embedId)
    {
        return $this->getRepository()->deleteWidgetsByEmbedId($embedId);
    }

    public function getWidgetsByEmbedId($embedId)
    {
        return $this->getRepository()->getWidgetsByEmbedId($embedId);
    }
}
