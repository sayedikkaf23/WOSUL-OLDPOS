<?php
function generateSlack($model) {
    $slack = substr(md5(time()), 0, 16); // better than rand()

    // call the same function if the barcode exists already
    if (slackExists($model,$slack)) {
        return generateSlack($model);
    }

    // otherwise, it's valid and can be used
    return $slack;
}

function slackExists($model,$slack) {
    // query the database and return a boolean
    // for instance, it might look like this in Laravel
    return $model::whereSlack($slack)->withTrashed()->exists();
}

function generateActivationCode($model) {
    $code = substr(md5(time()), 0, 20); // better than rand()

    // call the same function if the barcode exists already
    if (activationCodeExists($model,$code)) {
        return generateActivationCode($model);
    }

    // otherwise, it's valid and can be used
    return $code;
}

function activationCodeExists($model,$code) {
    // query the database and return a boolean
    // for instance, it might look like this in Laravel
    return $model::whereActivationCode($code)->withTrashed()->exists();
}
