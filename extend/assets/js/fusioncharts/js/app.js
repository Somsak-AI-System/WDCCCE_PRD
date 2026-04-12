$(function() {
    "use strict";

    $.alert = function(config){
        Swal.fire({
            position: 'center',
            type: config.type,
            title: config.msg,
            showConfirmButton: false,
            timer: 1500
        });
    }
});
