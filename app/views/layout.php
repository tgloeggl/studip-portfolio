<? $this->set_layout($GLOBALS['template_factory']->open('layouts/base')); ?>
<!-- breadcrumb navigation -->
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

<!-- area for messages -->
<? if (!empty($flash['messages'])) foreach ($flash['messages'] as $message) : ?>
    <?= MessageBox::$message['type']($message['text']) ?>
<? endforeach ?>

<script>
    var STUDIP = STUDIP || {};
    STUDIP.PortfolioConfig = STUDIP.PortfolioConfig || {};
    STUDIP.PortfolioConfig.base_url = '<?= $controller->url_for('') ?>';
</script>

<!-- content -->
<?= $content_for_layout ?>