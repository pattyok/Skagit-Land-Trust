/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/plugins_editor.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/plugins/index.js":
/*!******************************!*\
  !*** ./src/plugins/index.js ***!
  \******************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _styles_editor_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./styles.editor.scss */ "./src/plugins/styles.editor.scss");
/* harmony import */ var _styles_editor_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_styles_editor_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _settings__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./settings */ "./src/plugins/settings.js");
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/plugins */ "@wordpress/plugins");
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_3__);
/**
 * Internal dependencies
 */


/**
 * WordPress dependencies
 */



Object(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_2__["registerPlugin"])("carkeek-blocks-pageheader", {
  icon: false,
  render: _settings__WEBPACK_IMPORTED_MODULE_1__["default"]
});
Object(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_3__["registerBlockCollection"])("carkeek-blocks", {
  title: "Carkeek Blocks",
  icon: "wordpress"
});

/***/ }),

/***/ "./src/plugins/settings.js":
/*!*********************************!*\
  !*** ./src/plugins/settings.js ***!
  \*********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/edit-post */ "@wordpress/edit-post");
/* harmony import */ var _wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__);
var _jsxFileName = "/Users/pattyohara/Sites/lazar-landscape/app/public/wp-content/mu-plugins/carkeek-blocks/src/plugins/settings.js";

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }









var PageHeaderSettings = /*#__PURE__*/function (_Component) {
  _inherits(PageHeaderSettings, _Component);

  var _super = _createSuper(PageHeaderSettings);

  function PageHeaderSettings() {
    var _this;

    _classCallCheck(this, PageHeaderSettings);

    _this = _super.apply(this, arguments);
    _this.initialize = _this.initialize.bind(_assertThisInitialized(_this));
    return _this;
  }

  _createClass(PageHeaderSettings, [{
    key: "componentDidMount",
    value: function componentDidMount() {
      this.initialize();
    }
  }, {
    key: "componentDidUpdate",
    value: function componentDidUpdate() {
      this.initialize();
    }
  }, {
    key: "initialize",
    value: function initialize() {
      var postmeta = this.props.postmeta;
      var titleBlock = document.querySelector(".editor-post-title__block");

      if (titleBlock) {
        var isTitleHidden = typeof postmeta !== 'undefined' && typeof postmeta._carkeekblocks_title_hidden !== 'undefined' ? postmeta._carkeekblocks_title_hidden : false;
        var bodyClass = isTitleHidden ? "carkeek-blocks-title-hidden" : "carkeek-blocks-title-visible"; //remove existing class

        if (isTitleHidden) {
          document.body.classList.remove("carkeek-blocks-title-visible");
        } else {
          document.body.classList.remove("carkeek-blocks-title-hidden");
        }

        document.body.classList.add(bodyClass);
      }
    }
  }, {
    key: "render",
    value: function render() {
      var _this$props = this.props,
          onToggleTitle = _this$props.onToggleTitle,
          onToggleImage = _this$props.onToggleImage,
          onbylineChange = _this$props.onbylineChange,
          onColorChange = _this$props.onColorChange,
          postmeta = _this$props.postmeta,
          posttype = _this$props.posttype,
          featuredImage = _this$props.featuredImage,
          colors = _this$props.colors;

      if (['wp_block'].includes(posttype)) {
        return false;
      }

      var byLineField;

      if (['post'].includes(posttype) && typeof postmeta !== 'undefined') {
        var byline = typeof postmeta.byline !== 'undefined' ? postmeta.byline : '';
        byLineField = wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["TextControl"], {
          value: byline,
          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])("Post Byline", "carkeek-blocks"),
          onChange: function onChange(value) {
            return onbylineChange(value);
          },
          __self: this,
          __source: {
            fileName: _jsxFileName,
            lineNumber: 65,
            columnNumber: 18
          }
        });
      }

      var isTitleHidden = typeof postmeta !== 'undefined' && typeof postmeta._carkeekblocks_title_hidden !== 'undefined' ? postmeta._carkeekblocks_title_hidden : false;
      var isImageHidden = typeof postmeta !== 'undefined' && typeof postmeta._carkeekblocks_featuredimage_hidden !== 'undefined' ? postmeta._carkeekblocks_featuredimage_hidden : false;
      var hideImageCheckbox;

      if (featuredImage) {
        hideImageCheckbox = wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["CheckboxControl"], {
          className: "carkeek-hide-featured-image-label",
          label: "Hide Featured Image",
          checked: isImageHidden,
          onChange: onToggleImage,
          help: isImageHidden ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])("The Featured Image is hidden on the rendered page.", "carkeek-blocks") : Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])("The Featured Image is visible on the rendered page.", "carkeek-blocks"),
          __self: this,
          __source: {
            fileName: _jsxFileName,
            lineNumber: 76,
            columnNumber: 17
          }
        });
      }

      var colorField;

      if (['protected_farms', 'tribe_events'].includes(posttype) && typeof postmeta !== 'undefined') {
        var selectedColor = typeof postmeta._carkeekblocks_archive_background_color !== 'undefined' ? Object(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__["getColorObjectByAttributeValues"])(colors, postmeta._carkeekblocks_archive_background_color) : '';
        colorField = wp.element.createElement(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__["PluginDocumentSettingPanel"], {
          name: "bgcolor-panel",
          title: "Background Color",
          className: "bgcolor-panel",
          __self: this,
          __source: {
            fileName: _jsxFileName,
            lineNumber: 99,
            columnNumber: 17
          }
        }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])('Set a background color that will be used in certain views of this item.'), wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["ColorPalette"], {
          title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])("Color Settings", "carkeek-blocks"),
          colors: colors,
          value: selectedColor.color,
          onChange: onColorChange,
          disableCustomColors: true,
          __self: this,
          __source: {
            fileName: _jsxFileName,
            lineNumber: 105,
            columnNumber: 17
          }
        }));
      }

      return wp.element.createElement(wp.element.Fragment, null, wp.element.createElement(_wordpress_edit_post__WEBPACK_IMPORTED_MODULE_1__["PluginDocumentSettingPanel"], {
        name: "page-header-settings-panel",
        title: "Page Header Settings",
        className: "page-header-settings-panel",
        __self: this,
        __source: {
          fileName: _jsxFileName,
          lineNumber: 118,
          columnNumber: 13
        }
      }, wp.element.createElement(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__["CheckboxControl"], {
        className: "carkeek-hide-title-label",
        label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])("Hide " + posttype + " Title", "carkeek-blocks"),
        checked: isTitleHidden,
        onChange: onToggleTitle,
        help: isTitleHidden ? Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__["__"])("Title is hidden on the rendered page.", "carkeek-blocks") : null,
        __self: this,
        __source: {
          fileName: _jsxFileName,
          lineNumber: 123,
          columnNumber: 17
        }
      }), hideImageCheckbox, byLineField), colorField);
    }
  }]);

  return PageHeaderSettings;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Component"]);

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_5__["compose"])(Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__["withSelect"])(function () {
  return {
    postmeta: Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__["select"])("core/editor").getEditedPostAttribute("meta"),
    featuredImage: Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__["select"])("core/editor").getEditedPostAttribute("featured_media"),
    posttype: Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__["select"])("core/editor").getEditedPostAttribute("type"),
    colors: Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__["select"])("core/block-editor").getSettings().colors
  };
}), Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_4__["withDispatch"])(function (dispatch, ownProps) {
  var hideTitle;

  if (typeof ownProps.postmeta !== 'undefined' && typeof ownProps.postmeta._carkeekblocks_title_hidden !== 'undefined') {
    hideTitle = ownProps.postmeta._carkeekblocks_title_hidden;
  }

  var hideImage;

  if (typeof ownProps.postmeta !== 'undefined' && typeof ownProps.postmeta._carkeekblocks_featuredimage_hidden !== 'undefined') {
    hideImage = ownProps.postmeta._carkeekblocks_featuredimage_hidden;
  }

  return {
    onToggleTitle: function onToggleTitle() {
      dispatch("core/editor").editPost({
        meta: {
          _carkeekblocks_title_hidden: !hideTitle
        }
      });
    },
    onToggleImage: function onToggleImage() {
      dispatch("core/editor").editPost({
        meta: {
          _carkeekblocks_featuredimage_hidden: !hideImage
        }
      });
    },
    onColorChange: function onColorChange(bgcolor) {
      var selected = Object(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__["getColorObjectByColorValue"])(ownProps.colors, bgcolor);
      dispatch("core/editor").editPost({
        meta: {
          _carkeekblocks_archive_background_color: selected.slug
        }
      });
    },
    onbylineChange: function onbylineChange(byline) {
      dispatch("core/editor").editPost({
        meta: {
          byline: byline
        }
      });
    }
  };
}))(PageHeaderSettings));

/***/ }),

/***/ "./src/plugins/styles.editor.scss":
/*!****************************************!*\
  !*** ./src/plugins/styles.editor.scss ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "./src/plugins_editor.js":
/*!*******************************!*\
  !*** ./src/plugins_editor.js ***!
  \*******************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _plugins__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./plugins */ "./src/plugins/index.js");


/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp["components"];

/***/ }),

/***/ "@wordpress/compose":
/*!*********************************!*\
  !*** external ["wp","compose"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp["compose"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp["data"];

/***/ }),

/***/ "@wordpress/edit-post":
/*!**********************************!*\
  !*** external ["wp","editPost"] ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp["editPost"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp["i18n"];

/***/ }),

/***/ "@wordpress/plugins":
/*!*********************************!*\
  !*** external ["wp","plugins"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = wp["plugins"];

/***/ })

/******/ });
//# sourceMappingURL=plugins_editor.js.map