var sp = sp = sp || {};
"use strict";
sp.account = function () {
    var account = {
        'createAccountSuccess': function (form, status, xhr) {
            $("#createAccountForm").find("#messages").empty().append(sp.global.formatErrorMessages(form.errorMessages));

            setTimeout(function () {
                window.location = xhr.getResponseHeader('location');
            }, 3000);
        },

        'createAccountError': function (data, status, error) {
            var form = JSON.parse(data.responseText);
            $("#createAccountForm").find("#messages").empty().append(sp.global.formatErrorMessages(form.errorMessages));
        },


        'loginAccountError': function (data, status, error) {
            var form = JSON.parse(data.responseText);
            $("#loginAccountForm").find("#messages").empty().append(sp.global.formatErrorMessages(form.messages));
        },

        'loginAccountSuccess': function (form, status, xhr) {
            if (xhr.getResponseHeader('location')) {
                window.location = xhr.getResponseHeader('location');
            } else {
                window.location.reload(true);
            }
        }
    };
    return account;
}();
