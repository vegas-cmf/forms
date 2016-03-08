{% do assets.addCss('assets/vendor/eonasdan-bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') %}
{% do assets.addJs('assets/vendor/moment/moment.js') %}
{% do assets.addJs('assets/vendor/eonasdan-bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') %}
{% do assets.addJs('assets/vendor/eonasdan-bootstrap-datetimepicker/locales/bootstrap-datetimepicker.nl.js') %}
{% do assets.addJs('assets/js/lib/vegas/ui/timepicker.js') %}
<input type="text"{% for key, attribute in attributes %} {{ key }}="{{ attribute }}"{% endfor %} value="{{ value }}" vegas-timepicker />