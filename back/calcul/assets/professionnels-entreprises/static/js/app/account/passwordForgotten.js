sp = sp = sp || {};
"use strict";
sp.password = function () {

    var password = {
        'passwordForgottenSendMailError': function (data, status, error) {
            var form = JSON.parse(data.responseText);
            $("#passwordForgottenForm").find("#messages").empty().append(sp.global.formatErrorMessages(form.messages));
        },

        'passwordForgottenSendMailSuccess': function (form, status, xhr) {
            $("#passwordForgottenForm").find("#messages").empty().append(sp.global.formatErrorMessages(form.messages));
        },

        'newPasswordError': function (data, status, error) {
            var form = JSON.parse(data.responseText);
            $("#newPasswordForm").find("#messages").empty().append(sp.global.formatErrorMessages(form.messages));
        },

        'newPasswordSuccess': function (form, status, xhr) {
            $("#newPasswordForm").find("#messages").empty().append(sp.global.formatErrorMessages(form.messages));

            setTimeout(function () {
                window.location = xhr.getResponseHeader('location');
            }, 3000);
        }
    }
    return password;
}();
