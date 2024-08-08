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

        data.desktop_image = JSON.parse(this.decodeWysiwygCharacters(attributes.desktop_image));
        data.mobile_image = JSON.parse(this.decodeWysiwygCharacters(attributes.mobile_image));
        data.video = attributes.video;
        data.video_link = attributes.video_link;
        data.title = attributes.title;
        data.description = attributes.description;
        data.cta_link = attributes.cta_link;
        data.learn_more_link = attributes.learn_more_link;
      if (attributes.items && attributes.items != "") {
        data.items = JSON.parse(this.decodeWysiwygCharacters(attributes.items));
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
      if (!data.items || !data.items.length) {
        return data;
      }

      // sort items by position
      data.items.sort((propOne, propTwo) => ~~propOne.position - ~~propTwo.position);

        var attributes = {
            type: "VaxLtd\\PageBuilderHomepageCarousel\\Block\\Widget",
            template: "VaxLtd_PageBuilderHomepageCarousel::widget.phtml",
            type_name: "PageBuilder Homepage Carousel",
            items: this.encodeWysiwygCharacters(JSON.stringify(data.items)),
            desktop_image: this.encodeWysiwygCharacters(JSON.stringify(data.desktop_image)),
            mobile_image: this.encodeWysiwygCharacters(JSON.stringify(data.mobile_image)),
            video: data.video,
            video_link: data.video_link,
            title: data.title,
            description: data.description,
            cta_link: data.cta_link,
            learn_more_link: data.learn_more_link
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
