{% if element.getUserOption('sortable',false) %}
    {% do assets.addJs('assets/vendor/html5sortable/jquery.sortable.js') %}
{% endif %}
{% do assets.addJs('assets/js/lib/vegas/ui/cloneable.js') %}
{% do assets.addCss('assets/css/common/cloneable.css') %}
<div{% for key, attribute in attributes %}{% if key !== 'name' %} {{ key }}="{{ attribute }}"{% endif %}{% endfor %} vegas-cloneable>
{% for row in element.getRows() %}
    <fieldset>
    {% for element in row.getElements() %}
    {{ element.renderDecorated() }}

    {% endfor %}
</fieldset>
{% endfor %}
</div>
<div class="clearfix"></div>