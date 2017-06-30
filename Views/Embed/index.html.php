<?php

/*
 * @copyright   2017 Mautic Contributors. All rights reserved
 * @author      TheCodeine
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
$view->extend('MauticCoreBundle:Default:content.html.php');
$view['slots']->set('headerTitle', $embed['name']);
$view['slots']->set('mauticContent', 'embed');

echo $view['assets']->includeStylesheet('plugins/TheCodeineEmbedBundle/Assets/css/embed.css');
echo $view['assets']->includeScript('plugins/TheCodeineEmbedBundle/Assets/js/embed.js');

$hasEditAccess = $view['security']->isGranted('plugin:plugins:manage');
if ($hasEditAccess) {
    $buttons = [
        [
            'attr' => [
                'class'       => 'btn btn-default btn-nospin',
                'data-toggle' => 'ajaxmodal',
                'data-target' => '#MauticSharedModal',
                'href'        => $view['router']->path('mautic_plugin_thecodeine_embed_widget_new', ['id' => $embed['embed']]),
                'data-header' => $view['translator']->trans('mautic.dashboard.widget.add'),
            ],
            'iconClass' => 'fa fa-plus',
            'btnText'   => 'mautic.dashboard.widget.add',
        ],
    ];

    $view['slots']->set('actions', $view->render('MauticCoreBundle:Helper:page_actions.html.php', [
        'routeBase'     => 'embed',
        'langVar'       => 'embed',
        'customButtons' => $buttons,
    ]));
}
?>

<div id="dashboard-widgets" class="dashboard-widgets embed-widgets cards" data-embed-id="<?php echo $embed['embed'] ?>">
<?php if (count($widgets)): ?>
        <?php foreach ($widgets as $widget): ?>
            <div class="card-flex<?php echo $hasEditAccess ? ' widget' : '' ?>" data-widget-id="<?php echo $widget->getId(); ?>" style="width: <?php echo $widget->getWidth() ? $widget->getWidth().'' : '100' ?>%; height: <?php echo $widget->getHeight() ? $widget->getHeight().'px' : '300px' ?>">
                <?php echo $view->render('TheCodeineEmbedBundle:Widget:detail.html.php', ['widget' => $widget]); ?>
            </div>
        <?php endforeach; ?>
<?php else: ?>
    <div class="well well col-md-6 col-md-offset-3 mt-md">
        <div class="row">
            <div class="mautibot-image col-xs-3 text-center">
                <img class="img-responsive" style="max-height: 125px; margin-left: auto; margin-right: auto;" src="<?php echo $view['mautibot']->getImage('wave'); ?>" />
            </div>
            <div class="col-xs-9">
                <h4><i class="fa fa-quote-left"></i> <?php echo $view['translator']->trans('mautic.integration.thecodeine.embed.tip.header'); ?> <i class="fa fa-quote-right"></i></h4>
                <p class="mt-md"><?php echo $view['translator']->trans('mautic.integration.thecodeine.embed.nowidgets.tip'); ?></p>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>
<div id="cloned-widgets" class="dashboard-widgets cards"></div>
