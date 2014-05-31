var STUDIP = STUDIP || {};

(function ($) {

    $(document).ready(function() {
        $('a.confirm').bind('click', function() {
            return confirm('Sind Sie sicher?'.toLocaleString());
        })

        // add a new portfolio
        $('span.add_portfolio').bind('click', function() {
            window.location = STUDIP.URLHelper.getURL('plugins.php/portfolio/portfolio/add');
        });

        // edit the title of a portfolio
        $('span.edit_portfolio').bind('click', function() {
            // the current name of the portfolio
            var current_text = $(this).parent().find(':first-child');

            // add an edit element
            $(current_text).parent().prepend(
                $('<input class="portfolio" type="text">')
                    .val($(current_text).text())
                    .blur(function() {
                        $(this).parent().prepend($('<span>').text($(this).val()));

                        $.post(STUDIP.URLHelper.getURL('plugins.php/portfolio/portfolio/update/'
                            + $(this).parent().attr('data-id')), {
                                name:  $(this).val()
                            });
                        $(this).remove();
                    })
            );

            // focus on the new edit-element
            $(current_text).parent().find('input').focus();

            // remove the obsolete span
            $(current_text).remove();
        });

        jQuery('#fileupload').fileupload({
            url: $('input[name=upload_url]').val(),
            dataType: 'json',
            add: function (e, data) {
                STUDIP.Portfolio.File.file_id += 1;
                data.id = STUDIP.Portfolio.File.file_id;
                STUDIP.Portfolio.File.addFile(e, data);
            },
            done: function (e, data) {
                var files = data.result;

                if (typeof files.errors === "object") {
                    var errorTemplateData = {
                        message: json.errors.join("\n")
                    }
                    jQuery('#files_to_upload').before(STUDIP.Portfolio.File.errorTemplate(errorTemplateData));
                } else {
                    _.each(files, function(file) {
                        var id = jQuery('#files_to_upload tr:first-child').attr('data-fileid');
                        jQuery('#files_to_upload tr[data-fileid=' + id + ']').remove();

                        var templateData = {
                            id     : file.id,
                            url    : file.url,
                            name   : file.name,
                            size   : Math.round((file.size / 1024) * 100) / 100,
                            date   : file.date,
                            seminar: file.seminar_id
                        }

                        jQuery('#uploaded_files').append(STUDIP.Portfolio.File.uploadedFileTemplate(templateData));
                    });
                }
            },

            progress: function (e, data) {
                var kbs = parseInt(data._progress.bitrate / 8 / 1024);
                var progress = parseInt(data.loaded / data.total * 100, 10);
                var id = jQuery('#files_to_upload tr:first-child').attr('data-fileid');
                jQuery('#files_to_upload tr[data-fileid=' + id + '] progress').val(progress);
                jQuery('#files_to_upload tr[data-fileid=' + id + '] .kbs').html(kbs);
            },

            error: function(xhr, data) {
                var id = jQuery('#files_to_upload tr:first-child').attr('data-fileid');
                jQuery('#files_to_upload tr[data-fileid=' + id + '] td:nth-child(3)')
                            .html('Fehler beim Upload (' + xhr.status  + ': ' + xhr.statusText + ')');
                jQuery('#files_to_upload tr[data-fileid=' + id + '] td:nth-child(4)').html('');
                jQuery('#files_to_upload tr[data-fileid=' + id + '] td:nth-child(5)').html('');
                jQuery('#files_to_upload tr[data-fileid=' + id + '] td:nth-child(6)').html('');

                jQuery('#files_to_upload').append(jQuery('#files_to_upload tr[data-fileid=' + id + ']').remove());
            }
        });

        // load templates
        STUDIP.Portfolio.File.fileTemplate         = _.template(jQuery("script.file_template").html());
        STUDIP.Portfolio.File.uploadedFileTemplate = _.template(jQuery("script.uploaded_file_template").html());
        STUDIP.Portfolio.File.errorTemplate        = _.template(jQuery("script.error_template").html());
    });



    STUDIP.Portfolio = {
        studiengaenge: null,

        getTemplate: _.memoize(function(name) {
            return _.template(jQuery("script." + name).html());
        }),
    };

    STUDIP.Portfolio.File = {
        files : {},
        maxFilesize: 0,
        fileTemplate: null,
        uploadedFileTemplate: null,
        errorTemplate: null,
            questionTemplate: null,
        file_id: 0,

        addFile: function(e, data) {
            // this is the first file for the current upload-list
            if (STUDIP.Portfolio.File.file_id == 1) {
                jQuery('#files_to_upload').html('');
            }

            jQuery('#upload_button').removeClass('disabled');

            var file = data.files[0];
            STUDIP.Portfolio.File.files[data.id] = data;

            var templateData = {
                id: data.id,
                name: file.name,
                error: file.size > STUDIP.Portfolio.File.maxFilesize,
                size: Math.round((file.size / 1024) * 100) / 100
            }

            jQuery('#files_to_upload').append(STUDIP.Portfolio.File.fileTemplate(templateData));

            if(file.type == 'image/png'
                || file.type == 'image/jpg'
                || file.type == 'image/gif'
                || file.type == 'image/jpeg') {

                var img = new Image();

                var reader = new FileReader();

                reader.onload = function (e) {
                    img.src = e.target.result;
                }

                reader.readAsDataURL(file);

                jQuery('#files_to_upload tr:last-child td:first-child').append(img);
            }
        },

        removeUploadFile: function(id) {
            var files = STUDIP.Portfolio.File.files[id];
            delete STUDIP.Portfolio.File.files[id];

            _.each(files, function(file) {
                if (file.jqXHR) {
                    file.jqXHR.abort();
                }
            });

            jQuery('#files_to_upload tr[data-fileid=' + id + ']').remove();
        },

        removeFile: function(id) {
            jQuery.ajax(STUDIP.ABSOLUTE_URI_STUDIP + "plugins.php/portfolio/file"
                    + "/remove_file/" + id, {
                dataType: 'json',
                success : function() {
                    jQuery('#uploaded_files tr[data-fileid=' + id + ']').remove();
                },
                error: function(xhr) {
                    var json = jQuery.parseJSON(xhr.responseText);
                    alert('Fehler - Server meldet: ' + json.message);
                }
            });
        },

        upload: function() {
            // do nothing if upload has been disabled
            if (jQuery('upload_button').hasClass('disabled')) {
                return;
            }

            // set upload as disabled
            jQuery('#upload_button').addClass('disabled');

            // upload each file separately to allow max filesize for each file
            _.each(STUDIP.Portfolio.File.files, function (data) {
                if (data.files[0].size > 0 && data.files[0].size <= STUDIP.Portfolio.File.maxFilesize) {
                    data.submit();
                }
            });

            STUDIP.Portfolio.File.files = {};
            STUDIP.Portfolio.File.file_id = 0;
        },
    }

    STUDIP.Portfolio.Homepage = {
        tag1: null,
        tag2: null,

        init: function() {
            $('td.tags a').bind('click', function() {
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
            // if no tag is select anymore, show everything
            if (tag1 === null && tag2 === null) {
                // show all tables
                $('table[data-tag]').show('explode', 800);

                // show all tasks
                $('table[data-tag] tr.task').show('fade');
                return;
            }

            if (tag1 !== null) {
                $('table[data-tag]').each(function() {
                    // toggle the main tag
                    if ($(this).attr('data-tag') === tag1) {
                        $(this).show('fade', 1000);
                    } else {
                        $(this).hide('explode', 800);
                    }

                    // toggle the subtags
                    if (tag2 !== null && $(this).attr('data-tag') === tag1) {
                        $(this).find('tr.task').each(function() {
                            // toggle correct tasks to show / hide - match their tags
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

            STUDIP.Portfolio.Homepage.addPermissionTemplate(data);
        },

        addPermissionTemplate: function(data) {
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