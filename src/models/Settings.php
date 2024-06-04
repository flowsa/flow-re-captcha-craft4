<?php
/**
 * FlowReCaptcha plugin for Craft CMS 3.x
 *
 * A re-captcha field type for validating forms
 *
 * @link      https://www.flowsa.com
 * @copyright Copyright (c) 2018 Flow Communications
 */

namespace flowsa\flowrecaptcha\models;

use flowsa\flowrecaptcha\FlowReCaptcha;

use Craft;
use craft\base\Model;

/**
 * @author    Flow Communications
 * @package   FlowReCaptcha
 * @since     0.0.1
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * honeypotParam model attribute
     *
     * @var string
     */
    public $honeypotParam = 'WesbiteName';
    /**
     * Site key model attribute
     *
     * @var string
     */
    public $siteKey = '';

    /**
     * Secret key model attribute
     *
     * @var string
     */
    public $secretKey = '';


    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            ['honeypotParam', 'string'],
            ['siteKey', 'string'],
            ['secretKey', 'string'],
            [['honeypotParam'], 'required']
        ];
    }
}
