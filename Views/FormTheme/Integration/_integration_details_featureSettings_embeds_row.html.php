<?php
$list          = $form;
$hasErrors     = count($list->vars['errors']);
$feedbackClass = (!empty($hasErrors)) ? ' has-error' : '';
$datePrototype = (isset($list->vars['prototype'])) ?
    $view->escape('<div class="sortable row">
        <div class="pl-xs pr-xs col-sm-11">'.$view['form']->widget($list->vars['prototype']).'</div>
        <div class="pl-xs pr-xs col-sm-1">
            <button type="button" class="btn btn-default remove-field"
                onclick="Mautic.removeEmbedPluginField(this);">
                <span class="fa fa-close"></span>
            </button>
        </div>
    </div>') : '';
$feedbackClass = (!empty($hasErrors)) ? ' has-error' : '';

$names = [0];
?>

<div class="row">
    <div data-toggle="sortablelist" data-prefix="<?php echo $form->vars['id']; ?>" class="form-group col-xs-12 <?php echo $feedbackClass; ?>" id="<?php echo $form->vars['id']; ?>_list">
        <?php echo $view['form']->label($form, $label) ?>
        <div class="list-sortable">
            <?php foreach ($list->children as $item): ?>
              <?php $names[] = $item->vars['name']; ?>
              <div class="sortable row">
                  <div class="pl-xs pr-xs col-sm-11">
                    <?php echo $view['form']->widget($item); ?>
                    <?php echo $view['form']->errors($item); ?>
                  </div>
                  <div class="pl-xs pr-xs col-sm-1">
                      <button type="button" class="btn btn-default remove-field"
                              onclick="Mautic.removeEmbedPluginField(this, '<?php echo $item->vars['value']['embed'] ?>');">
                          <span class="fa fa-close"></span>
                      </button>
                  </div>
              </div>
            <?php endforeach; ?>
        </div>
        <?php echo $view['form']->errors($list); ?>
        <input type="hidden" class="sortable-itemcount" id="<?php echo $form->vars['id']; ?>_itemcount" value="<?php echo max($names) + 1; ?>" />

        <a data-prototype="<?php echo $datePrototype; ?>"
           class="add btn btn-warning ml-sm btn-add-item" href="#" id="<?php echo $form->vars['id']; ?>_additem">
            <?php echo $view['translator']->trans('mautic.integration.thecodeine.embed.form.add_menu_item'); ?>
        </a>
    </div>
</div>
