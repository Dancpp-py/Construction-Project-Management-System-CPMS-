$(document).ready(function(){
    $('#contractorRegisterForm').on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
        url: "/CPMS/auth/create_contractor.php",
        type: "POST",
        data: $(this).serialize(),
        dataType:"json",
        
        success: function(response){
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
