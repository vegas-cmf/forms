/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <aostrycharz@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
(function($) {
    $.fn.vegasCloner = function(customOptions) {
        self.prepareField = function(element, rowCounter) {
            var preparedField = element.clone(true,true);

            preparedField.find('[name]').each(function() {
                var orginalName = $(this).attr('name');
                var orginalId = $(this).attr('id');

                var nameArray = orginalName.split('[');
                var baseName = nameArray.shift();

                nameArray = nameArray.join('[');
                nameArray = nameArray.split(']');

                if (nameArray[0] !== '') {
                    nameArray[0] = parseInt(nameArray[0])+rowCounter;
                }

                if(orginalId) {
                    var idValueArr = orginalId.split(']');
                    var idValue = idValueArr.slice(-1).pop();
                    var newNameValue = baseName + '[' + nameArray.join(']') + idValue;
                }

                var newName = baseName + '[' + nameArray.join(']');

                preparedField.find('[name="'+orginalName+'"]').each(function() {
                    $(this).attr('name',newName);
                });

                preparedField.find('[id="'+orginalName+'"]').each(function() {
                    $(this).attr('id',newName);
                });

                preparedField.find('[id="'+orginalId+'"]').each(function() {
                    $(this).attr('id',newNameValue);
                });

                preparedField.find('[for="'+orginalId+'"]').each(function() {
                    $(this).attr('for',newNameValue);
                });
            });
            $('.cloner-remove').show();
            return preparedField.show();
        };

        self.sortable = function(cloneContainer, options) {
            if ($.fn.sortable && options.sortable.active) {
                cloneContainer.sortable({
                    forcePlaceholderSize: true,
                    items: ':visible'
                }).bind('sortupdate', options.sortable.callback);
            }
        };

        $(this).each(function() {
            var options = $.extend({
                'buttons': {
                    'add': {
                        'text': 'Add next',
                        'class': 'btn-form-submit'
                    },
                    'remove': {
                        'text': 'Remove last',
                        'class': 'btn-form-cancel',
                        'alertText': 'Do you want to remove this item?'
                    }
                },
                'row': {
                    'selector': 'fieldset',
                    'removeButton': $('<a>').html('x')
                        .attr('href','javascript:void(0);')
                        .addClass('cloner-row-remove')
                },
                'sortable': {
                    'active': false,
                    'callback': function() {
                        var orderIndicator = 0;

                        $(options.row.selector).each(function() {
                            $(this).find(':input').each(function() {
                                var matches = $(this).attr('name').match(/([^\[]*\[)(\d+)(\].*)/);

                                if (matches) {
                                    $(this).attr('name', matches[1]+orderIndicator+matches[3]);
                                }
                            });

                            orderIndicator++;
                        });
                    }
                }
            }, customOptions);

            var addBtn = $('<a>').html(options.buttons.add.text)
                .attr('href','javascript:void(0);')
                .addClass('cloner-add').addClass(options.buttons.add.class);

            var removeBtn = $('<a>').html(options.buttons.remove.text)
                .attr('href','javascript:void(0);')
                .addClass('cloner-remove').addClass(options.buttons.remove.class);

            var removeRowBtn = options.row.removeButton;

            var cloneContainer = $(this);
            if (typeof options.buttons.insertAfterSelector === 'undefined') {
                options.buttons.insertAfterSelector = cloneContainer;
            }

            removeBtn.insertAfter(options.buttons.insertAfterSelector).hide();
            addBtn.insertAfter(options.buttons.insertAfterSelector);

            var clonerBase = cloneContainer.find(options.row.selector+':eq(0)').clone(true,true);

            cloneContainer.find(options.row.selector+':eq(0)').remove();

            var rowCounter = cloneContainer.children().length;

            var hideRemoveBtn = function(){
                if(cloneContainer.children().length == 1) {
                    $('.cloner-remove').hide();
                }
            };

            addBtn.on('click',function() {
                var element = self.prepareField(clonerBase, rowCounter);

                removeRowBtn.clone(true).appendTo(element);
                element.appendTo(cloneContainer);

                cloneContainer.trigger('cloned');
                rowCounter++;
            });

            var alertMsg = options.buttons.remove.alertText;

            var removeBtnAction = function() {
                if (cloneContainer.children().length > 1) {
                    cloneContainer.children().last().remove();
                    rowCounter--;
                } else {
                    cloneContainer.find('input, textarea, select').val('');
                }
            };

            removeBtn.on('click',function() {
                alertMsg ? confirm(alertMsg) ? removeBtnAction() : false : removeBtnAction();

                hideRemoveBtn();
            });

            var removeRowAction = function(context) {
                if (cloneContainer.children().length > 1) {
                    context.parent().remove();
                    rowCounter--;
                } else {
                    cloneContainer.find('input, textarea, select').val('');
                }
            };

            removeRowBtn.on('click', function() {
                alertMsg ? confirm(alertMsg) ? removeRowAction($(this)) : false : removeRowAction($(this));

                hideRemoveBtn();
            });

            cloneContainer.find(options.row.selector).each(function() {
                removeRowBtn.clone(true).appendTo($(this));
            });

            $('#' + cloneContainer[0].id + ' ' + options.row.selector + ':first-of-type .cloner-row-remove').css('display', 'none');

            self.sortable(cloneContainer, options);

            cloneContainer.on('cloned', function() {
                self.sortable(cloneContainer, options);
            });
        });
    };
})(jQuery);

$(document).ready(function() {
    $('[vegas-cloneable]').vegasCloner();
});
