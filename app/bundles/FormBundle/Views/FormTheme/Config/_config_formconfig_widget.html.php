<?php

/*
 * @copyright   2019 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo $view['translator']->trans('mautic.config.tab.formconfig'); ?></h3>
    </div>
    <div class="panel-body">
        <?php foreach ($form->children as $name => $f): ?>
            <?php if ('form_results_data_sources' !== $name) : ?>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $view['form']->row($f); ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo $view['translator']->trans('mautic.config.tab.formresultsconfig'); ?></h3>
    </div>
    <div class="panel-body">
        <?php foreach ($form->children as $name => $f): ?>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['form_results_data_sources']); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>