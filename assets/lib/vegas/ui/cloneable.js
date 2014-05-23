/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <arkadiusz.ostrycharz@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
(function($) {
    $.fn.vegasCloner = function(options) {
        var prepareField = function(element, rowCounter) {
            var preparedField = element.clone();

            preparedField.find('[name]').each(function() {
                var orginalName = $(this).attr('name');

                var nameArray = orginalName.split('[');
                var baseName = nameArray.shift();

                nameArray = nameArray.join('[');
                nameArray = nameArray.split(']');

                if (nameArray[0] !== '') {
                    nameArray[0] = parseInt(nameArray[0])+rowCounter;
                }

                var newName = baseName + '[' + nameArray.join(']');

                preparedField.find('[name="'+orginalName+'"]').each(function() {
                    $(this).attr('name',newName);
                });

                preparedField.find('[id="'+orginalName+'"]').each(function() {
                    $(this).attr('id',newName);
                });
            });

            return preparedField.show();
        };

        var defaults = {
            'buttons': {
                'add': {
                    'text': 'Add next',
                    'class': 'btn-form-submit'
                },
                'remove': {
                    'text': 'Remove last',
                    'class': 'btn-form-cancel'
                }
            },
            'row': {
                'selector': 'fieldset',
                'removeButton': $('<a>').html('x')
                    .attr('href','javascript:void(0);')
                    .addClass('cloner-row-remove')
            }
        };

        if (typeof options === 'undefined') {
            var options = {};
        }

        if (typeof options['buttons'] !== 'undefined') {
            options['buttons'] = $.extend({}, defaults['buttons'], options['buttons']);
        }  else {
            options['buttons'] = defaults['buttons'];
        }

        if (typeof options['row'] !== 'undefined') {
            options['row'] = $.extend({}, defaults['row'], options['row']);
        }  else {
            options['row'] = defaults['row'];
        }

        $(this).each(function() {
            var addBtn = $('<a>').html(options.buttons.add.text)
                .attr('href','javascript:void(0);')
                .addClass('cloner-add').addClass(options.buttons.add.class);

            var removeBtn = $('<a>').html(options.buttons.remove.text)
                .attr('href','javascript:void(0);')
                .addClass('cloner-remove').addClass(options.buttons.remove.class);

            var removeRowBtn = options.row.removeButton;

            var cloneContainer = $(this);
            var clonerBase = cloneContainer.find(options.row.selector+':eq(0)').clone();

            cloneContainer.find(options.row.selector+':eq(0)').remove();

            if (typeof options.buttons.insertAfterSelector === 'undefined') {
                options.buttons.insertAfterSelector = cloneContainer;
            }

            removeBtn.insertAfter(options.buttons.insertAfterSelector);
            addBtn.insertAfter(options.buttons.insertAfterSelector);

            var rowCounter = cloneContainer.children().length;

            addBtn.on('click',function() {
                var element = prepareField(clonerBase, rowCounter);

                removeRowBtn.clone(true).appendTo(element);
                element.appendTo(cloneContainer);

                cloneContainer.trigger('cloned');
                rowCounter++;
            });

            removeBtn.on('click',function() {
                if (cloneContainer.children().length > 1) {
                    cloneContainer.children().last().remove();
                    rowCounter--;
                } else {
                    cloneContainer.find('input, textarea, select').val('');
                }
            });

            removeRowBtn.on('click', function() {
                $(this).parent().remove();
                rowCounter--;
            });

            cloneContainer.find(options.row.selector).each(function() {
                if ($(this).index > 1) {
                    removeRowBtn.clone(true).appendTo($(this));
                }
            });
        });
    };
})(jQuery);

$(document).ready(function() {
    $('[vegas-cloneable]').vegasCloner();
});
