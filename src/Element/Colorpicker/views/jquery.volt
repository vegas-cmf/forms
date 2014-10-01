{% do assets.addCss('assets/vendor/mjaalnir-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') %}
{% do assets.addJs('assets/vendor/mjaalnir-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js') %}
{% do assets.addJs('assets/js/lib/vegas/ui/colorpicker.js') %}
<input type="text"{% for key, attribute in attributes %} {{ key }}="{{ attribute }}"{% endfor %} value="{{ value }}" vegas-colorpicker />