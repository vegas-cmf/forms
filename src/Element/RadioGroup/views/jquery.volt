<div class="clearfix form-group">
    {% for radio in element.getElements() %}
    <span style="margin-right: 30px;">
        {{ radio.renderDecorated() }}
        <label for="{{ radio.getName() }}">{{ radio.getAttribute('value') }}</label>
    </span>
    {% endfor %}
</div>