{% do assets.addCss('assets/vendor/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') %}
{% do assets.addJs('assets/vendor/moment/min/moment.min.js') %}
{% do assets.addJs('assets/vendor/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') %}
{% do assets.addJs('assets/js/lib/vegas/ui/datepicker.js') %}
<input type="text"{% for key, attribute in attributes %} {{ key }}="{{ attribute }}"{% endfor %} value="{{ value }}" vegas-datepicker />