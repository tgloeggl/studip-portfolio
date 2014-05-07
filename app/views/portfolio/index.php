<div id="portfolio">
    <h1>Meine Portfolios</h1>
    
    <? foreach ($portfolios as $portfolio) : ?>
    <a href="<?= $controller->url_for('task/index/' . $portfolio['id']) ?>">
        <span class="portfolio" title="<?= htmlReady($portfolio['name']) ?>">
            <?= Assets::img('icons/64/blue/seminar.png') ?><br>
            <?= htmlReady($portfolio['name']) ?>
        </span>
    </a>
    <? endforeach ?>
</div>