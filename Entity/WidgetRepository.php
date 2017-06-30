<?php

/*
 * @copyright   2017 Mautic Contributors. All rights reserved
 * @author      TheCodeine
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\TheCodeineEmbedBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * WidgetRepository.
 */
class WidgetRepository extends CommonRepository
{
    /**
     * Update widget ordering.
     *
     * @param array  $ordering
     * @param string $embedId
     *
     * @return string
     */
    public function updateOrdering($ordering, $embedId)
    {
        $widgets = $this->getEntities(
            [
                'filter' => [
                    'embed' => $embedId,
                ],
            ]
        );

        foreach ($widgets as &$widget) {
            if (isset($ordering[$widget->getId()])) {
                $widget->setOrdering((int) $ordering[$widget->getId()]);
            }
        }

        $this->saveEntities($widgets);
    }

    /**
     * Delete widgets by embed id.
     *
     * @param string $embedId
     *
     * @return mixed
     */
    public function deleteWidgetsByEmbedId($embedId)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->delete($this->_entityName, $this->getTableAlias())
            ->where($this->getTableAlias().'.embed = :embed')
            ->setParameter('embed', $embedId)
            ->getQuery()
            ->execute()
        ;
    }

    /**
     * Get widgets by embed id.
     *
     * @param $embedId
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function getWidgetsByEmbedId($embedId)
    {
        return $this->getEntities([
            'filter' => [
                'where' => [
                    [
                        'column' => $this->getTableAlias().'.embed',
                        'expr'   => 'eq',
                        'value'  => $embedId,
                    ],
                ],
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultOrder()
    {
        return [
            ['w.ordering', 'ASC'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTableAlias()
    {
        return 'w';
    }
}
