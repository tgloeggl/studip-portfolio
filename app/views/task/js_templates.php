<script type="text/template" class="permission">
    <div class="three-columns" style="margin: 5px" data-user="<%- user %>">
        <div><%- fullname %></div>
        <div><%- permission %></div>
        <div>
            <?= Assets::img('icons/16/blue/trash.png', array(
                'title' => _('Berechtigung entfernen'),
                'class' => 'link'
            )) ?>
        </div>
        <br style="clear: both">
    </div>
</script>