<script type="text/template" class="studycourse_template">
<div data-studycourse-num="<%- num %>">
    <select name="studycourses[<%- num %>][]" data-studycourse-num="<%- num %>" multiple class="chosen" data-placeholder="<?= _('Wählen Sie bitte einen oder mehrere Studiengänge') ?>">
        <% _.each(options, function(opt) { %>
        <option value="<%- opt.value %>"><%- opt.name %></option>
        <% }); %>
    </select> 
    <a href="javascript:STUDIP.Portfolio.Admin.removeCombo(<%- num %>)"><?= Assets::img('icons/16/blue/trash.png') ?></a>
    <br><br>
</div>
</script>