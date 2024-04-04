define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/url-builder',
    'mage/storage',
    'Magento_Checkout/js/model/error-processor',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/model/full-screen-loader'
], function ($, wrapper, quote, urlBuilder, storage, errorProcessor, customer, fullScreenLoader) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction) {
            var comentarios = $('[name="customCheckoutForm[comentarios]"]').val();
            var sexo = $('[name="customCheckoutForm[sexo]"]').val();

            if (!comentarios || comentarios.trim() === '') {
                errorProcessor.process({
                    responseText: JSON.stringify({
                        message: 'Por favor, ingrese un comentario.'
                    })
                });
                return false;
            }

            if (!sexo) {
                errorProcessor.process({
                    responseText: JSON.stringify({
                        message: 'Por favor, seleccione un sexo.'
                    })
                });
                return false;
            }

            return originalAction();
        });
    };
});
