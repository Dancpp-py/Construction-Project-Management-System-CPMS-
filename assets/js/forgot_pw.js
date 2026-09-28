$(document).ready(function () {
    $("#forgotPasswordForm").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: "/CPMS/controllers/forgot_pw_admin.php",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    Swal.fire({
                        icon: "success",
                        title: "New Password Sent",
                        text: "Please check your email for your new password.",
                    });
                    $("#forgotPasswordForm")[0].reset();
                } else if (response.status === "error") {
                    Swal.fire({
                        icon: "error",
                        title: "Failed!",
                        text: response.message
                    });
                }
            },
        });
    });
});