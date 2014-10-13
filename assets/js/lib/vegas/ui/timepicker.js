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
$(document).ready(function() {
    var timepickerOptions = {
        pickDate: false,
        language: 'nl',
        minuteStepping: 15,
        pick12HourFormat: false,
        defaultDate:"",
        useCurrent: false
    };

    var render = function() {
        $('[vegas-timepicker]').datetimepicker(timepickerOptions);
    };

    render();

    $('[vegas-cloneable]').on('cloned', function() {
        render();
    });
});
