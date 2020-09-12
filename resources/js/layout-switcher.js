function gridView() {
    $('.grid-icon').on('click', function () {
        $('.all-products').removeClass('list-view').addClass('grid-view');
        $('.grid-icon').removeClass('disabled').addClass('active');
        $('.list-icon').removeClass('active').addClass('disabled');
    });
}

function listView() {
    $('.list-icon').on('click', function () {
        $('.all-products').removeClass('grid-view').addClass('list-view');
        $('.list-icon').removeClass('disabled').addClass('active');
        $('.grid-icon').removeClass('active').addClass('disabled');
    });
}
