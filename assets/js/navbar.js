$(document).ready(function(){
    $('.swal-logout').on('click', function(e){
        e.preventDefault();
        
        Swal.fire({
            title: "Logout?",
            text: "Are you sure you want to logout?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3b6ba5",
            cancelButtonColor: "#e03e2f",
            confirmButtonText: "Logout!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/CPMS/auth/logout.php';
            }
        });
    });
});
    
