require([
    'jquery',
    'Magento_Customer/js/customer-data',
    'mage/cookies'
], function ($, customerData) {
    'use strict';
    $('.qtyplus').on('click', function (e) {
        let qtyInputBox = $(this).parents('form').find('input[name="qty"]');
        let currentVal = parseInt(qtyInputBox.val());
        if (!isNaN(currentVal)) {
            currentVal++;
            qtyInputBox.val(currentVal);
        } else {
            currentVal = 1;
            qtyInputBox.val(currentVal);
        }
    });

    $('.qtyminus').on('click', function (e) {
        e.preventDefault();
        let qtyInputBox = $(this).parents('form').find('input[name="qty"]');
        let currentVal = parseInt(qtyInputBox.val());
        if (currentVal > 1) {
            if (!isNaN(currentVal) && currentVal > 0) {
                qtyInputBox.val(currentVal - 1);
            } else {
                qtyInputBox.val(0);
            }
        }
    });
    $('.add-to-cart').on('click', function (form) {
        form.preventDefault();
        $(this).css("opacity", "0.7");
        let formData = {
            qty: jQuery(this).parents('form').find('input[name="qty"]').val(),
            product: jQuery(this).parents('form').find('input[name="qty"]').data("id"),
            name: jQuery(this).parents('form').find('input[name="qty"]').data("name"),
            form_key: jQuery.mage.cookies.get('form_key')
        };
        $.ajax({
            type: "POST",
            url: '/checkout/cart/add',
            data: formData,
            dataType: "json",
            success: function (res) {
                $(this).css("opacity", "1");
                var productName = '<span class="prod-name" style="color: red">'+formData.name+'</span>';
                var successMessage = $.mage.__('You added %1 to your shopping cart.').replace('%1', productName);
                var popup = $('<div class="add-to-cart-modal-popup"/>').html('<span>'+successMessage+'</span>').modal({
                    modalClass: 'add-to-cart-popup cart-left-popup',
                    buttons: [
                        {
                            text: $.mage.__('Keep Shopping') + ' <span id="countdown"></span>',
                            click: function () {
                                this.closeModal();
                                $('.mfp-close').trigger('click');
                            }
                        },
                        {
                            text: $.mage.__('Goto Shopping Cart'),
                            click: function () {
                                var cartURL = window.cartURL;
                                window.location = cartURL;
                            }
                        },
                        {
                            text: $.mage.__('Checkout'),
                            click: function () {
                                window.location = window.checkout.checkoutUrl
                            }
                        }
                    ]
                });
                popup.modal('openModal');

                var counter = 3;
                var interval = setInterval(function(){
                    counter--;
                    if (counter <= 0) {
                        clearInterval(interval);
                        $('#countdown').remove();
                        popup.modal('closeModal');
                        $('.mfp-close').trigger('click');
                        return;
                    } else {
                        $('#countdown').text("("+counter+")");
                    }
                }, 1000);
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
