<h1 align="center">Docono Cookie Consent</h1>

## setup

define in the config the consents

```
dcc:
    consents:
        - analytics
        - youtube
        - my-consent
```

include the js:

```html
{% do pimcore_inline_script().appendFile(asset(dcc().jsFile())) %}
```

or

```html
{{ dcc().jsScript()|raw }}
```

---
there is also a basic styling if needed

```html
{% do pimcore_head_link().appendStylesheet(dcc().cssFile()) %}
```

css vars:
```
  --dcc-clr-white: #FBFAF5;
  --dcc-clr-black: #2A363B;
  --dcc-clr-green: #99B898;

  --dcc-body-w: 50rem;
  --dcc-gap: 2rem;
  --dcc-my: 3rem;
  --dcc-mx: auto;

  --dcc-yt-width: 50rem;
  --dcc-yt-ratio: 16/9;

  --dcc-switch-size: 1.5rem;
```

## consent dialog

Create your consent dialog whatever you like it.
Just ensure:
- the main container has the id "dc-consent"
- the "accept essential" button has the id "accept-essential"
- the "accept selected consents" button has the id "accept-selected"
- the name of the checkbox response with the defined consent

```html
{% do dcc().dialog() %}
<div id="dc-consent" data-ttl="48">
    <div class="dcc-body">
        <h3>{{ 'dcc.dialog.title'|trans }}</h3>
        <p>
            {{ ('dcc.dialog.text'|trans)|raw }}
        </p>

        <div class="dcc-consents">
            <label>
                <div class="dcc-slider">
                    <input type="checkbox" name="essential" checked disabled>
                    <div class="slider"></div>
                </div>

                {{ ('dcc.consent.essential')|trans }}
            </label>

            {% for permission in dcc().permissionList() %}
                <label>
                    <div class="dcc-slider">
                        <input type="checkbox" checked name="{{ permission }}">
                        <div class="slider"></div>
                    </div>

                    {{ ('dcc.consent.' ~ permission)|trans }}
                </label>
            {% endfor %}
        </div>


        <div class="dcc-actions">
            <button id="accept-essential" class="dcc-button -inverted">{{ 'dcc.button.essential'|trans }}</button>
            <button id="accept-selected" class="dcc-button -selected">{{ 'dcc.button.selected'|trans }}</button>
        </div>
    </div>
</div>
{{ dcc().endDialog()|raw }}
```

## JavaScript

To prevent any JavaScript from execution, such as google analytics, use the JS functionality:
Set the "data-consent" to the required consent name.

```html
{% do dcc().js().start() %}
<script
        data-consent="analytics"
        src="https://www.googletagmanager.com/gtag/js?id=UA-xxxxx"
        async
></script>
{{ dcc().js().end()|raw }}
```
The "src" attribute will be replaced with "data-src".
Is the consent given, the script will be loaded.

or

```html
{% do dcc().js().start() %}
<script data-consent="analytics" type="text/javascript">
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments)
    }

    gtag('consent', 'default', {ad_storage: 'denied', analytics_storage: 'denied'});
    gtag('set', 'ads_data_redaction', true);
    gtag('set', 'url_passthrough', true);
    gtag('js', new Date());
    gtag('config', 'xxxx');
</script>
{{ dcc().js().end()|raw }}
```

The script "type" attribute value will be replaced with "text/plain", which prevents the script of being executed.
If the consent is given, the script tag will be executed.

## Youtube
With the youtube helper, you ensure that no call will be made to youtube from the client browser.
If the the consent "youtube" is not accepted, a placeholder with a base64 thumbnail will be shown.
Is the thumbnail clicked, a consent prompt for that video will be shown.

### styling
- container: .dcc-youtube
- play icon: .dcc-youtube__play
- consent prompt: .dcc-youtube__consent
- consent prompt buttons: .consent-youtube__decline, .consent-youtube__accept

### translations
- dcc.youtube.message
- dcc.youtube.decline
- dcc.youtube.accept


```
{{ dcc().youtube('https://www.youtube-nocookie.com/embed/xxxxxx?controls=0')|raw }}
```

or get html tag with ```getHtml(thumbnailQuality='high', attributes=[])```

```
{{ dcc().youtube('https://www.youtube-nocookie.com/embed/xxxxxx?controls=0').getHtml('low', {role: 'video'})|raw }}
```

## Slots

If you want to show any content only if the consent is accepted, then there are slots

```html
<% do dcc().slot('mySlot').start('requiredConsent') %}
<div>
    content if 'requiredConsent' is accepted
</div>
{{ dcc().slot('mySlot').end()|raw }}}
```

## cookie blocker

There is a cookie blocker integrated, which blocks all cookies if the name is not white listed.
To block all cookies, the js need to be included inline to ensure it is executes first.

```html
{{ dcc().jsScript()|raw }}
```

Activate the blocker and register a cookie-namespace with all the cookie names

```html
<script>
    window.dccBlock = true
    window.dccData = window.dccData || []

    function dcc() {
        window.dccData.push(arguments)
    }

    dcc('my-cookie-namespace', ['cookie-name-one', 'cookie-name-two'])
</script>


Bind the cookie-namespace to the consent
```html
<input type="checkbox" checked name="my-consent" data-dcc-cn="my-cookie-namespace">
```

There are also predefined cookie-namespaces with all the necessary cookie names:
- matomo
- google-analytics
- google-tag-manager
- google-ad-sense

