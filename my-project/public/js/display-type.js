// $(document).ready(function () {
//     $('#display-type').click(function () {
//         let choice = $('#display-value').val();
//
//         if(choice == 2) {
//             $('.product').removeClass('col-6');
//             $('.product').addClass('col-4');
//         } else {
//             $('.product').removeClass('col-4');
//             $('.product').addClass('col-6');
//         }
//     });
// });

$(document).ready(function () {
    $('.display-type').click(function () {

        var lastClass = $('.product').attr('class').split(' ').pop();
        $(this).removeClass(lastClass);

        let choice = $(this).attr('id');

        if(choice == 1) {
            $('.product').removeClass('col-6');
            $('.product').removeClass('col-4');
            $('.product').addClass('col-12');
        } else if (choice == 2) {
            $('.product').removeClass('col-12');
            $('.product').removeClass('col-4');
            $('.product').addClass('col-6');
        } else {
            $('.product').removeClass('col-6');
            $('.product').removeClass('col-12');
            $('.product').addClass('col-4');
        }

    });
});