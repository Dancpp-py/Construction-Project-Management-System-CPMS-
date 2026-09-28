$(document).ready(function(){
    $('#sidebarToggle').on('click', function(){
        $('#sidebar').toggleClass('active');
        $('.sidebar-overlay').toggleClass('active');
    });
}); 

