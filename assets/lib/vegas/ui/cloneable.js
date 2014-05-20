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
        
        var options = options || {
            'textAdd': 'Add next',
            'textRemove': 'Remove last',
            'classAdd': 'btn-form-submit',
            'classRemove': 'btn-form-cancel'
        };
        
        $(this).each(function() { 
            var addBtn = $('<a>').html(options.textAdd)
                    .attr('href','javascript:void(0);')
                    .addClass('cloner-add').addClass(options.classAdd);
            
            var removeBtn = $('<a>').html(options.textRemove)
                    .attr('href','javascript:void(0);')
                    .addClass('cloner-remove').addClass(options.classRemove);

            var removeRowBtn = $('<a>').html('x')
                    .attr('href','javascript:void(0);')
                    .addClass('cloner-row-remove');

            var cloneContainer = $(this);
            var clonerBase = cloneContainer.find('fieldset:eq(0)').clone();

            cloneContainer.find('fieldset:eq(0)').remove();

            removeBtn.insertAfter(cloneContainer);
            addBtn.insertAfter(cloneContainer);
            
            var rowCounter = cloneContainer.children().length;
            
            addBtn.on('click',function() {
                var element = prepareField(clonerBase, rowCounter);

                removeRowBtn.clone(true).prependTo(element);
                element.appendTo(cloneContainer);

                cloneContainer.trigger('cloned');
                rowCounter++;
            });
            
            removeBtn.on('click',function() {
                if (cloneContainer.children().length > 1) {
                    cloneContainer.children().last().remove(); 
                    rowCounter--;
                } else {
                    $(this).parent().find('input, textarea, select').val('');
                }
            });

            removeRowBtn.on('click', function() {
                $(this).parent().remove();
                rowCounter--;
            });

            cloneContainer.find('fieldset').each(function() {
                if ($(this).index > 1) {
                    removeRowBtn.clone(true).prependTo($(this));
                }
            });
        });
    };
})(jQuery);

$(document).ready(function() {
    $('[vegas-cloneable]').vegasCloner();
});
