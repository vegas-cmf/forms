{% do assets.addJs('assets/vendor/multiselect/js/jquery.multi-select.js') %}
{% do assets.addJs('assets/js/lib/vegas/ui/multiselect.js') %}
{% do assets.addCss('assets/vendor/multiselect/css/multi-select.css') %}
<input type="hidden" name="{{ element.getName() }}[]" />
<select{% for key, attribute in attributes %} {{ key }}="{{ attribute }}"{% endfor %} multiple="multiple" data-vegas-multiselect>
{% for key, name in element.getOptions() %}
    <option value="{{ key }}"<?php if (!empty($value) && in_array($key, $value)): ?>selected="selected"<?php endif; ?>>{{ name }}</option>
{% endfor %}
</select>