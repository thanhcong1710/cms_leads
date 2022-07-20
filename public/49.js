(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[49],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/setting/target/list.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/setting/target/list.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! axios */ "./resources/coreui/node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_Pagination__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../components/Pagination */ "./resources/coreui/src/components/Pagination.vue");
/* harmony import */ var _utilities_utility__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../utilities/utility */ "./resources/coreui/src/utilities/utility.js");
/* harmony import */ var _components_Loading__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../components/Loading */ "./resources/coreui/src/components/Loading.vue");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vue-select */ "./resources/coreui/node_modules/vue-select/dist/vue-select.js");
/* harmony import */ var vue_select__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(vue_select__WEBPACK_IMPORTED_MODULE_4__);
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

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
  components: {
    loader: _components_Loading__WEBPACK_IMPORTED_MODULE_3__["default"],
    paging: _components_Pagination__WEBPACK_IMPORTED_MODULE_1__["default"],
    "vue-select": vue_select__WEBPACK_IMPORTED_MODULE_4___default.a
  },
  name: "List-Parent",
  data: function data() {
    return {
      list_week: [],
      week: '',
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false
      },
      searchData: {
        keyword: "",
        week_id: "",
        pagination: this.pagination
      },
      users_manager: [],
      users: [],
      pagination: {
        url: "/api/users/list",
        id: "",
        style: "line",
        "class": "",
        spage: 1,
        ppage: 1,
        npage: 0,
        lpage: 1,
        cpage: 1,
        total: 0,
        limit: 20,
        limitSource: [10, 20, 30, 40, 50],
        pages: []
      },
      modal: {
        title: "THÔNG BÁO",
        show: false,
        color: "success",
        body: "Cập nhật khách hàng thành công",
        closeOnBackdrop: false
      },
      roles: []
    };
  },
  created: function created() {
    var _this = this;

    _utilities_utility__WEBPACK_IMPORTED_MODULE_2__["default"].g("/api/report_week?type=all").then(function (response) {
      _this.list_week = response.data;
    });
    this.search();
  },
  methods: {
    selectWeek: function selectWeek() {
      var data = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

      if (data && _typeof(data) === 'object') {
        var week_id = data.id;
        this.searchData.week_id = week_id;
      } else {
        this.searchData.week_id = "";
      }
    },
    reset: function reset() {
      location.reload();
    },
    search: function search(a) {
      var _this2 = this;

      var data = {
        keyword: this.searchData.keyword,
        week_id: this.searchData.week_id
      };
      var link = "/api/target/list";
      this.loading.processing = true;
      _utilities_utility__WEBPACK_IMPORTED_MODULE_2__["default"].p(link, data).then(function (response) {
        _this2.loading.processing = false;
        _this2.users = response.data.list;
        _this2.pagination.spage = response.data.paging.spage;
        _this2.pagination.ppage = response.data.paging.ppage;
        _this2.pagination.npage = response.data.paging.npage;
        _this2.pagination.lpage = response.data.paging.lpage;
        _this2.pagination.cpage = response.data.paging.cpage;
        _this2.pagination.total = response.data.paging.total;
        _this2.pagination.limit = response.data.paging.limit;
      })["catch"](function (e) {
        _utilities_utility__WEBPACK_IMPORTED_MODULE_2__["default"].processAuthen(e);
      });
    },
    changePage: function changePage(link) {
      var info = link.toString().substr(this.pagination.url.length).split("/");
      var page = info.length > 1 ? info[1] : 1;
      this.pagination.cpage = parseInt(page);
      this.search();
    },
    exit: function exit() {
      this.modal.show = false;
    }
  },
  filters: {
    getStatusName: function getStatusName(value) {
      return value == 1 ? "Kích hoạt" : "Không kích hoạt";
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/setting/target/list.vue?vue&type=template&id=796137a6&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/setting/target/list.vue?vue&type=template&id=796137a6& ***!
  \*************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "animated fadeIn" },
    [
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col-lg-12" }, [
          _c(
            "div",
            { staticClass: "card" },
            [
              _c("loader", {
                attrs: {
                  active: _vm.loading.processing,
                  text: _vm.loading.text
                }
              }),
              _vm._v(" "),
              _vm._m(0),
              _vm._v(" "),
              _c("div", { staticClass: "card-body" }, [
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "form-group col-sm-6" }, [
                    _c("label", { attrs: { for: "name" } }, [
                      _vm._v("Từ khóa")
                    ]),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.searchData.keyword,
                          expression: "searchData.keyword"
                        }
                      ],
                      staticClass: "form-control",
                      attrs: {
                        type: "text",
                        placeholder: "Tên nhân viên, mã nhân viên"
                      },
                      domProps: { value: _vm.searchData.keyword },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.$set(
                            _vm.searchData,
                            "keyword",
                            $event.target.value
                          )
                        }
                      }
                    })
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "form-group col-sm-6" },
                    [
                      _c("label", { attrs: { for: "ccmonth" } }, [
                        _vm._v("Tuần")
                      ]),
                      _vm._v(" "),
                      _c("vue-select", {
                        attrs: {
                          label: "label",
                          placeholder: "Chọn tuần",
                          options: _vm.list_week,
                          searchable: true,
                          language: "tv-VN",
                          onChange: _vm.selectWeek
                        },
                        model: {
                          value: _vm.week,
                          callback: function($$v) {
                            _vm.week = $$v
                          },
                          expression: "week"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "form-group col-sm-12" },
                    [
                      _c(
                        "router-link",
                        {
                          staticClass: "btn btn-success",
                          attrs: { to: "/setting/target/add" }
                        },
                        [
                          _c("i", { staticClass: "fa fa-plus" }),
                          _vm._v(" Thêm mới\n              ")
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "button",
                        {
                          staticClass: "btn btn-info",
                          attrs: { type: "submit" },
                          on: {
                            click: function($event) {
                              return _vm.search()
                            }
                          }
                        },
                        [
                          _c("i", { staticClass: "fa fa-search" }),
                          _vm._v(" Tìm kiếm\n              ")
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "button",
                        {
                          staticClass: "btn btn-secondary",
                          attrs: { type: "reset" },
                          on: {
                            click: function($event) {
                              return _vm.reset()
                            }
                          }
                        },
                        [
                          _c("i", { staticClass: "fas fa-undo-alt" }),
                          _vm._v(" Reset\n              ")
                        ]
                      )
                    ],
                    1
                  )
                ]),
                _vm._v(" "),
                _c(
                  "table",
                  { staticClass: "table table-striped table-hover" },
                  [
                    _vm._m(1),
                    _vm._v(" "),
                    _c(
                      "tbody",
                      _vm._l(_vm.users, function(item, index) {
                        return _c("tr", { key: index }, [
                          _c("td", [
                            _vm._v(
                              "\n                  " +
                                _vm._s(
                                  index +
                                    1 +
                                    (_vm.pagination.cpage - 1) *
                                      _vm.pagination.limit
                                ) +
                                "\n                "
                            )
                          ]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(item.user_label))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(item.week_label))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(item.call))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(item.talk_time))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(item.trial_accept))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(item.trial_actual))]),
                          _vm._v(" "),
                          _c("td", [_vm._v(_vm._s(item.new_enroll))]),
                          _vm._v(" "),
                          _c(
                            "td",
                            [
                              item.can_edit == 1
                                ? _c(
                                    "router-link",
                                    {
                                      staticClass: "btn btn-sm btn-success",
                                      attrs: {
                                        to:
                                          "/setting/target/" + item.id + "/edit"
                                      }
                                    },
                                    [_c("i", { staticClass: "fa fa-edit" })]
                                  )
                                : _vm._e()
                            ],
                            1
                          )
                        ])
                      }),
                      0
                    )
                  ]
                ),
                _vm._v(" "),
                _c("div", { staticClass: "text-center" }, [
                  _c(
                    "nav",
                    { attrs: { "aria-label": "Page navigation" } },
                    [
                      _c("paging", {
                        attrs: {
                          rootLink: _vm.pagination.url,
                          id: _vm.pagination.id,
                          listStyle: _vm.pagination.style,
                          customClass: _vm.pagination.class,
                          firstPage: _vm.pagination.spage,
                          previousPage: _vm.pagination.ppage,
                          nextPage: _vm.pagination.npage,
                          lastPage: _vm.pagination.lpage,
                          currentPage: _vm.pagination.cpage,
                          pagesItems: _vm.pagination.total,
                          pagesLimit: _vm.pagination.limit,
                          pageList: _vm.pagination.pages,
                          routing: _vm.changePage
                        }
                      })
                    ],
                    1
                  )
                ])
              ])
            ],
            1
          )
        ])
      ]),
      _vm._v(" "),
      _c(
        "CModal",
        {
          attrs: {
            title: _vm.modal.title,
            show: _vm.modal.show,
            color: _vm.modal.color,
            closeOnBackdrop: _vm.modal.closeOnBackdrop
          },
          on: {
            "update:show": function($event) {
              return _vm.$set(_vm.modal, "show", $event)
            }
          },
          scopedSlots: _vm._u([
            {
              key: "header",
              fn: function() {
                return [
                  _c("h5", { staticClass: "modal-title" }, [
                    _vm._v(_vm._s(_vm.modal.title))
                  ])
                ]
              },
              proxy: true
            },
            {
              key: "footer",
              fn: function() {
                return [
                  _c(
                    "CButton",
                    {
                      attrs: {
                        color: "btn btn-" + _vm.modal.color,
                        type: "button"
                      },
                      on: { click: _vm.exit }
                    },
                    [_vm._v("Đóng")]
                  )
                ]
              },
              proxy: true
            }
          ])
        },
        [_vm._v("\n    " + _vm._s(_vm.modal.body) + "\n    ")]
      )
    ],
    1
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "card-header" }, [
      _c("strong", [_vm._v("Danh sách mục tiêu tuần")])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v("STT")]),
        _vm._v(" "),
        _c("th", [_vm._v("Nhân viên")]),
        _vm._v(" "),
        _c("th", [_vm._v("Tuần")]),
        _vm._v(" "),
        _c("th", [_vm._v("Call")]),
        _vm._v(" "),
        _c("th", [_vm._v("Talk time")]),
        _vm._v(" "),
        _c("th", [_vm._v("Trial accept")]),
        _vm._v(" "),
        _c("th", [_vm._v("Trial actual")]),
        _vm._v(" "),
        _c("th", [_vm._v("New enroll")]),
        _vm._v(" "),
        _c("th", [_vm._v("Thao tác")])
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/coreui/src/views/setting/target/list.vue":
/*!************************************************************!*\
  !*** ./resources/coreui/src/views/setting/target/list.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _list_vue_vue_type_template_id_796137a6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./list.vue?vue&type=template&id=796137a6& */ "./resources/coreui/src/views/setting/target/list.vue?vue&type=template&id=796137a6&");
/* harmony import */ var _list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./list.vue?vue&type=script&lang=js& */ "./resources/coreui/src/views/setting/target/list.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _list_vue_vue_type_template_id_796137a6___WEBPACK_IMPORTED_MODULE_0__["render"],
  _list_vue_vue_type_template_id_796137a6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/coreui/src/views/setting/target/list.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/coreui/src/views/setting/target/list.vue?vue&type=script&lang=js&":
/*!*************************************************************************************!*\
  !*** ./resources/coreui/src/views/setting/target/list.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./list.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/setting/target/list.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/coreui/src/views/setting/target/list.vue?vue&type=template&id=796137a6&":
/*!*******************************************************************************************!*\
  !*** ./resources/coreui/src/views/setting/target/list.vue?vue&type=template&id=796137a6& ***!
  \*******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_list_vue_vue_type_template_id_796137a6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./list.vue?vue&type=template&id=796137a6& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/setting/target/list.vue?vue&type=template&id=796137a6&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_list_vue_vue_type_template_id_796137a6___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_list_vue_vue_type_template_id_796137a6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);