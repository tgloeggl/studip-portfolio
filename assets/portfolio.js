var STUDIP = STUDIP || {};

(function ($) {

    $(document).ready(function() {
       // $('#portfolio select.chosen').chosen();
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
        addCombo: function () {
            // load the studycourse if they are not yet present
            if (STUDIP.Portfolio.studiengaenge === null) {
                $.ajax(STUDIP.URLHelper.getURL('plugins.php/portfolio/admin/set/get_studycourses'), {
                    success: function(data) { 
                        STUDIP.Portfolio.studiengaenge = data;
                        STUDIP.Portfolio.Admin.doAddCombo();
                    }
                });
            } else {
                STUDIP.Portfolio.Admin.doAddCombo();
            }

        },
        
        doAddCombo: function() {
            var template = STUDIP.Portfolio.getTemplate('studycourse_template');
            var template_data = {
                num: this.num,
                options: STUDIP.Portfolio.studiengaenge
            }
            
            $('div.studiengang_combos').append(template(template_data));
            
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