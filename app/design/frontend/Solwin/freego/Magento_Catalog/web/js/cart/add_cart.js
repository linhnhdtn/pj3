require([
    'jquery'
], function ($) {
    'use strict';
    $(window).load(function () {
        $('.qtyplus').on('click', function (e) {
            e.preventDefault();
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
    });
});
