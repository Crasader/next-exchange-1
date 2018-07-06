var User = function () {
    var reset2FA = function (reset2FAUrl) {
        $("body").on('click', '.reset2fa', function (e) {
            e.preventDefault();

            swal({
                    title: "Are you sure?",
                    text: "Do you really want to reset the 2FA?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, reset 2FA!",
                    closeOnConfirm: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: "POST",
                            url: reset2FAUrl,
                            success: function (data) {
                                swal("Completed!", "2FA reset link has been sent to your email address! It will be valid for a " + data.ttl + ".", "success");
                            },
                            error: function (data) {
                                swal("Error!", "Something went wrong!", "error");
                            }
                        });
                    }

                });
        });
    };

    return {
        manage: function (reset2FAUrl) {
            reset2FA(reset2FAUrl);
        }
    }
}();


