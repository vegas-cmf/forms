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
    var editorConfig = {
        enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        htmlEncodeOutput: false,
        entities: false,
        filebrowserBrowseUrl : '/assets/html/ui/elfinder.html',
        filebrowserWindowWidth : '740',
        filebrowserWindowHeight : '410',
        toolbar: [
            [ 'Bold', 'Italic', 'Underline', 'Format', 'NumberedList', 'BulletedList', 'PasteText', 'Link', 'Unlink', 'Image', 'Flash', 'Source' ]
        ]
    };

    $('[vegas-richtext]').ckeditor(editorConfig);
    
    $('[vegas-cloneable]').on('cloned', function() {
        $('[vegas-richtext]').ckeditor(editorConfig);
    });
});
