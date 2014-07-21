<? $this->set_layout($GLOBALS['template_factory']->open('layouts/base')); ?>
<span class="breadcrumb">
    <?
    $breadcrumb = array();
    foreach ($path as $step) :
        if (is_array($step)) {
            $breadcrumb[] = '<a href="'. $controller->url_for($step[0]) .'">'
                          . htmlReady($step[1]) . '</a>';
        } else {
            $breadcrumb[] = htmlReady($step);
        }
    endforeach; ?>

    <span><?= _('Sie befinden sich hier:') ?></span>
    <span><?= implode(' &gt; ', $breadcrumb) ?></span>
</span>

<?= $content_for_layout ?>