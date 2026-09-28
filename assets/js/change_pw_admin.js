$(document).ready(function(){
    $('#adminChangePWForm').on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
        url: "/CPMS/controllers/change_pw_admin.php",
        type: "POST",
        data: $(this).serialize(),
        dataType:"json",
        
        success: function(response){
            if(response.status === 'success') {
                Swal.fire({
                    title: 'Changed!',
                    text: response.message,
                    icon: 'success'
                }).then(() => {
                    window.location.href = '/CPMS/auth/logout.php';
                });
            } else if (response.status === 'error') {
                Swal.fire({
                    title: 'Error!',
                    text: response.message,
                    icon: 'error'
                });
            }
        }
        });
    });
});
