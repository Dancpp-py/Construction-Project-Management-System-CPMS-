$(document).ready(function(){
    $('#registerForm').on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
        url: "/CPMS/auth/create.php",
        type: "POST",
        data: $(this).serialize(),
        dataType:"json",
        
        success: function(response){
            console.log("Success:", response);
            if(response.status === 'success') {
                Swal.fire({
                    title: 'Registered!',
                    text: response.message,
                    icon: 'success'
                });
            } else {
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


