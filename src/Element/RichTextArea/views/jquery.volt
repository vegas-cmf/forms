{% do assets.addJs('assets/vendor/ckeditor/ckeditor.js') %}
{% do assets.addJs('assets/vendor/ckeditor/adapters/jquery.js') %}
{% do assets.addJs('assets/js/lib/vegas/ui/richtext.js') %}
<textarea{% for key, attribute in attributes %} {{ key }}="{{ attribute }}"{% endfor %} vegas-richtext>
{{ value }}
</textarea>