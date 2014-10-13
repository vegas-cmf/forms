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
    var render = function(){
        $('[vegas-colorpicker]').colorpicker();
    };

    render();

    $('[vegas-cloneable]').on('cloned', function() {
        render();
    });
});
