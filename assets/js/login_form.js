$(document).ready(function(){
    $('#swal-login').on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
        url: "/CPMS/auth/login.php",
        type: "POST",
        data: $(this).serialize(),
        dataType:"json",
        
        success: function(response){
            if(response.status === 'success' && response.type === 'admin'){
                console.log(response);
                Swal.fire({
                    title: 'Login successful!',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '/CPMS/modules/admin/admin_dashboard.php';
                });
            }else if(response.status === 'success' && response.type === 'contractor'){
                console.log(response);
                Swal.fire({
                    title: 'Login successful!',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '/CPMS/modules/contractors/contractor_dashboard.php';
                });
            }else{
                Swal.fire({
                    title: 'Login Failed!',
                    text: response.message,
                    icon: 'error',
                    timerProgressBar: true,
                    showConfirmButton: true
                });
            }
        },
        });
    });
});
