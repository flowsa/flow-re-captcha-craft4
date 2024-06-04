# FlowReCaptcha plugin for Craft CMS 4.x and 5.x

A re-captcha field type for validating forms

![Screenshot](resources/img/plugin-logo.png)

## Requirements

This plugin requires both Craft CMS 4.0.0-beta.23 or later and 5.0.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Add the following code to `composer.json`

        "repositories": {
                "google-map-embed": {
                    "type": "path",
                    "url": "./plugins/google-map-embed"
                },
        }

3. Then tell Composer to load the plugin:

        composer require /flow-re-captcha

4. In the Control Panel, go to Settings → Plugins and click the “Install” button for FlowReCaptcha.

## FlowReCaptcha Overview

-Insert text here-

## Configuring FlowReCaptcha

Disable recaptcha submitting the form after google recaptcha response

add attribute `data-recaptcha-submit="false"` on the form element

## Using FlowReCaptcha

-Insert text here-

## FlowReCaptcha Roadmap

Some things to do, and ideas for potential features:

* Release it

Brought to you by [Flow Communications](https://www.flowsa.com)
