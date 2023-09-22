(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[39],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/pages/Login.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/pages/Login.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! axios */ "./resources/coreui/node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utilities_utility__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utilities/utility */ "./resources/coreui/src/utilities/utility.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'Login',
  data: function data() {
    return {
      email: '',
      password: '',
      showMessage: false,
      message: ''
    };
  },
  sockets: {
    connect: function connect() {
      console.log('socket to notification channel connected');
    }
  },
  created: function created() {
    _utilities_utility__WEBPACK_IMPORTED_MODULE_1__["default"].g('/api/get-login-redirect').then(function (response) {
      location.href = response.data;
    });
  },
  methods: {
    goRegister: function goRegister() {
      this.$router.push({
        path: 'register'
      });
    },
    login: function login() {
      var self = this;
      axios__WEBPACK_IMPORTED_MODULE_0___default.a.post('/api/login', {
        email: self.email,
        password: self.password
      }).then(function (response) {
        self.email = '';
        self.password = '';
        localStorage.setItem("user_id", response.data.user_id);
        localStorage.setItem("api_token", response.data.access_token);
        localStorage.setItem('roles', JSON.stringify(response.data.roles));
        localStorage.setItem('user_info', JSON.stringify(response.data.user_info));
        self.$router.push({
          path: '/dashboard'
        }); // self.$socket.emit('userConnected', response.data.user_id);
      })["catch"](function (error) {
        self.message = 'Incorrect E-mail or password';
        self.showMessage = true;
        console.log(error);
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/pages/Login.vue?vue&type=template&id=29374064&":
/*!*****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/pages/Login.vue?vue&type=template&id=29374064& ***!
  \*****************************************************************************************************************************************************************************************************************/
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
  return _c(
    "div",
    { staticClass: "d-flex content-center min-vh-100" },
    [
      _c(
        "CContainer",
        { attrs: { fluid: "" } },
        [
          _c(
            "CRow",
            { staticClass: "justify-content-center" },
            [
              _c(
                "CCol",
                { attrs: { md: "6" } },
                [
                  _c(
                    "CCard",
                    { staticClass: "mx-4 mb-0" },
                    [
                      _c(
                        "CCardBody",
                        { staticClass: "p-4" },
                        [
                          _c(
                            "CForm",
                            {
                              attrs: { method: "POST" },
                              on: {
                                submit: function($event) {
                                  $event.preventDefault()
                                  return _vm.login.apply(null, arguments)
                                }
                              }
                            },
                            [
                              _c(
                                "svg",
                                {
                                  staticStyle: { margin: "20px" },
                                  attrs: {
                                    version: "1.0",
                                    id: "cms-logo",
                                    xmlns: "http://www.w3.org/2000/svg",
                                    width: "200px",
                                    viewBox: "0 0 1020.000000 418.000000",
                                    preserveAspectRatio: "xMidYMid meet"
                                  }
                                },
                                [
                                  _c(
                                    "g",
                                    {
                                      attrs: {
                                        transform:
                                          "translate(0.000000,418.000000) scale(0.100000,-0.100000)",
                                        fill: "#cd0118",
                                        stroke: "none"
                                      }
                                    },
                                    [
                                      _c("path", {
                                        attrs: {
                                          d:
                                            "M6463 4169 c-74 -14 -132 -46 -192 -106 -80 -80 -105 -147 -98 -261\n                      11 -185 124 -308 314 -342 147 -26 323 68 384 205 19 41 23 68 23 145 -1 103\n                      -15 156 -60 222 -67 97 -245 162 -371 137z"
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c("path", {
                                        attrs: {
                                          d:
                                            "M3585 4157 c-65 -22 -99 -43 -142 -90 -61 -66 -86 -141 -87 -257 -1\n                      -83 2 -102 26 -153 59 -125 175 -199 314 -199 194 1 320 99 369 288 13 49 13\n                      66 1 124 -28 132 -93 221 -195 268 -45 21 -74 27 -151 29 -59 2 -110 -2 -135\n                      -10z"
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c("path", {
                                        attrs: {
                                          d:
                                            "M6357 3240 c-184 -11 -249 -29 -298 -80 -71 -74 -829 -1136 -829\n                      -1160 0 -6 164 -10 440 -10 l440 0 0 -980 0 -980 385 0 385 0 0 1605 0 1605\n                      -92 0 c-51 0 -136 2 -188 4 -52 3 -162 0 -243 -4z"
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c("path", {
                                        attrs: {
                                          d:
                                            "M8345 3241 c-420 -9 -474 -18 -648 -108 -53 -27 -98 -62 -168 -132\n                      -83 -84 -102 -109 -147 -201 -155 -314 -163 -761 -17 -1050 94 -186 246 -317\n                      448 -386 157 -54 199 -57 782 -59 594 -2 608 -3 692 -64 87 -63 125 -212 88\n                      -349 -15 -54 -27 -75 -65 -113 -87 -87 -47 -83 -865 -89 -712 -5 -721 -5 -779\n                      -27 -91 -35 -116 -60 -178 -179 -71 -139 -212 -463 -204 -471 3 -3 473 -6\n                      1043 -6 1147 -1 1138 -1 1317 64 304 111 492 369 541 745 72 552 -136 952\n                      -568 1094 -149 49 -190 52 -747 56 -329 3 -546 8 -580 15 -149 30 -219 118\n                      -220 276 0 107 26 179 80 226 74 63 45 60 860 67 491 5 758 10 784 18 22 6 51\n                      19 65 30 36 26 106 154 226 416 l104 226 -373 0 c-205 0 -541 2 -747 4 -206 2\n                      -531 0 -724 -3z"
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c("path", {
                                        attrs: {
                                          d:
                                            "M1390 3229 c-958 -108 -1482 -803 -1375 -1826 61 -581 275 -984 631\n                      -1194 157 -93 312 -138 565 -166 189 -20 824 -25 1402 -9 l327 8 0 323 0 323\n                      -717 5 c-774 5 -821 8 -974 61 -271 94 -384 354 -383 876 1 531 148 797 492\n                      892 72 20 110 22 607 28 324 4 542 11 560 17 107 37 127 58 210 224 47 91 205\n                      436 205 445 0 8 -1475 1 -1550 -7z"
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c("path", {
                                        attrs: {
                                          d:
                                            "M3370 1630 l0 -1610 385 0 385 0 0 985 c0 578 4 985 9 985 14 0 139\n                      -137 279 -305 69 -82 164 -189 211 -238 l85 -87 373 0 c205 0 373 2 373 4 0 5\n                      -491 699 -949 1340 -168 237 -323 447 -343 467 -63 65 -94 69 -475 69 l-333 0\n                      0 -1610z"
                                        }
                                      })
                                    ]
                                  )
                                ]
                              ),
                              _vm._v(" "),
                              _c("h1", [_vm._v("Login")]),
                              _vm._v(" "),
                              _c("p", { staticClass: "text-muted" }, [
                                _vm._v("Sign In to your account")
                              ]),
                              _vm._v(" "),
                              _c("CInput", {
                                attrs: {
                                  prependHtml: "<i class='cui-user'></i>",
                                  placeholder: "Username",
                                  autocomplete: "username email"
                                },
                                scopedSlots: _vm._u([
                                  {
                                    key: "prepend-content",
                                    fn: function() {
                                      return [
                                        _c("CIcon", {
                                          attrs: { name: "cil-user" }
                                        })
                                      ]
                                    },
                                    proxy: true
                                  }
                                ]),
                                model: {
                                  value: _vm.email,
                                  callback: function($$v) {
                                    _vm.email = $$v
                                  },
                                  expression: "email"
                                }
                              }),
                              _vm._v(" "),
                              _c("CInput", {
                                attrs: {
                                  prependHtml:
                                    "<i class='cui-lock-locked'></i>",
                                  placeholder: "Password",
                                  type: "password",
                                  autocomplete: "curent-password"
                                },
                                scopedSlots: _vm._u([
                                  {
                                    key: "prepend-content",
                                    fn: function() {
                                      return [
                                        _c("CIcon", {
                                          attrs: { name: "cil-lock-locked" }
                                        })
                                      ]
                                    },
                                    proxy: true
                                  }
                                ]),
                                model: {
                                  value: _vm.password,
                                  callback: function($$v) {
                                    _vm.password = $$v
                                  },
                                  expression: "password"
                                }
                              }),
                              _vm._v(" "),
                              _c(
                                "CRow",
                                [
                                  _c(
                                    "CCol",
                                    {
                                      staticClass: "text-left",
                                      attrs: { col: "6" }
                                    },
                                    [
                                      _c(
                                        "CButton",
                                        {
                                          staticClass: "px-4",
                                          attrs: {
                                            type: "submit",
                                            color: "primary"
                                          }
                                        },
                                        [_vm._v("Login")]
                                      )
                                    ],
                                    1
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "CCol",
                                    {
                                      staticClass: "text-right",
                                      attrs: { col: "6" }
                                    },
                                    [
                                      _c(
                                        "CButton",
                                        {
                                          staticClass: "px-0",
                                          attrs: { color: "link" }
                                        },
                                        [_vm._v("Forgot password?")]
                                      )
                                    ],
                                    1
                                  )
                                ],
                                1
                              )
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c("p", { staticStyle: { color: "red" } }, [
                            _vm._v(_vm._s(_vm.message))
                          ])
                        ],
                        1
                      )
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/coreui/src/views/pages/Login.vue":
/*!****************************************************!*\
  !*** ./resources/coreui/src/views/pages/Login.vue ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Login_vue_vue_type_template_id_29374064___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Login.vue?vue&type=template&id=29374064& */ "./resources/coreui/src/views/pages/Login.vue?vue&type=template&id=29374064&");
/* harmony import */ var _Login_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Login.vue?vue&type=script&lang=js& */ "./resources/coreui/src/views/pages/Login.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Login_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Login_vue_vue_type_template_id_29374064___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Login_vue_vue_type_template_id_29374064___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/coreui/src/views/pages/Login.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/coreui/src/views/pages/Login.vue?vue&type=script&lang=js&":
/*!*****************************************************************************!*\
  !*** ./resources/coreui/src/views/pages/Login.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Login.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/pages/Login.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/coreui/src/views/pages/Login.vue?vue&type=template&id=29374064&":
/*!***********************************************************************************!*\
  !*** ./resources/coreui/src/views/pages/Login.vue?vue&type=template&id=29374064& ***!
  \***********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_template_id_29374064___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./Login.vue?vue&type=template&id=29374064& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/pages/Login.vue?vue&type=template&id=29374064&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_template_id_29374064___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Login_vue_vue_type_template_id_29374064___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);