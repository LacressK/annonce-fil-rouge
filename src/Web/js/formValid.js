$(document).ready(() => {
  $("#submitUser").click(function () {
    validate = true;

    // Validation Nom
    if ($("#nom").val() == "") {
      $("#nom").css("border-color", "red");
      validate = false;
    }
    else if(!$("#nom").val().match(/^[a-z]+$/i)) {
      $("#nom").css("border-color", "red");
      validate = false;
    }
    else {
      $("#nom").css("border-color", "green");
    }

    // Validation Prenom
    if ($("#prenom").val() == "") {
      $("#prenom").css("border-color", "red");
      validate = false;
    }
    else if(!$("#prenom").val().match(/^[a-z]+$/i)) {
      $("#prenom").css("border-color", "red");
      validate = false;
    }
    else {
      $("#prenom").css("border-color", "green");
    }

    // Validation Email
    var emailRegex = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i; 

    if ($("#mail").val() == "") {
      $("#mail").css("border-color", "red");
      validate = false;
    }
    else if(!$("#mail").val().match(emailRegex)) {
      $("#mail").css("border-color", "red");
      validate = false;
    }
    else {
      $("#mail").css("border-color", "green");
    }

    // Validation Pseudo
    var usernameRegex = /^[a-zA-Z0-9]+([a-zA-Z0-9](-)[a-zA-Z0-9])*[a-zA-Z0-9]+$/;
    /*  
    Usernames can consist of lowercase and capitals
    Usernames can consist of alphanumeric characters
    Cannot be two underscores, two hypens or two spaces in a row
    Cannot have a underscore, hypen or space at the start or end  
    */

    if ($("#pseudo").val() == "") {
      $("#pseudo").css("border-color", "red");
      validate = false;
    }
    else if(!$("#pseudo").val().match(usernameRegex)) {
      $("#pseudo").css("border-color", "red");
      validate = false;
    }
    else {
      $("#pseudo").css("border-color", "green");
    }

    // Validation Mot de passe
    var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/;
    /*
      /^
      (?=.*\d)          should contain at least one digit
      (?=.*[a-z])       should contain at least one lower case
      (?=.*[A-Z])       should contain at least one upper case
      [a-zA-Z0-9]{8,}   should contain at least 8 from the mentioned characters
      $/
    */

    if ($("#mdp").val() == "") {
      $("#mdp").css("border-color", "red");
      validate = false;
    }
    else if(!$("#mdp").val().match(passwordRegex)) {
      $("#mdp").css("border-color", "red");
      validate = false;
    }
    else {
      $("#mdp").css("border-color", "green");
    }

    // Validation Mot de passe confirmation

    if ($("#mdpConf").val() == "") {
      $("#mdpConf").css("border-color", "red");
      validate = false;
    }
    else if ($("#mdp").val() != $("#mdpConf").val()) {
      $("#mdpConf").css("border-color", "red");
      validate = false;
    }
    else {
      $("#mdpConf").css("border-color", "green");
    }

    return validate;
  });
});

$(document).ready(() => {
  $("#submitAddRubrique").click(function () {
    validate = true;
    // Validation Libelle
    if ($("#newRubrique").val() == "") {
      $("#newRubrique").css("border-color", "red");
      validate = false;
    }
    else if(!$("#newRubrique").val().match(/^[a-z]+$/i)) {
      $("#newRubrique").css("border-color", "red");
      validate = false;
    }
    else {
      $("#newRubrique").css("border-color", "green");
    }
    return validate;
  });
});

$(document).ready(() => {
  $("#submitEditRubrique").click(function () {
    validate = true;
    // Validation Libelle
    if($.trim($("#updRubrique").val()) == '') {
      $("#updRubrique").css("border-color", "red");
      validate = false;
    }
    else if(!$("#updRubrique").val().match(/^[a-z]+$/i)) {
      $("#updRubrique").css("border-color", "red");
      validate = false;
    }
    else {
      $("#updRubrique").css("border-color", "green");
    }
    return validate;
  });
});