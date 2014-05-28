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
        }),
    };
    
    STUDIP.Portfolio.Homepage = {
        tag1: null,
        tag2: null,

        init: function() {
            $('#tagcloud a').bind('click', function() {
                // alert($(this).attr('data-tag'));

                if ($(this).parent().hasClass('lvl2')) {
                    $(this).siblings().removeClass('selected');
                    $(this).toggleClass('selected');
                    
                    STUDIP.Portfolio.Homepage.tag2 = $(this).hasClass('selected') ? $(this).attr('data-tag') : null;
                } else {
                    $(this).parent().siblings().find('.open').toggleClass('open', 'closed')
                            .parent().find('.lvl2').hide().find('.selected').removeClass('selected');

                    $(this).toggleClass('open', 'closed');
                    $(this).parent().find('.lvl2').toggle();
                    
                    STUDIP.Portfolio.Homepage.tag1 = $(this).hasClass('open') ? null : $(this).attr('data-tag') ;
                    STUDIP.Portfolio.Homepage.tag2 = null;
                }
                
                STUDIP.Portfolio.Homepage.filter(STUDIP.Portfolio.Homepage.tag1, STUDIP.Portfolio.Homepage.tag2);
            })
        },
        
        filter: function(tag1, tag2) {
            if (tag1 === null && tag2 === null) {
                $('table[data-tag]').show('explode', 800);
                return;
            }

            if (tag1 !== null) {
                $('table[data-tag]').each(function() {
                    if ($(this).attr('data-tag') === tag1) {
                        $(this).show('fade', 1000);
                    } else {
                        $(this).hide('explode', 800);
                    }
                    // $(this).toggle($(this).attr('data-tag') === tag1);
                    
                    if (tag2 !== null && $(this).attr('data-tag') === tag1) {
                        $(this).find('tr.task').each(function() {
                            if ($.inArray(tag2, $(this).data('tags')) !== -1) {
                                $(this).show('fade');
                            } else {
                                $(this).hide('fade');
                            }
                            // $(this).toggle($.inArray(tag2, $(this).data('tags')) !== -1);
                        });
                    } else if ($(this).attr('data-tag') === tag1) {
                        $(this).find('tr.task').show('fade');
                    }
                });
            }
        },
        
        addPermission: function() {
            var data = {
                user: $('#permissions select[name=search]').val(),
                fullname: $('#permissions select[name=search]').parent().find('.chosen-single').text(),
                perm: $('#permissions select[name=permission]').val(),
                permission: $('#permissions select[name=permission]').parent().find('.chosen-single').text()
            };
            
            if (data.user === undefined || data.user === null) {
                $('#permissions select[name=search]').siblings('.chosen-error').hide();
                $('#permissions select[name=search]').siblings('.chosen-error').show('highlight');
                return;
            }
            
            $('#permissions select[name=search]').siblings('.chosen-error').hide();
            
            var template = STUDIP.Portfolio.getTemplate('permission');
            $('#permission_list').append(template(data)).find('div:last-child img').click(function() {
                $(this).parent().parent().remove();
            });
        }
    };

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
    };
}(jQuery));