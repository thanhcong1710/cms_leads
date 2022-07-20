(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[43],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/reports/report_01.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/reports/report_01.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_Pagination__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../components/Pagination */ "./resources/coreui/src/components/Pagination.vue");
/* harmony import */ var _utilities_utility__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../utilities/utility */ "./resources/coreui/src/utilities/utility.js");
/* harmony import */ var _components_Loading__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../components/Loading */ "./resources/coreui/src/components/Loading.vue");
/* harmony import */ var vue2_datepicker__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! vue2-datepicker */ "./resources/coreui/node_modules/vue2-datepicker/lib/index.js");
/* harmony import */ var vue2_datepicker__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(vue2_datepicker__WEBPACK_IMPORTED_MODULE_3__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    DatePicker: vue2_datepicker__WEBPACK_IMPORTED_MODULE_3___default.a,
    loader: _components_Loading__WEBPACK_IMPORTED_MODULE_2__["default"],
    paging: _components_Pagination__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  name: "List-Parent",
  data: function data() {
    return {
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false
      },
      searchData: {
        dateRange: "",
        keyword: "",
        pagination: this.pagination
      },
      imports: [],
      pagination: {
        url: "/api/imports/list",
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
      datepickerOptions: {
        closed: true,
        value: "",
        minDate: "",
        lang: {
          days: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
          months: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"]
        }
      }
    };
  },
  created: function created() {
    this.search();
  },
  methods: {
    reset: function reset() {
      location.reload();
    },
    search: function search(a) {
      var _this = this;

      var startDate = this.searchData.dateRange != '' && this.searchData.dateRange[0] ? "".concat(_utilities_utility__WEBPACK_IMPORTED_MODULE_1__["default"].dateToString(this.searchData.dateRange[0])) : '';
      var endDate = this.searchData.dateRange != '' && this.searchData.dateRange[1] ? "".concat(_utilities_utility__WEBPACK_IMPORTED_MODULE_1__["default"].dateToString(this.searchData.dateRange[1])) : '';
      var data = {
        start_date: startDate,
        end_date: endDate,
        keyword: this.searchData.keyword,
        pagination: this.pagination
      };
      var link = "/api/reports/01";
      this.loading.processing = true;
      _utilities_utility__WEBPACK_IMPORTED_MODULE_1__["default"].p(link, data).then(function (response) {
        _this.loading.processing = false;
        _this.imports = response.data.list;
        _this.pagination.spage = response.data.paging.spage;
        _this.pagination.ppage = response.data.paging.ppage;
        _this.pagination.npage = response.data.paging.npage;
        _this.pagination.lpage = response.data.paging.lpage;
        _this.pagination.cpage = response.data.paging.cpage;
        _this.pagination.total = response.data.paging.total;
        _this.pagination.limit = response.data.paging.limit;
      })["catch"](function (e) {
        _utilities_utility__WEBPACK_IMPORTED_MODULE_1__["default"].processAuthen(e);
      });
    },
    changePage: function changePage(link) {
      var info = link.toString().substr(this.pagination.url.length).split("/");
      var page = info.length > 1 ? info[1] : 1;
      this.pagination.cpage = parseInt(page);
      this.search();
    },
    exportExcel: function exportExcel() {
      var startDate = this.searchData.dateRange != '' && this.searchData.dateRange[0] ? "".concat(_utilities_utility__WEBPACK_IMPORTED_MODULE_1__["default"].dateToString(this.searchData.dateRange[0])) : '';
      var endDate = this.searchData.dateRange != '' && this.searchData.dateRange[1] ? "".concat(_utilities_utility__WEBPACK_IMPORTED_MODULE_1__["default"].dateToString(this.searchData.dateRange[1])) : '';
      var url = "/api/export/report01/";
      this.key = '';
      this.value = '';

      if (this.searchData.keyword) {
        this.key += "keyword,";
        this.value += this.searchData.keyword + ",";
      }

      if (startDate) {
        this.key += "start_date,";
        this.value += startDate + ",";
      }

      if (endDate) {
        this.key += "end_date,";
        this.value += endDate + ",";
      }

      this.key = this.key ? this.key.substring(0, this.key.length - 1) : '_';
      this.value = this.value ? this.value.substring(0, this.value.length - 1) : "_";
      url += this.key + "/" + this.value + "?token=".concat(localStorage.getItem("api_token"));
      window.open(url, '_blank');
    }
  },
  filters: {
    getStatusName: function getStatusName(value) {
      var resp = '';

      switch (Number(value)) {
        case 1:
          resp = 'KH mới';
          break;

        case 2:
          resp = 'KH tiềm năng';
          break;

        case 3:
          resp = 'KH tiềm năng cần follow up';
          break;

        case 4:
          resp = 'KH bận gọi lại sau';
          break;

        case 5:
          resp = 'KH không nghe máy';
          break;

        case 6:
          resp = 'KH đồng ý đặt lịch checkin';
          break;

        case 7:
          resp = 'KH đã đến checkin';
          break;

        case 8:
          resp = 'KH đã mua gói phí';
          break;

        case 9:
          resp = 'KH không có nhu cầu';
          break;

        case 10:
          resp = 'KH không tiềm năng';
          break;

        case 11:
          resp = 'KH đến hạn tái tục';
          break;

        case 12:
          resp = 'Danh sách đen';
          break;

        default:
          resp = 'KH mới';
          break;
      }

      return resp;
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/reports/report_01.vue?vue&type=template&id=53593ba8&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/reports/report_01.vue?vue&type=template&id=53593ba8& ***!
  \***********************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "animated fadeIn" }, [
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-lg-12" }, [
        _c(
          "div",
          { staticClass: "card" },
          [
            _c("loader", {
              attrs: { active: _vm.loading.processing, text: _vm.loading.text }
            }),
            _vm._v(" "),
            _vm._m(0),
            _vm._v(" "),
            _c("div", { staticClass: "card-body" }, [
              _c("div", { staticClass: "row" }, [
                _c("div", { staticClass: "form-group col-sm-3" }, [
                  _c("label", { attrs: { for: "name" } }, [_vm._v("Từ khóa")]),
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
                        _vm.$set(_vm.searchData, "keyword", $event.target.value)
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
                      _vm._v("Ngày tạo")
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
                        placeholder: "Chọn thời gian tìm kiếm từ ngày đến ngày"
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
                      staticClass: "btn btn-success",
                      on: {
                        click: function($event) {
                          return _vm.exportExcel()
                        }
                      }
                    },
                    [
                      _c("i", { staticClass: "fas fa-file-excel" }),
                      _vm._v(" Xuất báo cáo\n              ")
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
                ])
              ]),
              _vm._v(" "),
              _c("table", { staticClass: "table table-striped table-hover" }, [
                _vm._m(1),
                _vm._v(" "),
                _c(
                  "tbody",
                  _vm._l(_vm.imports, function(item, index) {
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
                      _c("td", [_vm._v(_vm._s(item.id))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.parent_name))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.student_name))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.student_year))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.source_name))]),
                      _vm._v(" "),
                      _c("td", [
                        _vm._v(_vm._s(_vm._f("getStatusName")(item.status)))
                      ]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.owner_name))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.branch_name))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.created_date))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.last_care_date))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.last_method))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.total_care))])
                    ])
                  }),
                  0
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
    ])
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "card-header" }, [
      _c("strong", [_vm._v(" Báo cáo thông tin khách hàng")])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c("th", [_vm._v("#")]),
        _vm._v(" "),
        _c("th", [_vm._v("Mã KH")]),
        _vm._v(" "),
        _c("th", [_vm._v("Tên KH")]),
        _vm._v(" "),
        _c("th", [_vm._v("Tên HS")]),
        _vm._v(" "),
        _c("th", [_vm._v("Năm sinh")]),
        _vm._v(" "),
        _c("th", [_vm._v("Nguồn")]),
        _vm._v(" "),
        _c("th", [_vm._v("Trạng thái")]),
        _vm._v(" "),
        _c("th", [_vm._v("TVV")]),
        _vm._v(" "),
        _c("th", [_vm._v("Trung tâm")]),
        _vm._v(" "),
        _c("th", [_vm._v("Ngày nhập data")]),
        _vm._v(" "),
        _c("th", [_vm._v("Ngày chăm sóc gần nhất")]),
        _vm._v(" "),
        _c("th", [_vm._v("Hình thức chăm sóc gần nhất")]),
        _vm._v(" "),
        _c("th", [_vm._v("Tổng số lần tương tác")])
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/coreui/src/views/reports/report_01.vue":
/*!**********************************************************!*\
  !*** ./resources/coreui/src/views/reports/report_01.vue ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _report_01_vue_vue_type_template_id_53593ba8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./report_01.vue?vue&type=template&id=53593ba8& */ "./resources/coreui/src/views/reports/report_01.vue?vue&type=template&id=53593ba8&");
/* harmony import */ var _report_01_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./report_01.vue?vue&type=script&lang=js& */ "./resources/coreui/src/views/reports/report_01.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _report_01_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _report_01_vue_vue_type_template_id_53593ba8___WEBPACK_IMPORTED_MODULE_0__["render"],
  _report_01_vue_vue_type_template_id_53593ba8___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/coreui/src/views/reports/report_01.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/coreui/src/views/reports/report_01.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/coreui/src/views/reports/report_01.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_report_01_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./report_01.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/reports/report_01.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_report_01_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/coreui/src/views/reports/report_01.vue?vue&type=template&id=53593ba8&":
/*!*****************************************************************************************!*\
  !*** ./resources/coreui/src/views/reports/report_01.vue?vue&type=template&id=53593ba8& ***!
  \*****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_report_01_vue_vue_type_template_id_53593ba8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./report_01.vue?vue&type=template&id=53593ba8& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/reports/report_01.vue?vue&type=template&id=53593ba8&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_report_01_vue_vue_type_template_id_53593ba8___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_report_01_vue_vue_type_template_id_53593ba8___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);