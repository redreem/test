var common = {

    init:function() {

        this.checkAlerts();

        // ----- сабмиты форм
        $('#submit_login_form').bind('click', function() {

            $('#login_form').submit();
            return false;
        });

        $('#submit_add_form').bind('click', function() {

            $('#add_form').submit();
            return false;
        });

        $('#submit_edit_form').bind('click', function() {

            $('#edit_form').submit();
            return false;
        });

        // ----- события кнопок
        $('#add_task_button').bind('click', function() {

            $('#edit_form').animate({
                height:0
            }, 1000);

            $('#add_form').animate({
                height:466
            }, 1000);
        });

        var $this = this;

        $('.edit_task_button').bind('click', function() {

            $this.edit(this);
        });

        $('#login_button').bind('click', function() {

            $(this).hide();

            $('#login_form').animate({
                opacity:1
            }, 1000);
        });

        $('#order_field_select, #order_direct_select').bind('change', function() {

            var field = $this.getSelectValue($('#order_field_select').get(0));
            var direct = $this.getSelectValue($('#order_direct_select').get(0));

            location.href = '/tasks?order_field=' + field + '&tasks_page=' + FE.getData('tasks_page') + '&order_direct=' + direct;
        });
    },

    checkAlerts:function() {

        if (FE.getData('error')) {

            var errors = FE.getData('errors');
            var messages = [];

            for (var code in errors) {

                if (errors[code]['status']) {

                    messages.push(errors[code]['descr']);
                }
            }

            switch (FE.getData('action')) {

                case 'add':

                    $('#add_form').animate({
                        height:466
                    }, 1000);

                    $('#task_user_name_add').val( FE.getData('user_name') );
                    $('#task_user_email_add').val( FE.getData('user_email') );
                    $('#task_description_add_input').text( FE.getData('task_description') );
                    break;

                case 'edit':

                    $('#edit_form').animate({
                        height:392
                    }, 1000);

                    var description = '';

                    $('.admin_task_row').each(function() {

                        if ($(this).data('id') == FE.getData('task_id')) {

                            description = $(this).find('.task_description').text();

                            $('#task_description_edit_input').text(123 + description);

                            return false;
                        }
                    });

                    $('#task_id').val( FE.getData('task_id'));
                    break;
            }
            alert(messages.join(' | '));
        }

        if (FE.getData('bad_login')) {

            alert('Неверный логин или пароль');
        }
    },

    getSelectValue:function(select_el) {

        var index = select_el.selectedIndex;
        var i = 0;
        var value;
        $(select_el).find('option').each(function(){

            if (i == index) {

                value = $(this).attr('value');
                return false;
            }
            i++;
        });

        return value;
    },

    edit:function(edit_button_el) {

        $('#add_form').animate({
            height:0
        }, 1000);

        $('#edit_form').animate({
            height:392
        }, 1000);

        var row_el = $(edit_button_el).parent().parent();
        var task_id = $(row_el).data('id');

        var description_el = $(row_el).find('.task_description').get(0);
        var description = description_el.innerText;

        $('#task_description_edit_input').text(description);
        $('#task_id').val(task_id);
    }
};

$(document).ready(function() {

    common.init();
});