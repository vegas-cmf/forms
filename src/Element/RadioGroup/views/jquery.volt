{% set hasErrors = form.hasMessagesFor(element.getName()) %}
<div class="clearfix form-group{% if hasErrors %} has-error{% endif %}">
    {% if hasErrors %}
        <span class="help-block">
            {% for error in form.getMessagesFor(element.getName()) %}
                {{ error }}
            {% endfor %}
        </span>
    {% endif %}

    {% for radio in element.getElements() %}
    <span style="margin-right: 30px;">
        {{ radio.renderDecorated() }}
        <label for="{{ radio.getName() }}">{{ radio.getAttribute('value') }}</label>
    </span>
    {% endfor %}
</div>