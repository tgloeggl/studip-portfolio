<!-- Form-Buttons -->
<div class="portfolio-buttons">
    <div class="button-group">
        <?= Studip\Button::createAccept(_('Speichern')) ?>
        <?= Studip\LinkButton::createCancel(_('Abbrechen'), $controller->url_for('task/index/' . $portfolio->id)) ?>
    </div>
</div>