<script type="text/template" class="permission">
    <div class="three-columns" style="margin: 5px">
        <div><%- fullname %></div>
        <div><%- permission %></div>
        <div>
            <?= Assets::img('icons/16/blue/trash.png', array(
                'title' => _('Berechtigung entfernen'),
                'class' => 'link'
            )) ?>
        </div>
        <input type="hidden" name="perms[<%- user %>]" value="<%- perm %>">
        <br style="clear: both">
    </div>
</script>