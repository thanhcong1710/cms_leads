(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[45],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/pages/SwitchSystem.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/pages/SwitchSystem.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _utilities_utility__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../utilities/utility */ "./resources/coreui/src/utilities/utility.js");
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'Login',
  data: function data() {
    return {
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false
      }
    };
  },
  created: function created() {
    _utilities_utility__WEBPACK_IMPORTED_MODULE_0__["default"].g("/api/switch-system").then(function (response) {
      window.location.href = response.data.link_redirect;
    })["catch"](function (e) {
      _utilities_utility__WEBPACK_IMPORTED_MODULE_0__["default"].processAuthen(e);
    });
  },
  methods: {}
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/pages/SwitchSystem.vue?vue&type=template&id=0b733cae&":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/pages/SwitchSystem.vue?vue&type=template&id=0b733cae& ***!
  \************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div")
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/coreui/src/views/pages/SwitchSystem.vue":
/*!***********************************************************!*\
  !*** ./resources/coreui/src/views/pages/SwitchSystem.vue ***!
  \***********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SwitchSystem_vue_vue_type_template_id_0b733cae___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SwitchSystem.vue?vue&type=template&id=0b733cae& */ "./resources/coreui/src/views/pages/SwitchSystem.vue?vue&type=template&id=0b733cae&");
/* harmony import */ var _SwitchSystem_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SwitchSystem.vue?vue&type=script&lang=js& */ "./resources/coreui/src/views/pages/SwitchSystem.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SwitchSystem_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SwitchSystem_vue_vue_type_template_id_0b733cae___WEBPACK_IMPORTED_MODULE_0__["render"],
  _SwitchSystem_vue_vue_type_template_id_0b733cae___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/coreui/src/views/pages/SwitchSystem.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/coreui/src/views/pages/SwitchSystem.vue?vue&type=script&lang=js&":
/*!************************************************************************************!*\
  !*** ./resources/coreui/src/views/pages/SwitchSystem.vue?vue&type=script&lang=js& ***!
  \************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SwitchSystem_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./SwitchSystem.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/pages/SwitchSystem.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SwitchSystem_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/coreui/src/views/pages/SwitchSystem.vue?vue&type=template&id=0b733cae&":
/*!******************************************************************************************!*\
  !*** ./resources/coreui/src/views/pages/SwitchSystem.vue?vue&type=template&id=0b733cae& ***!
  \******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SwitchSystem_vue_vue_type_template_id_0b733cae___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./SwitchSystem.vue?vue&type=template&id=0b733cae& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/pages/SwitchSystem.vue?vue&type=template&id=0b733cae&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SwitchSystem_vue_vue_type_template_id_0b733cae___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SwitchSystem_vue_vue_type_template_id_0b733cae___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);