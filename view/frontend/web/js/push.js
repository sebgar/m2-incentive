define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('mage.incentive_push', {
        options: {},

        _create: function () {
            var _self = this;
            var max = $(this.options.elements.container_list).length;
            $(this.options.elements.pushs).each(function (index, element) {
                element = $(element);
                var position = parseInt(element.data('position'));
                if (position <= max) {
                    $(_self.options.elements.container_list).each(function (index, item) {
                        if (index + 1 === position) {
                            $(item).before('<' + _self.options.item.htmlTag +' class="'+_self.options.item.classes+'">'+element.html()+'</'+ _self.options.item.htmlTag +'>');
                        }
                    });
                }

                element.remove();
            });
        }
    });

    return $.mage.incentive_push;
});
