/*eslint-disable */

/* jscs:disable */

function _inheritsLoose(subClass, superClass) {
    subClass.prototype = Object.create(superClass.prototype);
    subClass.prototype.constructor = subClass;
    _setPrototypeOf(subClass, superClass);
}

function _setPrototypeOf(o, p) {
    _setPrototypeOf =
        Object.setPrototypeOf ||
        function _setPrototypeOf(o, p) {
            o.__proto__ = p;
            return o;
        };
    return _setPrototypeOf(o, p);
}

define([
    "Magento_PageBuilder/js/mass-converter/widget-directive-abstract",
    "Magento_PageBuilder/js/utils/object",
    "underscore",
], function (_widgetDirectiveAbstract, _object, _underscore) {
    /**
     * Enables the settings of the content type to be stored as a widget directive.
     *
     * @api
     */
    var WidgetDirective = /*#__PURE__*/ (function (_widgetDirectiveAbstr) {
        "use strict";

        _inheritsLoose(WidgetDirective, _widgetDirectiveAbstr);

        function WidgetDirective() {
            return _widgetDirectiveAbstr.apply(this, arguments) || this;
        }

        var _proto = WidgetDirective.prototype;

        /**
         * Convert value to internal format
         *
         * @param {object} data
         * @param {object} config
         * @returns {object}
         */
        _proto.fromDom = function fromDom(data, config) {
            var attributes = _widgetDirectiveAbstr.prototype.fromDom.call(
                this,
                data,
                config
            );
            data.title = attributes.title;
            data.sub_title = attributes.sub_title;
            data.first_label = attributes.first_label;
            data.first_description = attributes.first_description;
            data.second_label = attributes.second_label;
            data.second_description = attributes.second_description;
            if (attributes.first_video && attributes.first_video != "") {
                data.first_video = JSON.parse(this.decodeWysiwygCharacters(attributes.first_video));
            }
            if (attributes.second_video && attributes.second_video != "") {
                data.second_video = JSON.parse(this.decodeWysiwygCharacters(attributes.second_video));
            }

            return data;
        };
        /**
         * Convert value to knockout format
         *
         * @param {object} data
         * @param {object} config
         * @returns {object}
         */

        _proto.toDom = function toDom(data, config) {

            var attributes = {
                type: "VaxLtd\\PageBuilderDualVideo\\Block\\Widget",
                template: "VaxLtd_PageBuilderDualVideo::widget.phtml",
                type_name: "PageBuilder Dual Video Widget",
                title: data.title,
                sub_title: data.sub_title,
                first_label: data.first_label,
                first_description: data.first_description,
                second_label: data.second_label,
                second_description: data.second_description,
                first_video: this.encodeWysiwygCharacters(JSON.stringify(data.first_video)),
                second_video: this.encodeWysiwygCharacters(JSON.stringify(data.second_video))
            };

            (0, _object.set)(
                data,
                config.html_variable,
                this.buildDirective(attributes)
            );
            return data;
        };

        /**
         * @param {string} content
         * @returns {string}
         */
        ;

        _proto.encodeWysiwygCharacters = function encodeWysiwygCharacters(content) {
            return content.replace(/"/g, "`").replace(/\\/g, "|").replace(/</g, "&lt;").replace(/>/g, "&gt;");
        }
        /**
         * @param {string} content
         * @returns {string}
         */
        ;

        _proto.decodeWysiwygCharacters = function decodeWysiwygCharacters(content) {
            if (!content || (Array.isArray(content) && content.length === 0) || (typeof content === 'string' && content === '')) {
                return "";
            }

            return content.replace(/`/g, "\"").replace(/\|/g, "\\").replace(/&lt;/g, "<").replace(/&gt;/g, ">");
        };

        return WidgetDirective;
    })(_widgetDirectiveAbstract);

    return WidgetDirective;
});
