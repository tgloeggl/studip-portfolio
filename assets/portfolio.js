var STUDIP = STUDIP || {};

(function ($) {

    $(document).ready(function() {
       $('a.confirm').bind('click', function() {
           return confirm('Sind Sie sicher?'.toLocaleString());
       })
    });

    STUDIP.Portfolio = {
        studiengaenge: null,
                
        getTemplate: _.memoize(function(name) {
            return _.template(jQuery("script." + name).html());
        })
    }
    
    STUDIP.Portfolio.Admin = {
        num : 0,
        
        /**
         * add a studycourse-combo select-box to the view
         * 
         * @returns {undefined}
         */
        addCombo: function (selected_elements) {
            // load the studycourse if they are not yet present
            if (STUDIP.Portfolio.studiengaenge === null) {
                $.ajax(STUDIP.URLHelper.getURL('plugins.php/portfolio/admin/set/get_studycourses'), {
                    success: function(data) { 
                        STUDIP.Portfolio.studiengaenge = data;
                        STUDIP.Portfolio.Admin.doAddCombo(selected_elements);
                    }
                });
            } else {
                STUDIP.Portfolio.Admin.doAddCombo(selected_elements);
            }

        },
        
        doAddCombo: function(selected_elements) {
            var template = STUDIP.Portfolio.getTemplate('studycourse_template');
            var template_data = {
                num: this.num,
                options: STUDIP.Portfolio.studiengaenge
            }

            $('div.studiengang_combos').append(template(template_data));

            if (selected_elements !== undefined) {
                for (var i = 0; i < selected_elements.length; i++) {
                    $('select[data-studycourse-num=' + this.num + '] option[value=' + selected_elements[i] + ']').attr('selected', 'selected');
                }
            }
            
            $('div.studiengang_combos select').chosen();
            
            this.num++;
        },
        
        /**
         * remove the studygroup-combo denoted by the passed num from the view
         * @param int num
         * @returns {undefined}
         */
        removeCombo: function(num) {
            $('div[data-studycourse-num=' + num + ']').remove();
        }
    }
}(jQuery));