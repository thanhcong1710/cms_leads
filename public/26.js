(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[26],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/cameraAI/list.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/cameraAI/list.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! axios */ "./resources/coreui/node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_Pagination__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../components/Pagination */ "./resources/coreui/src/components/Pagination.vue");
/* harmony import */ var _utilities_utility__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../utilities/utility */ "./resources/coreui/src/utilities/utility.js");
/* harmony import */ var _components_Loading__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../components/Loading */ "./resources/coreui/src/components/Loading.vue");
/* harmony import */ var vue2_datepicker__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vue2-datepicker */ "./resources/coreui/node_modules/vue2-datepicker/lib/index.js");
/* harmony import */ var vue2_datepicker__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(vue2_datepicker__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var vue_multiselect__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! vue-multiselect */ "./resources/coreui/node_modules/vue-multiselect/dist/vue-multiselect.min.js");
/* harmony import */ var vue_multiselect__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(vue_multiselect__WEBPACK_IMPORTED_MODULE_5__);
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
    DatePicker: vue2_datepicker__WEBPACK_IMPORTED_MODULE_4___default.a,
    loader: _components_Loading__WEBPACK_IMPORTED_MODULE_3__["default"],
    paging: _components_Pagination__WEBPACK_IMPORTED_MODULE_1__["default"],
    Multiselect: vue_multiselect__WEBPACK_IMPORTED_MODULE_5___default.a
  },
  name: "List-Parent",
  data: function data() {
    return {
      list_branches: [],
      datepickerOptions: {
        closed: true,
        value: "",
        minDate: "",
        lang: {
          days: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
          months: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"]
        }
      },
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false
      },
      searchData: {
        branch_id: "",
        keyword: "",
        pagination: this.pagination,
        dateRange: ""
      },
      users_manager: [],
      parents: [],
      pagination: {
        url: "/api/camera-ai/list-action",
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
        title: "CHI TIẾT",
        show: false,
        color: "success",
        body: "Cập nhật khách hàng thành công",
        img_url: "",
        closeOnBackdrop: false
      }
    };
  },
  created: function created() {
    var _this = this;

    _utilities_utility__WEBPACK_IMPORTED_MODULE_2__["default"].g("/api/branches?permission=1").then(function (response) {
      _this.list_branches = response.data;
    });
    this.search();
  },
  methods: {
    reset: function reset() {
      location.reload();
    },
    search: function search(a) {
      var _this2 = this;

      var startDate = this.searchData.dateRange != '' && this.searchData.dateRange[0] ? "".concat(_utilities_utility__WEBPACK_IMPORTED_MODULE_2__["default"].dateToString(this.searchData.dateRange[0])) : '';
      var endDate = this.searchData.dateRange != '' && this.searchData.dateRange[1] ? "".concat(_utilities_utility__WEBPACK_IMPORTED_MODULE_2__["default"].dateToString(this.searchData.dateRange[1])) : '';
      var ids_branch_id = [];
      this.searchData.branch_id = _utilities_utility__WEBPACK_IMPORTED_MODULE_2__["default"].is.obj(this.searchData.branch_id) ? [this.searchData.branch_id] : this.searchData.branch_id;

      if (this.searchData.branch_id.length) {
        this.searchData.branch_id.map(function (item) {
          ids_branch_id.push(item.id);
        });
      }

      var data = {
        keyword: this.searchData.keyword,
        branch_id: ids_branch_id,
        pagination: this.pagination,
        end_date: endDate,
        start_date: startDate
      };
      var link = "/api/camera-ai/list-action";
      this.loading.processing = true;
      _utilities_utility__WEBPACK_IMPORTED_MODULE_2__["default"].p(link, data).then(function (response) {
        _this2.loading.processing = false;
        _this2.parents = response.data.list;
        _this2.total = response.data.detail_total;
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
    showModalImg: function showModalImg(img_url) {
      this.modal.img_url = img_url;
      this.modal.show = true;
    },
    exit: function exit() {
      this.modal.show = false;
    }
  },
  sockets: {
    camera_ai: function camera_ai(data) {
      if (localStorage.getItem("branch_id") == 0 || data.branch_id == localStorage.getItem("branch_id")) {
        this.search();
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/cameraAI/list.vue?vue&type=template&id=751a0a20&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/cameraAI/list.vue?vue&type=template&id=751a0a20& ***!
  \*******************************************************************************************************************************************************************************************************************/
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
                  _vm.list_branches.length > 1
                    ? _c(
                        "div",
                        { staticClass: "form-group col-sm-3" },
                        [
                          _c("label", { attrs: { for: "name" } }, [
                            _vm._v("Trung tâm")
                          ]),
                          _vm._v(" "),
                          _c(
                            "multiselect",
                            {
                              attrs: {
                                placeholder: "Chọn trung tâm",
                                "select-label": "Chọn trung tâm",
                                options: _vm.list_branches,
                                label: "name",
                                "close-on-select": false,
                                "hide-selected": true,
                                multiple: true,
                                searchable: true,
                                "track-by": "id"
                              },
                              model: {
                                value: _vm.searchData.branch_id,
                                callback: function($$v) {
                                  _vm.$set(_vm.searchData, "branch_id", $$v)
                                },
                                expression: "searchData.branch_id"
                              }
                            },
                            [
                              _c(
                                "span",
                                {
                                  attrs: { slot: "noResult" },
                                  slot: "noResult"
                                },
                                [_vm._v("Không tìm thấy dữ liệu")]
                              )
                            ]
                          )
                        ],
                        1
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _c("div", { staticClass: "form-group col-sm-3" }, [
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
                        placeholder: "Tên khách hàng, số điện thoại"
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
                    { staticClass: "form-group col-sm-3" },
                    [
                      _c("label", { attrs: { for: "ccmonth" } }, [
                        _vm._v("Thời gian")
                      ]),
                      _vm._v(" "),
                      _c("date-picker", {
                        staticStyle: { width: "100%" },
                        attrs: {
                          clearable: true,
                          lang: _vm.datepickerOptions.lang,
                          range: "",
                          format: "YYYY-MM-DD",
                          id: "apax-date-range",
                          placeholder:
                            "Chọn thời gian tìm kiếm từ ngày đến ngày"
                        },
                        model: {
                          value: _vm.searchData.dateRange,
                          callback: function($$v) {
                            _vm.$set(_vm.searchData, "dateRange", $$v)
                          },
                          expression: "searchData.dateRange"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "form-group col-sm-12" }, [
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
                  ])
                ]),
                _vm._v(" "),
                _c("div", [
                  _c(
                    "table",
                    { staticClass: "table table-striped table-hover" },
                    [
                      _vm._m(1),
                      _vm._v(" "),
                      _c(
                        "tbody",
                        _vm._l(_vm.parents, function(item, index) {
                          return _c("tr", { key: index }, [
                            _c("td", [
                              _vm._v(
                                "\n                    " +
                                  _vm._s(
                                    index +
                                      1 +
                                      (_vm.pagination.cpage - 1) *
                                        _vm.pagination.limit
                                  ) +
                                  "\n                  "
                              )
                            ]),
                            _vm._v(" "),
                            _c("td", [_vm._v(_vm._s(item.branch_name))]),
                            _vm._v(" "),
                            _c("td", [_vm._v(_vm._s(item.student_name))]),
                            _vm._v(" "),
                            _c("td", [_vm._v(_vm._s(item.date))]),
                            _vm._v(" "),
                            _c("td", [
                              _c(
                                "button",
                                {
                                  staticClass: "btn btn-sm btn-info",
                                  on: {
                                    click: function($event) {
                                      return _vm.showModalImg(
                                        item.detected_image_url
                                      )
                                    }
                                  }
                                },
                                [_c("i", { staticClass: "fa fa-eye" })]
                              )
                            ])
                          ])
                        }),
                        0
                      )
                    ]
                  )
                ]),
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
                      }),
                      _vm._v(" "),
                      _c(
                        "div",
                        {
                          staticClass: "last page",
                          staticStyle: {
                            width: "60px",
                            float: "left",
                            height: "30px"
                          }
                        },
                        [
                          _c(
                            "select",
                            {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.pagination.limit,
                                  expression: "pagination.limit"
                                }
                              ],
                              staticStyle: { width: "100%", height: "100%" },
                              on: {
                                change: [
                                  function($event) {
                                    var $$selectedVal = Array.prototype.filter
                                      .call($event.target.options, function(o) {
                                        return o.selected
                                      })
                                      .map(function(o) {
                                        var val =
                                          "_value" in o ? o._value : o.value
                                        return val
                                      })
                                    _vm.$set(
                                      _vm.pagination,
                                      "limit",
                                      $event.target.multiple
                                        ? $$selectedVal
                                        : $$selectedVal[0]
                                    )
                                  },
                                  function($event) {
                                    return _vm.search()
                                  }
                                ]
                              }
                            },
                            [
                              _c("option", { attrs: { value: "20" } }, [
                                _vm._v("20")
                              ]),
                              _vm._v(" "),
                              _c("option", { attrs: { value: "50" } }, [
                                _vm._v("50")
                              ]),
                              _vm._v(" "),
                              _c("option", { attrs: { value: "100" } }, [
                                _vm._v("100")
                              ]),
                              _vm._v(" "),
                              _c("option", { attrs: { value: "200" } }, [
                                _vm._v("200")
                              ]),
                              _vm._v(" "),
                              _c("option", { attrs: { value: "300" } }, [
                                _vm._v("300")
                              ])
                            ]
                          )
                        ]
                      )
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
            color: _vm.modal.color
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
        [_c("img", { attrs: { src: _vm.modal.img_url, width: "100%" } })]
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
      _c("strong", [_vm._v("Danh sách sự kiện")])
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
        _c("th", [_vm._v("Trung tâm")]),
        _vm._v(" "),
        _c("th", [_vm._v("Học sinh")]),
        _vm._v(" "),
        _c("th", [_vm._v("Thời gian checkin")]),
        _vm._v(" "),
        _c("th", [_vm._v("Thao tác")])
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/coreui/src/views/cameraAI/list.vue":
/*!******************************************************!*\
  !*** ./resources/coreui/src/views/cameraAI/list.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _list_vue_vue_type_template_id_751a0a20___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./list.vue?vue&type=template&id=751a0a20& */ "./resources/coreui/src/views/cameraAI/list.vue?vue&type=template&id=751a0a20&");
/* harmony import */ var _list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./list.vue?vue&type=script&lang=js& */ "./resources/coreui/src/views/cameraAI/list.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _list_vue_vue_type_template_id_751a0a20___WEBPACK_IMPORTED_MODULE_0__["render"],
  _list_vue_vue_type_template_id_751a0a20___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/coreui/src/views/cameraAI/list.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/coreui/src/views/cameraAI/list.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/coreui/src/views/cameraAI/list.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./list.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/cameraAI/list.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_list_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/coreui/src/views/cameraAI/list.vue?vue&type=template&id=751a0a20&":
/*!*************************************************************************************!*\
  !*** ./resources/coreui/src/views/cameraAI/list.vue?vue&type=template&id=751a0a20& ***!
  \*************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_list_vue_vue_type_template_id_751a0a20___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./list.vue?vue&type=template&id=751a0a20& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/cameraAI/list.vue?vue&type=template&id=751a0a20&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_list_vue_vue_type_template_id_751a0a20___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_list_vue_vue_type_template_id_751a0a20___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);