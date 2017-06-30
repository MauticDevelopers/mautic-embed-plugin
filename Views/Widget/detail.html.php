<?php

/*
 * @copyright   2017 Mautic Contributors. All rights reserved
 * @author      TheCodeine
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
?>

<div class="card" style="height: <?php echo $widget->getHeight() ? ($widget->getHeight() - 10).'px' : '300px' ?>">
    <div class="card-header">
        <h4><?php echo $widget->getName(); ?></h4>
        <?php if ($widget->getId() && $view['security']->isGranted('plugin:plugins:manage')) : ?>
        <div class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-v"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a  href="<?php echo $this->container->get('router')->generate('mautic_plugin_thecodeine_embed_widget_edit', ['widgetId' => $widget->getId()]); ?>"
                        data-toggle="ajaxmodal"
                        data-target="#MauticSharedModal"
                        data-header="<?php echo $view['translator']->trans('mautic.dashboard.widget.header.edit'); ?>">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                </li>
                <li role="separator" class="divider"></li>
                <li>
                    <a  href="<?php echo $this->container->get('router')->generate('mautic_plugin_thecodeine_embed_widget_delete', ['widgetId' => $widget->getId()]); ?>"
                        data-header="<?php echo $view['translator']->trans('mautic.dashboard.widget.header.delete'); ?>"
                        class="remove-widget">
                        <i class="fa fa-remove"></i> Remove
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <iframe width="100%" height="100%" src="<?php echo $widget->getData(); ?>" frameborder="0" allowfullscreen></iframe>
    </div>
</div>
