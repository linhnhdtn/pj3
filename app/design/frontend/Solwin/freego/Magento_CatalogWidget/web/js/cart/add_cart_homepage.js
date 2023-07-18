require(['jquery', 'Magento_Customer/js/customer-data', 'mage/cookies'], function ($, customerData) {
    'use strict';
    $(window).load(function () {
        $('.add-to-cart').on('click', function (form) {
            form.preventDefault();
            console.log(1234);
            jQuery(this).css("opacity", "0.7");
            let formData = {
                qty: jQuery(this).parents('form').find('input[name="qty"]').val(),
                product: jQuery(this).parents('form').find('input[name="qty"]').data("id"),
                form_key: jQuery.mage.cookies.get('form_key')
            };
            $.ajax({
                type: "POST",
                url: '/checkout/cart/add',
                data: formData,
                dataType: "json",
                success: function (res) {
                    jQuery('.add-to-cart').css("opacity", "1");
                    reloadCart();
                }
            });
        });

        function reloadCart() {
            let sections = ['cart'];
            customerData.invalidate(sections);
            customerData.reload(sections, true);
        }
    });
});

