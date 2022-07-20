(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[23],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/reports/report_03.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/reports/report_03.vue?vue&type=script&lang=js& ***!
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
/* harmony import */ var vue_multiselect__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vue-multiselect */ "./resources/coreui/node_modules/vue-multiselect/dist/vue-multiselect.min.js");
/* harmony import */ var vue_multiselect__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(vue_multiselect__WEBPACK_IMPORTED_MODULE_4__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    paging: _components_Pagination__WEBPACK_IMPORTED_MODULE_0__["default"],
    Multiselect: vue_multiselect__WEBPACK_IMPORTED_MODULE_4___default.a
  },
  name: "List-Parent",
  data: function data() {
    return {
      list_type_status: [{
        id: 1,
        label: 'Nghe máy'
      }, {
        id: 2,
        label: 'Không nghe máy'
      }, {
        id: 3,
        label: 'Máy bận'
      }],
      list_branches: [],
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false
      },
      searchData: {
        // from_date:"",
        // to_date:"",
        type_status: "",
        branch_id: "",
        type_date: 1,
        keyword: "",
        pagination: this.pagination
      },
      imports: [],
      pagination: {
        url: "/api/reports/03",
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
      }
    };
  },
  created: function created() {
    var _this = this;

    _utilities_utility__WEBPACK_IMPORTED_MODULE_1__["default"].g("/api/branches").then(function (response) {
      _this.list_branches = response.data;
    });
    this.search(0);
  },
  methods: {
    reset: function reset() {
      location.reload();
    },
    search: function search() {
      var _this2 = this;

      var a = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 1;
      var ids_branch_id = [];
      this.searchData.branch_id = _utilities_utility__WEBPACK_IMPORTED_MODULE_1__["default"].is.obj(this.searchData.branch_id) ? [this.searchData.branch_id] : this.searchData.branch_id;

      if (this.searchData.branch_id.length) {
        this.searchData.branch_id.map(function (item) {
          ids_branch_id.push(item.id);
        });
      }

      var ids_type_status = [];
      this.searchData.type_status = _utilities_utility__WEBPACK_IMPORTED_MODULE_1__["default"].is.obj(this.searchData.type_status) ? [this.searchData.type_status] : this.searchData.type_status;

      if (this.searchData.type_status.length) {
        this.searchData.type_status.map(function (item) {
          ids_type_status.push(item.id);
        });
      }

      var data = {
        from_date: a ? document.getElementById('from_date').value : "",
        to_date: a ? document.getElementById('to_date').value : "",
        branch_id: ids_branch_id,
        type_date: this.searchData.type_date,
        type_status: ids_type_status,
        keyword: this.searchData.keyword,
        pagination: this.pagination
      };
      var link = "/api/reports/03";
      this.loading.processing = true;
      _utilities_utility__WEBPACK_IMPORTED_MODULE_1__["default"].p(link, data).then(function (response) {
        _this2.loading.processing = false;
        _this2.imports = response.data.list;
        _this2.pagination.spage = response.data.paging.spage;
        _this2.pagination.ppage = response.data.paging.ppage;
        _this2.pagination.npage = response.data.paging.npage;
        _this2.pagination.lpage = response.data.paging.lpage;
        _this2.pagination.cpage = response.data.paging.cpage;
        _this2.pagination.total = response.data.paging.total;
        _this2.pagination.limit = response.data.paging.limit;
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
      var ids_branch_id = '';
      this.searchData.branch_id = _utilities_utility__WEBPACK_IMPORTED_MODULE_1__["default"].is.obj(this.searchData.branch_id) ? [this.searchData.branch_id] : this.searchData.branch_id;

      if (this.searchData.branch_id.length) {
        this.searchData.branch_id.map(function (item) {
          ids_branch_id += ids_branch_id ? '-' + item.id : item.id;
        });
      }

      var ids_type_status = '';
      this.searchData.type_status = _utilities_utility__WEBPACK_IMPORTED_MODULE_1__["default"].is.obj(this.searchData.type_status) ? [this.searchData.type_status] : this.searchData.type_status;

      if (this.searchData.type_status.length) {
        this.searchData.type_status.map(function (item) {
          ids_type_status += ids_type_status ? '-' + item.id : item.id;
        });
      }

      var url = "/api/export/report03/";
      this.key = '';
      this.value = '';

      if (this.searchData.keyword) {
        this.key += "keyword,";
        this.value += this.searchData.keyword + ",";
      }

      if (ids_type_status) {
        this.key += "type_status,";
        this.value += ids_type_status + ",";
      }

      if (this.searchData.type_date) {
        this.key += "type_date,";
        this.value += this.searchData.type_date + ",";
      }

      if (ids_branch_id) {
        this.key += "branch_id,";
        this.value += ids_branch_id + ",";
      }

      if (document.getElementById('from_date').value) {
        this.key += "from_date,";
        this.value += document.getElementById('from_date').value + ",";
      }

      if (document.getElementById('to_date').value) {
        this.key += "to_date,";
        this.value += document.getElementById('to_date').value + ",";
      }

      this.key = this.key ? this.key.substring(0, this.key.length - 1) : '_';
      this.value = this.value ? this.value.substring(0, this.value.length - 1) : "_";
      url += this.key + "/" + this.value + "?token=".concat(localStorage.getItem("api_token"));
      window.open(url, '_blank');
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/reports/report_03.vue?vue&type=style&index=0&id=5320dda4&scoped=true&lang=css&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/reports/report_03.vue?vue&type=style&index=0&id=5320dda4&scoped=true&lang=css& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.search_date_type label[data-v-5320dda4]{\n  margin-right: 20px;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/reports/report_03.vue?vue&type=style&index=0&id=5320dda4&scoped=true&lang=css&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/reports/report_03.vue?vue&type=style&index=0&id=5320dda4&scoped=true&lang=css& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./report_03.vue?vue&type=style&index=0&id=5320dda4&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/reports/report_03.vue?vue&type=style&index=0&id=5320dda4&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/reports/report_03.vue?vue&type=template&id=5320dda4&scoped=true&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/reports/report_03.vue?vue&type=template&id=5320dda4&scoped=true& ***!
  \***********************************************************************************************************************************************************************************************************************************/
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
                _vm._m(1),
                _vm._v(" "),
                _vm._m(2),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "form-group col-sm-12 search_date_type" },
                  [
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.searchData.type_date,
                          expression: "searchData.type_date"
                        }
                      ],
                      attrs: {
                        type: "radio",
                        id: "type1",
                        name: "type_date",
                        value: "1"
                      },
                      domProps: {
                        checked: _vm._q(_vm.searchData.type_date, "1")
                      },
                      on: {
                        change: [
                          function($event) {
                            return _vm.$set(_vm.searchData, "type_date", "1")
                          },
                          function($event) {
                            return _vm.search()
                          }
                        ]
                      }
                    }),
                    _vm._v(" "),
                    _c("label", { attrs: { for: "type1" } }, [
                      _vm._v("Hôm nay")
                    ]),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.searchData.type_date,
                          expression: "searchData.type_date"
                        }
                      ],
                      attrs: {
                        type: "radio",
                        id: "type2",
                        name: "type_date",
                        value: "2"
                      },
                      domProps: {
                        checked: _vm._q(_vm.searchData.type_date, "2")
                      },
                      on: {
                        change: [
                          function($event) {
                            return _vm.$set(_vm.searchData, "type_date", "2")
                          },
                          function($event) {
                            return _vm.search()
                          }
                        ]
                      }
                    }),
                    _vm._v(" "),
                    _c("label", { attrs: { for: "type2" } }, [
                      _vm._v("Hôm qua")
                    ]),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.searchData.type_date,
                          expression: "searchData.type_date"
                        }
                      ],
                      attrs: {
                        type: "radio",
                        id: "type3",
                        name: "type_date",
                        value: "3"
                      },
                      domProps: {
                        checked: _vm._q(_vm.searchData.type_date, "3")
                      },
                      on: {
                        change: [
                          function($event) {
                            return _vm.$set(_vm.searchData, "type_date", "3")
                          },
                          function($event) {
                            return _vm.search()
                          }
                        ]
                      }
                    }),
                    _vm._v(" "),
                    _c("label", { attrs: { for: "type3" } }, [
                      _vm._v("Tuần này")
                    ]),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.searchData.type_date,
                          expression: "searchData.type_date"
                        }
                      ],
                      attrs: {
                        type: "radio",
                        id: "type4",
                        name: "type_date",
                        value: "4"
                      },
                      domProps: {
                        checked: _vm._q(_vm.searchData.type_date, "4")
                      },
                      on: {
                        change: [
                          function($event) {
                            return _vm.$set(_vm.searchData, "type_date", "4")
                          },
                          function($event) {
                            return _vm.search()
                          }
                        ]
                      }
                    }),
                    _vm._v(" "),
                    _c("label", { attrs: { for: "type4" } }, [
                      _vm._v("Tuần trước")
                    ]),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.searchData.type_date,
                          expression: "searchData.type_date"
                        }
                      ],
                      attrs: {
                        type: "radio",
                        id: "type5",
                        name: "type_date",
                        value: "5"
                      },
                      domProps: {
                        checked: _vm._q(_vm.searchData.type_date, "5")
                      },
                      on: {
                        change: [
                          function($event) {
                            return _vm.$set(_vm.searchData, "type_date", "5")
                          },
                          function($event) {
                            return _vm.search()
                          }
                        ]
                      }
                    }),
                    _vm._v(" "),
                    _c("label", { attrs: { for: "type5" } }, [
                      _vm._v("Tháng này")
                    ]),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.searchData.type_date,
                          expression: "searchData.type_date"
                        }
                      ],
                      attrs: {
                        type: "radio",
                        id: "type6",
                        name: "type_date",
                        value: "6"
                      },
                      domProps: {
                        checked: _vm._q(_vm.searchData.type_date, "6")
                      },
                      on: {
                        change: [
                          function($event) {
                            return _vm.$set(_vm.searchData, "type_date", "6")
                          },
                          function($event) {
                            return _vm.search()
                          }
                        ]
                      }
                    }),
                    _vm._v(" "),
                    _c("label", { attrs: { for: "type6" } }, [
                      _vm._v("Tháng trước")
                    ])
                  ]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "form-group col-sm-4" },
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
                          { attrs: { slot: "noResult" }, slot: "noResult" },
                          [_vm._v("Không tìm thấy dữ liệu")]
                        )
                      ]
                    )
                  ],
                  1
                ),
                _vm._v(" "),
                _c("div", { staticClass: "form-group col-sm-4" }, [
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
                    attrs: { type: "text", placeholder: "Tên mã nhân viên" },
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
                  { staticClass: "form-group col-sm-4" },
                  [
                    _c("label", { attrs: { for: "name" } }, [
                      _vm._v("Trạng thái cuộc gọi")
                    ]),
                    _vm._v(" "),
                    _c(
                      "multiselect",
                      {
                        attrs: {
                          placeholder: "Chọn trạng thái",
                          "select-label": "Chọn trạng thái",
                          options: _vm.list_type_status,
                          label: "label",
                          "close-on-select": false,
                          "hide-selected": true,
                          multiple: true,
                          searchable: true,
                          "track-by": "id"
                        },
                        model: {
                          value: _vm.searchData.type_status,
                          callback: function($$v) {
                            _vm.$set(_vm.searchData, "type_status", $$v)
                          },
                          expression: "searchData.type_status"
                        }
                      },
                      [
                        _c(
                          "span",
                          { attrs: { slot: "noResult" }, slot: "noResult" },
                          [_vm._v("Không tìm thấy dữ liệu")]
                        )
                      ]
                    )
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
                _vm._m(3),
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
                      _c("td", [_vm._v(_vm._s(item.branch_name))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.sip_name))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.total_inbound))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.total_outbound))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.duration_inbound))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.duration_outbound))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.total_duration_inbound))]),
                      _vm._v(" "),
                      _c("td", [_vm._v(_vm._s(item.total_duration_outbound))])
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
      _c("strong", [_vm._v(" Báo cáo cuộc gọi")])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "form-group col-sm-4" }, [
      _c("label", { attrs: { for: "name" } }, [
        _vm._v("Ngày giờ bắt đầu tìm kiếm")
      ]),
      _vm._v(" "),
      _c("input", {
        staticClass: "form-control",
        attrs: { type: "datetime-local", value: "", id: "from_date" }
      })
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "form-group col-sm-4" }, [
      _c("label", { attrs: { for: "name" } }, [
        _vm._v("Ngày giờ kết thúc tìm kiếm")
      ]),
      _vm._v(" "),
      _c("input", {
        staticClass: "form-control",
        attrs: { type: "datetime-local", value: "", id: "to_date" }
      })
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
        _c("th", [_vm._v("Trung tâm")]),
        _vm._v(" "),
        _c("th", [_vm._v("Tên máy nhánh")]),
        _vm._v(" "),
        _c("th", [_vm._v("Tổng gọi vào")]),
        _vm._v(" "),
        _c("th", [_vm._v("Tổng gọi ra")]),
        _vm._v(" "),
        _c("th", [_vm._v("Thời gian gọi vào TB")]),
        _vm._v(" "),
        _c("th", [_vm._v("Thời gian gọi ra TB")]),
        _vm._v(" "),
        _c("th", [_vm._v("Tổng thời gian gọi vào")]),
        _vm._v(" "),
        _c("th", [_vm._v("Tổng thời gian gọi ra")])
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/coreui/src/views/reports/report_03.vue":
/*!**********************************************************!*\
  !*** ./resources/coreui/src/views/reports/report_03.vue ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _report_03_vue_vue_type_template_id_5320dda4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./report_03.vue?vue&type=template&id=5320dda4&scoped=true& */ "./resources/coreui/src/views/reports/report_03.vue?vue&type=template&id=5320dda4&scoped=true&");
/* harmony import */ var _report_03_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./report_03.vue?vue&type=script&lang=js& */ "./resources/coreui/src/views/reports/report_03.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _report_03_vue_vue_type_style_index_0_id_5320dda4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./report_03.vue?vue&type=style&index=0&id=5320dda4&scoped=true&lang=css& */ "./resources/coreui/src/views/reports/report_03.vue?vue&type=style&index=0&id=5320dda4&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _report_03_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _report_03_vue_vue_type_template_id_5320dda4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _report_03_vue_vue_type_template_id_5320dda4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "5320dda4",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/coreui/src/views/reports/report_03.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/coreui/src/views/reports/report_03.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/coreui/src/views/reports/report_03.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_report_03_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./report_03.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/reports/report_03.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_report_03_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/coreui/src/views/reports/report_03.vue?vue&type=style&index=0&id=5320dda4&scoped=true&lang=css&":
/*!*******************************************************************************************************************!*\
  !*** ./resources/coreui/src/views/reports/report_03.vue?vue&type=style&index=0&id=5320dda4&scoped=true&lang=css& ***!
  \*******************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_report_03_vue_vue_type_style_index_0_id_5320dda4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader!../../../../../node_modules/css-loader??ref--6-1!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/src??ref--6-2!../../../../../node_modules/vue-loader/lib??vue-loader-options!./report_03.vue?vue&type=style&index=0&id=5320dda4&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/reports/report_03.vue?vue&type=style&index=0&id=5320dda4&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_report_03_vue_vue_type_style_index_0_id_5320dda4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_report_03_vue_vue_type_style_index_0_id_5320dda4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_report_03_vue_vue_type_style_index_0_id_5320dda4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_report_03_vue_vue_type_style_index_0_id_5320dda4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/coreui/src/views/reports/report_03.vue?vue&type=template&id=5320dda4&scoped=true&":
/*!*****************************************************************************************************!*\
  !*** ./resources/coreui/src/views/reports/report_03.vue?vue&type=template&id=5320dda4&scoped=true& ***!
  \*****************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_report_03_vue_vue_type_template_id_5320dda4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./report_03.vue?vue&type=template&id=5320dda4&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/reports/report_03.vue?vue&type=template&id=5320dda4&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_report_03_vue_vue_type_template_id_5320dda4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_report_03_vue_vue_type_template_id_5320dda4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);