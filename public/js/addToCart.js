$(document).ready(function () {
    $('.check-availability').on('click', function () {
        const container = $(this).parent().find('.quantity'), productId = container.data('product-id');
        const quantity = parseInt(container.val());

        checkAvailability(productId, quantity);
    });
});

function checkAvailability (productId, quantity)
{
    // Generate the route.
    const url = Routing.generate('app_product_check', {'id': productId});
    $.ajax({
        url: url,
        method: 'GET',
        data: {
            qty: quantity
        },
        success: function (data) {
            $('#alert').html(data);
        }
    });
}