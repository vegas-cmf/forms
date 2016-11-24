{% set elementName = element.getName() %}
<input type="hidden" name="{{ elementName }}" value="{{ element.getDefault() is null ? 0 : element.getDefault() }}" />
{{ element }}