{% do assets.addJs('assets/js/lib/vegas/ui/browser.js') %}
<div class="input-group browser-wrapper">
    <input type="text"{% for key, attribute in attributes %} {{ key }}="{{ attribute }}"{% endfor %} value="{{ value }}" vegas-browser />
    <div class="input-group-btn">
        <a class="btn btn-primary btn-browse">Browse</a>
    </div>
</div>