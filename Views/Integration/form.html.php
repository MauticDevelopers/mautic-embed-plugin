<?php

/*
 * @copyright   2017 Mautic Contributors. All rights reserved
 * @author      TheCodeine
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

echo $view->render('MauticPluginBundle:Integration:form.html.php', get_defined_vars());

echo $view['assets']->includeScript('plugins/TheCodeineEmbedBundle/Assets/js/integration.js');
