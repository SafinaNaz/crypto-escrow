//Javascript Code By 
$(document).ajaxStart(function () {
    showLoader();
});
$(document).ajaxStop(function () {
    hideLoader();
});

function showLoader() {
    if (typeof NProgress !== undefined && typeof NProgress !== 'undefined') {
        NProgress.start();
    }
}

function hideLoader() {
    if (typeof NProgress !== undefined && typeof NProgress !== 'undefined') {
        NProgress.done();
    }
}
$(function () {

    jQuery.validator.addMethod("validBTCAddress", function (value, element) {
        return this.optional(element) || WAValidator.validate(value, 'BTC');
    }, "Please provide a valid Bitcoin wallet address");
    jQuery.validator.addMethod("validXMRAddress", function (value, element) {
        return this.optional(element) || WAValidator.validate(value, 'XMR');
    }, "Please provide a valid Monero wallet address");

    /*limiter.js*/
    if ($('textarea.limited, input.limited').length > 0) {
        $('textarea.limited, input.limited').inputlimiter({
            remText: '%n character%s remaining...',
            limitText: 'max allowed : %n.'
        });
    }

    $("#login-form, #register-form, #verify-form, #reset-password-form, #confirm-form, #email-form, #profile-form, #etl-form, #msg-form, #msg-form-private").validate({
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });
});
//show image onchange input file function
function change_image(input, img_id, show_div, width, height) {

    var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
    var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);
    if (isSafari) {
        var nAgt = navigator.userAgent;
        var fullVersion = '' + parseFloat(navigator.appVersion);
        var verOffset;
        if ((verOffset = nAgt.indexOf("Safari")) != -1) {
            fullVersion = parseFloat(nAgt.substring(verOffset + 7));
            if ((verOffset = nAgt.indexOf("Version")) != -1)
                fullVersion = parseFloat(nAgt.substring(verOffset + 8));
        }
        if (fullVersion >= 7) {
            readURL1(this);
        } else {
            $('#' + show_div).html(input);
        }

    } else {
        readURL1(input, img_id, show_div, width, height);
    }
}

// Modifide readURL function for generic images
function readURL1(input, img_id, show_div, width, height) {


    if (input.files && input.files[0]) {
        var reader = new FileReader();

        var file = input.files[0];

        var image = new Image();
        reader.readAsDataURL(file);
        reader.onload = function (_file) {
            image.src = _file.target.result;
            image.onload = function () {
                var w = this.width,
                    h = this.height,
                    t = file.type,
                    n = file.name,
                    s = ~~(file.size / 1024) + 'KB';

                if (typeof width != 'undefined' && typeof height != 'undefined') {
                    if (w >= width && h >= height) {
                        $('#' + img_id).attr('src', image.src).parent().show();
                    } else {
                        $('#' + img_id).val('');
                        alert('Invalid image size (' + w + 'x' + h + '). Image must be large then ' + width + ' x ' + height);
                        return false;
                    }
                } else {
                    $('#' + img_id).attr('src', image.src).parent().show();
                    /* $('#' + show_div).html('image size: ' + w + ' X ' + h);*/

                }
            };

        };

    }
}
function subscribe() {
    var url = APP_URL + "/subscribe-us";
    var _token = $('meta[name="csrf-token"]').attr('content');
    var data = { _token: _token, subscribe_email: $("#subscribeEmail").val() };
    if ($("#subscribeEmail").val() != "") {
        $.post(url, data, function (response) {
            if (response.response == 1) {
                Swal.fire("Success!", "Your email has been subscribed successfully.", "success");
            } else {
                Swal.fire("Error!", "Email is invalid. Please provide a valid email to subscribe.", "error");
            }
            $("#subscribeEmail").val("");

        });
    } else {
        Swal.fire("Warning!", "Please enter your email to subscribe.", "warning");
    }
}


$(document).ready(function () {

    $(window).scroll(function () {
        var nav = $('.site-nav');
        var top = 95;
        if ($(window).scrollTop() >= top) {

            nav.addClass('fixed-nav');

        } else {
            nav.removeClass('fixed-nav');
        }
    });

    /*Mobile Left Menu Toggle*/
    $(".navbar-toggler").click(function () {
        $(".left-menu").toggleClass("open");
        $(".right-canvas").toggleClass("expanded");
    });

});