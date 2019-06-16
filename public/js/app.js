;(function (window, document, $, undefined) {
    if (!$) {
        return undefined;
    }
    let loaderUrl = '/preloader.svg';
    $.fn.extend({
        loader: function (enable) {
            if (enable) {
                $(this).css('position', 'relative');
                $(this).append(
                    '<div id="form_loader" style="position: absolute;z-index: 999;display: flex;flex-direction: column;justify-content: center;text-align: center;width: 100%;height: 100%;/*background: rgba(0,0,0,.15)*/;left: 0;top: 0;">' +
                    '<img src="' + loaderUrl + '" alt="">' +
                    '</div>'
                );
            } else {
                $('#form_loader').remove();
                $(this).css('position', 'auto');
            }
        },
        reloadObj: function (loader = true) {
            let className = '';
            this[0].classList.forEach((item) => {
                className += '.' + item;
            });
            if (loader) {
                $(className).loader(true);
            }
            $.ajax({
                url: window.location.href,
                dataType: 'html',
                success: function (resp) {
                    $(document).find(className).html($(resp).find(className).html());
                    if (loader) {
                        $(className).loader(false);
                    }
                    setTolltip();
                }
            });
        }
    });
}(window, document, window.jQuery));

function setTolltip() {
    $('[title]').tooltipster({
        theme: 'tooltipster-shadow',
        side: 'right',
        delay: 1000,
    });
    $('[data-name]').tooltipster({
        theme: 'tooltipster-shadow',
        side: 'right',
        content: 'Редактировать',
        delay: 0,
    })
}

function countProperties(obj) {
    return Object.keys(obj).length;
}

function Crm() {
    /**
     * Блокировка страницы если запущен таймер
     * @type {boolean}
     */
    let blockedPage = false;
    window.addEventListener("beforeunload", function (e) {
        if (!blockedPage)
            return;

        let confirmationMessage = 'Сначала остановите таймер';

        (e || window.event).returnValue = confirmationMessage;
        return confirmationMessage;
    });

    /**
     * Оформление таблиц
     */
    $(document).on('mouseover', '.table tr', function () {
        let table = $(this).closest('.table');
        $('.table tr').removeClass('active');
        $(this).addClass('active');
    });
    $(document).on('mouseout', '.table tr', function () {
        $('.table tr').removeClass('active');
    });

    /**
     * Laravel csrf токен
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let clientContainer = '.client-editable';
    let updateContainer = '.update-container';
    let self = this;

    $(document).on('click', '.cart-create', _cartCreateClick);

    $(document).on('mouseover', '[data-name]', _appendEditButton);
    $(document).on('mouseout', '[data-name]', _removeEditButton);
    $(document).on('click', '[data-name]', _clickEditElement);

    $(document).on('click', '.createAccess', _appendAccessForm);
    $(document).on('keyup', '.createAccessForm input', _createAccess);

    $(document).on('click', '.createTask', _createTask);
    $(document).on('click', '.task-actions div.icon', _taskHandler);

    $(document).on('click', '.createPayment', _createPayment);

    $(document).on('change', '.upload-form input', _uploadFile);

    $(document).on('click', '#user-photo-upload', _uploadUserPhoto);
    $(document).on('submit', '#edit-profile', _editProfile);

    $(document).on('click', '#create_user', _createUser);

    $(document).on('submit', '.chat-footer form', _createMessage);

    $(document).on('click', '#msg_btn', _popup_msg);

    $(document).on('click', '.topic-container .topic', _loadTopicMessages);

    $('.message-popup form').submit(function () {
        let url = $('#msg_btn').data('url');
        let th = $(this);
        th.loader(true);
        let data = new FormData(th.get(0));
        $.ajax({
            url: url,
            data: data,
            dataType: 'json',
            method: 'POST',
            contentType: false,
            processData: false,
            success: function (response) {
                self.msg(response);
                $.fancybox.close();
                th.trigger('reset');
                th.loader(false);
            }
        });
        return false;
    });

    /** Профиль пользователя **/
    function _popup_msg() {
        $.fancybox.open($('.message-popup'));
    }

    /** Работа с чатами */
    setInterval(function () {
        self.updateChat();
    }, 1500);

    function _loadTopicMessages() {
        $('.topic').removeClass('active');
        let $th = $(this);
        let url = $th.data('url');
        let $loadBlock = $('#load-lk-chat');
        $loadBlock.loader(true);
        $.ajax({
            url: url,
            dataType: 'html',
            success: function (response) {
                $loadBlock.html(response);
                $loadBlock.loader(false);
                $th.addClass('active');
            }
        })
    }

    function _createMessage() {
        let th = $(this);
        let data = th.serialize();
        let url = th.attr('action');
        th.loader(true);
        $.ajax({
            url: url,
            dataType: 'json',
            data: data,
            method: 'POST',
            error: function () {
                let resp = {
                    success: false,
                    msg: 'Ошибка отправки сообщения'
                };
                self.msg(resp);
                th.loader(false);
            },
            success: function (response) {
                th.loader(false);
                let resp = {
                    success: true,
                    msg: 'Успшено отправлено'
                };
                th.trigger('reset');
                self.msg(resp);
                self.updateChat();
            }
        });
        return false;
    }

    this.updateChat = function () {
        let $form = $('.chat-footer form');
        if (!$form.length) {
            return false;
        }
        let url = $form.data('get');
        let lk = $form.data('lk');

        if (lk) {
            $.ajax({
                url: url,
                dataType: 'html',
                success: function (response) {
                    let countLocal = $('.message').length;
                    let countAjax = $(response).find('.message').length;
                    self.updateCount();
                    let $lk = $('#load-lk-chat');
                    if (countLocal !== countAjax) {
                        $lk.find('.chat-body').html($($(response)[2]).html());
                        $lk.find('input').focus();
                    }
                }
            });
        } else {
            $.ajax({
                url: url,
                dataType: 'json',
                success: function (response) {
                    let $messagess = $('.chat-body .message');
                    let countResponse = countProperties(response);
                    let countLocal = $messagess.length;

                    let text = '<i class="fas fa-dumbbell"></i> Количество сообщений: ' + countResponse;
                    $('.chat-container .card-footer').html(text);

                    if (countLocal !== countResponse) {
                        $('.reload-chat').reloadObj();
                    }
                }
            });
        }
    };

    this.updateCount = function () {
        let $block = $('.topic.active');
        // $block.loader(true);
        let url = $block.data('url');
        $.ajax({
            url: url,
            data: {
                count: true,
            },
            success: function (response) {
                let count = response;
                // $block.loader(false);
                $block.find('.dsc').text(count+' непрочитанных');
            }
        })
    };

    function _cartCreateClick() {
        $('#create-crm').modal('show');
    }

    function _appendEditButton() {
        $(this).append('<span class="editIcon"><i class="fas fa-pencil-alt"></i></span>');
    }

    function _removeEditButton() {
        $('.editIcon').remove();
    }


    function _clickEditElement() {
        let val = $(this).text().replace(/\s{2,}/g, '');
        let $parent = $(this).parent();
        let oldHtml = $parent.html();
        let name = $(this).data('name');
        let id = false;

        let $clientContainer = $(clientContainer);
        let action = $clientContainer.data('action');

        if ($(this).hasClass('accessClass')) {
            action = $clientContainer.data('access-action');
            id = $(this).data('id');
        }

        //accessClass

        $parent.html(
            '<div class="inputFormSave"> ' +
            '<input class="table-input" name="' + name + '" value="' + val + '" />' +
            '<span class="saveButton"><i class="fas fa-check"></i></span>' +
            '</div>'
        );

        let $inputFormSave = $('.inputFormSave input');
        $inputFormSave.focus();
        $inputFormSave.keyup(function (e) {
            if (e.originalEvent.keyCode == 13) {
                $(this).parent().find('.saveButton').click();
            }
        });

        $parent.find('.saveButton').click(function () {
            val = $(this).parent().find('input').val();
            let data = {};
            data[name] = val;
            if (id) {
                data['id'] = id;
                data['value'] = val;
                data['name'] = name;
            }

            updateClient(action, data);
        });

        return false;
    }

    function updateClient($action, $data) {
        $.ajax({
            url: $action,
            data: $data,
            method: 'POST',
            success: function (resp) {
                console.log(resp);
                $(updateContainer).reloadObj();
                self.msg(resp);
            }
        })
    }

    function _appendAccessForm() {
        let $container = $(this).closest('.table-responsive').find('.table tbody');
        $container.append(
            '<tr class="createAccessForm">\n' +
            '    <td>\n' +
            '        <input name="name" class="table-input" type="text" placeholder="Название">\n' +
            '    </td>\n' +
            '    <td>\n' +
            '        <input name="value" class="table-input" type="text" placeholder="Значение">\n' +
            '    </td>\n' +
            '</tr>'
        );
    }

    function _createAccess(e) {
        if (e.originalEvent.keyCode !== 13) {
            return false;
        }
        let action = $(clientContainer).data('access-action');
        let data = {all: []};
        $('.createAccessForm').each(function () {
            data.all.push({
                name: $(this).find('[name=name]').val(),
                value: $(this).find('[name=value]').val(),
            });
        });
        updateClient(action, data);
    }

    function _createTask() {
        let action = $(this).data('action');
        let $parent = $(this).closest('.table-responsive');
        $parent.append(
            '<form action="' + action + '" class="taskForm">\n' +
            '    <input type="text" name="text" class="table-input" placeholder="Введите название задачи">\n' +
            '</form>'
        );
        $(this).remove();


        let $taskForm = $('.taskForm input');
        $taskForm.focus();

        $('.taskForm').submit(function () {
            let $self = $(this);
            updateClient(action, {
                text: $self.find('input').val()
            });
            return false;
        });
    }

    let timerId = false;
    let foo = 0;

    function _taskHandler() {
        let action = $(this).data('action');
        let url = $(this).parent().data('url');
        let $tr = $(this).closest('tr');
        let id = $(this).parent().data('task-id');
        switch (action) {
            case 'start':
                blockedPage = true;
                timerId = setInterval(function () {

                    foo++;
                    if ((foo % 2) === 0) {
                        $('#favicon').attr('href', 'favicon2.ico');
                    } else {
                        $('#favicon').attr('href', 'favicon64.ico');
                    }

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            action: action
                        },
                        success: function (resp) {
                            $tr = $('[data-task-id=' + id + ']').closest('tr');
                            $tr.find('.time_tmp').text(resp.time);
                            $(document).find('title').text(resp.time);
                            if ((resp.second % 1800) === 0) {
                                let audio = new Audio();
                                audio.preload = 'auto';
                                audio.src = '/shabani.wav';
                                audio.play();
                            }
                        }
                    })
                }, 1000);
                setTimeout(function () {
                    $(updateContainer).reloadObj();
                }, 1500);
                self.msg({success: true, msg: 'Таймер запущен'});
                break;
            case 'pause':
                blockedPage = false;
                $('#favicon').attr('href', 'favicon64.ico');
                clearInterval(timerId);
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        action: action
                    },
                    success: function (resp) {
                        $(updateContainer).reloadObj();
                        self.msg(resp);
                        $(document).find('title').text('[Остановлено]');
                    }
                });
                break;
            case 'refresh':
                clearInterval(timerId);
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        action: action
                    },
                    success: function (resp) {
                        $(updateContainer).reloadObj();
                        self.msg(resp);
                    }
                });
                break;
            case 'success':
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        action: action
                    },
                    success: function (resp) {
                        $(updateContainer).reloadObj();
                        self.msg(resp);
                    }
                });
                break;
            case 'rename-task':
                let text = $tr.find('.task-text').text();
                let newName = prompt('Введите новое имя задачи', text);
                if (newName) {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            text: newName,
                            action: action
                        },
                        success: function (resp) {
                            $(updateContainer).reloadObj();
                            self.msg(resp);
                        }
                    });
                }
                break;
            case 'remove':
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        action: action
                    },
                    success: function (resp) {
                        $(updateContainer).reloadObj();
                        self.msg(resp);
                    }
                });
                break;
            case 'file-rename':
                let new_name = prompt('Введите новое имя');
                if (new_name) {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            action: action,
                            new_name: new_name,
                        },
                        success: function (resp) {
                            $(updateContainer).reloadObj();
                            self.msg(resp);
                        }
                    });
                }
                break;
        }
    }

    function _createPayment() {
        let $parent = $(this).closest('.table-responsive');
        let action = $(this).data('action');
        $parent.append(
            '<form class="paymentForm">\n' +
            '    <input type="text" name="name" class="table-input" placeholder="Имя">\n' +
            '    <input type="number" name="price" class="table-input" placeholder="Цена">\n' +
            '</form>'
        );
        $(this).remove();

        let $inputs = $('.paymentForm input');
        $($inputs[0]).focus();
        $inputs.keyup(function (e) {
            if (e.originalEvent.keyCode !== 13) {
                return false;
            }
            $(this).closest('form').submit();
        });

        $('.paymentForm').submit(function () {
            let $self = $(this);
            updateClient(action, {
                name: $self.find('[name=name]').val(),
                price: $self.find('[name=price]').val(),
            });

            return false;
        });
    }

    function _uploadFile() {
        let $form = $(this).closest('form');
        let url = $form.attr('action');
        let files = this.files;
        let data = new FormData();
        $.each(files, function (key, value) {
            data.append(key, value);
        });
        $('.files-container').loader(true);

        $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            processData: false,
            contentType: false,
            dataType: 'json',
            data: data,
            success: function (resp) {
                $(updateContainer).reloadObj();
                self.msg(resp);
                $('.files-container').loader(false);
            },
            error: function (respond, textStatus, jqXHR) {
                alert('Неизвестная ошибка');
            }
        });

        return false;
    }

    function _uploadUserPhoto() {
        let $input = $(this).parent().find('input[type=file]');
        $input.click();

        $input.change(function (e) {
            let action = $(this).data('action');
            let files = this.files;
            let data = new FormData();

            $.each(files, function (key, value) {
                data.append(key, value);
            });

            $.ajax({
                url: action,
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                dataType: 'json',
                data: data,
                success: function (resp) {
                    $(updateContainer).reloadObj();
                    self.msg(resp);
                },
                error: function (respond, textStatus, jqXHR) {
                    alert('Неизвестная ошибка');
                }
            });
        });


        return false;
    }

    function _editProfile() {
        let data = $(this).serialize();
        let action = $(this).data('action');
        updateClient(action, data);
        return false;
    }

    function _createUser() {
        let action = $(this).data('action');
        let name = prompt('Введите имя');
        let email = prompt('Введите Email');
        let pass = prompt('Введите пароль');
        if (!pass || !email || !name) {
            return false;
        }
        updateClient(action, {
            name: name,
            email: email,
            password: pass
        });
    }

    this.msg = function (resp) {
        if (resp.success) {
            self.showNotification('top', 'center', 3, resp.msg);
        } else {
            self.showNotification('top', 'center', 2, resp.msg);
        }
    };


    this.showNotification = function (from, align, color, msg) {
        let type = ['', 'info', 'danger', 'success', 'warning', 'rose', 'primary'];
        let icon;
        if (color === 3) {
            icon = 'fas fa-check';
        } else {
            icon = 'fas fa-exclamation-triangle';
        }
        // console.log(type[color]);

        $.notify({
            icon: icon,
            message: msg

        }, {
            type: type[color],
            delay: 1500,
            timer: 200,
            // type: 'progress',
            placement: {
                from: from,
                align: align
            },
            template: '<div data-notify="container" class="col-11 col-md-4 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">' +
                '   <i class="fas fa-times"></i>' +
                '</button>' +
                '<i data-notify="icon"></i>' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '   <div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">' +
                '   </div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a></div>'
        });
    };
    /* others */
    let quill = null;
    hljs.configure({
        languages: ['javascript', 'html', 'php'],
        useBR: false,
    });
    if ($('#dop-info').length) {
        $(document).on('click', '#dop-info #editor', function () {
            if ($(this).hasClass('ql-snow')) {
                return false;
            }
            $('#toolbar').fadeIn();

            quill = new Quill('#editor', {
                modules: {
                    toolbar: '#toolbar',
                    syntax: true
                },
                theme: 'snow',
            });
            let $saveBtn = $(this).parent().find('.save-editor');
            $saveBtn.fadeIn();
            $saveBtn.click(function () {
                let html = quill.root.innerHTML;
                let $clientContainer = $(clientContainer);
                let action = $clientContainer.data('action');
                let editorData = {
                    full_description: html
                };
                updateClient(action, editorData);
            });
            quill.focus();
        });
    }
    setTolltip();
    $(document).on('click', '.accordion .btn-collapse', function () {
        $('.accordion .btn-collapse').addClass('collapsed');
        $(this).removeClass('collapsed');
    });
}

$(document).ready(function () {
    new Crm();
});