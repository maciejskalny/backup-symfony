$(document).ready(function () {
    $('#display-type').click(function () {
        let choice = $('#display-value').val();

        if(choice == 2) {
            $('.product').removeClass('col-12');
            $('.product').addClass('col-4');
        } else {
            $('.product').removeClass('col-4');
            $('.product').addClass('col-12');
        }
    });
});