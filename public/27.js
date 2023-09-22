(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[27],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/cameraAI/students.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/cameraAI/students.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************/
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
        dateRange: "",
        status: ""
      },
      users_manager: [],
      parents: [],
      pagination: {
        url: "/api/camera-ai/list-student",
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
        title: "CẬP NHẬT AVATAR",
        show: false,
        color: "info",
        body: "Cập nhật học sinh thành công",
        attached_file: "",
        student_id: ""
      },
      modal_img: {
        title: "AVATAR",
        show: false,
        color: "info"
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

      var ids_branch_id = [];
      this.searchData.branch_id = _utilities_utility__WEBPACK_IMPORTED_MODULE_2__["default"].is.obj(this.searchData.branch_id) ? [this.searchData.branch_id] : this.searchData.branch_id;

      if (this.searchData.branch_id.length) {
        this.searchData.branch_id.map(function (item) {
          ids_branch_id.push(item.id);
        });
      }

      var data = {
        status: this.searchData.status,
        keyword: this.searchData.keyword,
        branch_id: ids_branch_id,
        pagination: this.pagination
      };
      var link = "/api/camera-ai/list-student";
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
    showModalUpdate: function showModalUpdate(data) {
      this.modal.show = true;
      this.modal.attached_file = '';
      this.modal.student_id = data.student_id;
    },
    exit: function exit() {
      this.modal.show = false;
    },
    fileChanged: function fileChanged(e) {
      var _this3 = this;

      var fileReader = new FileReader();
      var fileName = e.target.value.split("\\").pop();
      this.file_name = fileName;
      fileReader.readAsDataURL(e.target.files[0]);

      fileReader.onload = function (e) {
        _this3.modal.attached_file = e.target.result;
      };
    },
    updateAvatar: function updateAvatar() {
      var _this4 = this;

      if (this.modal.attached_file) {
        this.modal.show = false;
        this.loading.processing = true;
        var dataUpload = {
          files: this.modal.attached_file,
          student_id: this.modal.student_id
        };
        _utilities_utility__WEBPACK_IMPORTED_MODULE_2__["default"].p("api/camera-ai/update-student", dataUpload).then(function (response) {
          _this4.loading.processing = false;

          if (response.data.error) {
            alert(response.data.message);
          }

          _this4.search();
        })["catch"](function (e) {
          return console.log(e);
        });
      }
    },
    pushCameraAI: function pushCameraAI(data) {
      var _this5 = this;

      this.loading.processing = true;
      var params = {
        student_id: data.student_id
      };
      _utilities_utility__WEBPACK_IMPORTED_MODULE_2__["default"].p("api/camera-ai/push-data", params).then(function (response) {
        _this5.loading.processing = false;

        if (response.data.error) {
          alert(response.data.message);
        }

        _this5.search();
      })["catch"](function (e) {
        return console.log(e);
      });
    },
    pushAllCameraAI: function pushAllCameraAI() {
      var _this6 = this;

      this.loading.processing = true;
      _utilities_utility__WEBPACK_IMPORTED_MODULE_2__["default"].g("api/camera-ai/push-all-data").then(function (response) {
        _this6.loading.processing = false;

        if (response.data.error) {
          alert(response.data.message);
        }

        _this6.search();
      })["catch"](function (e) {
        return console.log(e);
      });
    },
    showModalImg: function showModalImg(img_url) {
      if (img_url) {
        this.modal_img.img_url = img_url;
        this.modal_img.show = true;
      }
    }
  },
  filters: {}
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/cameraAI/students.vue?vue&type=template&id=75ed15ea&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/coreui/src/views/cameraAI/students.vue?vue&type=template&id=75ed15ea& ***!
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
                        placeholder: "Tên học sinh, mã học sinh"
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
                  _c("div", { staticClass: "form-group col-sm-3" }, [
                    _c("label", { attrs: { for: "ccmonth" } }, [
                      _vm._v("Trạng thái")
                    ]),
                    _vm._v(" "),
                    _c(
                      "select",
                      {
                        directives: [
                          {
                            name: "model",
                            rawName: "v-model",
                            value: _vm.searchData.status,
                            expression: "searchData.status"
                          }
                        ],
                        staticClass: "form-control",
                        on: {
                          change: function($event) {
                            var $$selectedVal = Array.prototype.filter
                              .call($event.target.options, function(o) {
                                return o.selected
                              })
                              .map(function(o) {
                                var val = "_value" in o ? o._value : o.value
                                return val
                              })
                            _vm.$set(
                              _vm.searchData,
                              "status",
                              $event.target.multiple
                                ? $$selectedVal
                                : $$selectedVal[0]
                            )
                          }
                        }
                      },
                      [
                        _c("option", { attrs: { value: "" } }, [
                          _vm._v("Chọn trạng thái")
                        ]),
                        _vm._v(" "),
                        _c("option", { attrs: { value: "2" } }, [
                          _vm._v("Chưa có faceID")
                        ]),
                        _vm._v(" "),
                        _c("option", { attrs: { value: "1" } }, [
                          _vm._v("Đã có faceID")
                        ])
                      ]
                    )
                  ]),
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
                    ),
                    _vm._v(" "),
                    _c(
                      "button",
                      {
                        staticClass: "btn btn-warning",
                        on: {
                          click: function($event) {
                            return _vm.pushAllCameraAI()
                          }
                        }
                      },
                      [
                        _c("i", { staticClass: "fas fa-upload" }),
                        _vm._v(" Đồng bộ dữ liệu CameraAI")
                      ]
                    )
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "wrapper2" }, [
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
                            _c("td", [
                              _c("img", {
                                staticClass: "c-avatar-img ",
                                staticStyle: {
                                  width: "40px",
                                  cursor: "pointer"
                                },
                                attrs: {
                                  src: item.avatar_url
                                    ? item.avatar_url
                                    : "img/avatars/avatar.png"
                                },
                                on: {
                                  click: function($event) {
                                    return _vm.showModalImg(item.avatar_url)
                                  }
                                }
                              })
                            ]),
                            _vm._v(" "),
                            _c("td", [_vm._v(_vm._s(item.branch_name))]),
                            _vm._v(" "),
                            _c("td", [_vm._v(_vm._s(item.name))]),
                            _vm._v(" "),
                            _c("td", [_vm._v(_vm._s(item.crm_id))]),
                            _vm._v(" "),
                            _c("td", [_vm._v(_vm._s(item.face_id))]),
                            _vm._v(" "),
                            _c("td", [
                              _c(
                                "button",
                                {
                                  staticClass: "btn btn-sm btn-success",
                                  on: {
                                    click: function($event) {
                                      return _vm.showModalUpdate(item)
                                    }
                                  }
                                },
                                [_c("i", { staticClass: "fa fa-edit" })]
                              ),
                              _vm._v(" "),
                              _c(
                                "button",
                                {
                                  staticClass: "btn btn-sm btn-warning",
                                  on: {
                                    click: function($event) {
                                      return _vm.pushCameraAI(item)
                                    }
                                  }
                                },
                                [_c("i", { staticClass: "fas fa-upload" })]
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
                      attrs: { color: "btn btn-success", type: "button" },
                      on: { click: _vm.updateAvatar }
                    },
                    [_vm._v("Lưu")]
                  ),
                  _vm._v(" "),
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
        [
          _c("input", {
            staticClass: "form-control",
            attrs: { type: "file", id: "fileUploadExcel" },
            on: { change: _vm.fileChanged }
          })
        ]
      ),
      _vm._v(" "),
      _c(
        "CModal",
        {
          attrs: {
            title: _vm.modal_img.title,
            show: _vm.modal_img.show,
            color: _vm.modal_img.color
          },
          on: {
            "update:show": function($event) {
              return _vm.$set(_vm.modal_img, "show", $event)
            }
          },
          scopedSlots: _vm._u([
            {
              key: "header",
              fn: function() {
                return [
                  _c("h5", { staticClass: "modal-title" }, [
                    _vm._v(_vm._s(_vm.modal_img.title))
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
                        color: "btn btn-" + _vm.modal_img.color,
                        type: "button"
                      },
                      on: {
                        click: function($event) {
                          _vm.modal_img.show = false
                        }
                      }
                    },
                    [_vm._v("Đóng")]
                  )
                ]
              },
              proxy: true
            }
          ])
        },
        [_c("img", { attrs: { src: _vm.modal_img.img_url, width: "100%" } })]
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
      _c("strong", [_vm._v("Danh sách học sinh")])
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
        _c("th", [_vm._v("Avatar")]),
        _vm._v(" "),
        _c("th", [_vm._v("Trung tâm")]),
        _vm._v(" "),
        _c("th", [_vm._v("Tên học sinh")]),
        _vm._v(" "),
        _c("th", [_vm._v("Mã CRM")]),
        _vm._v(" "),
        _c("th", [_vm._v("Face ID")]),
        _vm._v(" "),
        _c("th", [_vm._v("Thao tác")])
      ])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/coreui/src/views/cameraAI/students.vue":
/*!**********************************************************!*\
  !*** ./resources/coreui/src/views/cameraAI/students.vue ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _students_vue_vue_type_template_id_75ed15ea___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./students.vue?vue&type=template&id=75ed15ea& */ "./resources/coreui/src/views/cameraAI/students.vue?vue&type=template&id=75ed15ea&");
/* harmony import */ var _students_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./students.vue?vue&type=script&lang=js& */ "./resources/coreui/src/views/cameraAI/students.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _students_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _students_vue_vue_type_template_id_75ed15ea___WEBPACK_IMPORTED_MODULE_0__["render"],
  _students_vue_vue_type_template_id_75ed15ea___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/coreui/src/views/cameraAI/students.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/coreui/src/views/cameraAI/students.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/coreui/src/views/cameraAI/students.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_students_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../node_modules/vue-loader/lib??vue-loader-options!./students.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/cameraAI/students.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_students_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/coreui/src/views/cameraAI/students.vue?vue&type=template&id=75ed15ea&":
/*!*****************************************************************************************!*\
  !*** ./resources/coreui/src/views/cameraAI/students.vue?vue&type=template&id=75ed15ea& ***!
  \*****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_students_vue_vue_type_template_id_75ed15ea___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib??vue-loader-options!./students.vue?vue&type=template&id=75ed15ea& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/coreui/src/views/cameraAI/students.vue?vue&type=template&id=75ed15ea&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_students_vue_vue_type_template_id_75ed15ea___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_students_vue_vue_type_template_id_75ed15ea___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);