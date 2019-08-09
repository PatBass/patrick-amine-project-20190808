var sp = sp = sp || {};
"use strict";
sp.profile = function () {
    var nom = $("#nom").val();
    var prenom = $("#prenom").val();
    var sexe = $("#sexe").val();
    var dateNaissance = $("#dateNaissance").val();

    var profile = {
        'reset': function () {
            $("#nom").val(nom);
            $("#prenom").val(prenom);
            $("#sexe").val(sexe);
            $("#dateNaissance").val(dateNaissance);
        },
        'refresh': function () {
            nom = $("#nom").val();
            prenom = $("#prenom").val();
            sexe = $("#sexe").val();
            dateNaissance = $("#dateNaissance").val();
        },
        'updateProfileError': function (data, status, error) {
            var form = JSON.parse(data.responseText);
            $("#profileForm").find("#messages").empty().append(sp.global.formatErrorMessages(form.messages));
        },
        'updateProfileSuccess': function (form, status, xhr) {
            sp.profile.refresh();
            $("#profileForm").find("#messages").empty().append(sp.global.formatErrorMessages(form.messages));
        }
    }
    return profile;
}();
