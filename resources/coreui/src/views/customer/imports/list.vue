<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Danh sách Import</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-3">
                <label for="ccmonth">Trạng thái</label>
                <select class="form-control" v-model="searchData.status">
                  <option value>Chọn trạng thái</option>
                  <option value="0">Lỗi</option>
                  <option value="1">Hoàn thành</option>
                </select>
              </div>
              <div class="form-group col-sm-12">
                <router-link class="btn btn-success" :to="'/imports/add'">
                  <i class="fas fa-upload"></i> Import
                </router-link>
                <button class="btn btn-info" type="submit" @click="search()">
                  <i class="fa fa-search"></i> Tìm kiếm
                </button>
                <button
                  class="btn btn-secondary"
                  type="reset"
                  @click="reset()"
                >
                  <i class="fas fa-undo-alt"></i> Reset
                </button>
              </div>
            </div>
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>STT</th>
                  <th>Tên File</th>
                  <th>Thời gian import</th>
                  <th>Người thực hiện</th>
                  <th>Thành công</th>
                  <th>Lỗi</th>
                  <th>Trạng thái</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in imports" :key="index">
                  <td>
                    {{ index + 1 + (pagination.cpage - 1) * pagination.limit }}
                  </td>
                  <td><a :href="item.file_link" target="blank">{{ item.file_name }}</a></td>
                  <td>{{ item.created_at }}</td>
                  <td>{{ item.creator_name }}</td>
                  <td>{{ item.count_success }}</td>
                  <td>{{ item.count_error }}</td>
                  <td>{{ item.status | getStatusName}}</td>
                  <td></td>
                </tr>
              </tbody>
            </table>
            <div class="text-center">
              <nav aria-label="Page navigation">
                <paging
                  :rootLink="pagination.url"
                  :id="pagination.id"
                  :listStyle="pagination.style"
                  :customClass="pagination.class"
                  :firstPage="pagination.spage"
                  :previousPage="pagination.ppage"
                  :nextPage="pagination.npage"
                  :lastPage="pagination.lpage"
                  :currentPage="pagination.cpage"
                  :pagesItems="pagination.total"
                  :pagesLimit="pagination.limit"
                  :pageList="pagination.pages"
                  :routing="changePage"
                ></paging>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
    <CModal
      :title="modal.title"
      :show.sync="modal.show"
      :color="modal.color"
      :closeOnBackdrop="modal.closeOnBackdrop"
    >
      {{ modal.body }}
      <template #header>
        <h5 class="modal-title">{{ modal.title }}</h5>
      </template>
      <template #footer>
        <CButton :color="'btn btn-' + modal.color" @click="exit" type="button"
          >Đóng</CButton
        >
      </template>
    </CModal>
  </div>
</template>

<script>
import axios from "axios";
import paging from "../../../components/Pagination";
import u from "../../../utilities/utility";
import loader from "../../../components/Loading";

export default {
  components: {
    loader: loader,
    paging: paging,
  },
  name: "List-Parent",
  data() {
    return {
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      searchData: {
        keyword: "",
        status: "",
        pagination: this.pagination
      },
      imports: [],
      pagination: {
        url: "/api/imports/list",
        id: "",
        style: "line",
        class: "",
        spage: 1,
        ppage: 1,
        npage: 0,
        lpage: 1,
        cpage: 1,
        total: 0,
        limit: 20,
        limitSource: [10, 20, 30, 40, 50],
        pages: [],
      },
      modal: {
        title: "THÔNG BÁO",
        show: false,
        color: "success",
        body: "Cập nhật khách hàng thành công",
        closeOnBackdrop: false,
      },
    };
  },
  created() {
    this.search();
  },
  methods: {
    reset() {
      location.reload();
    },
    search(a) {
      const data = {
        keyword: this.searchData.keyword,
        status: this.searchData.status,
      };
      const link = "/api/imports/list";

      this.loading.processing = true;
      u.p(link, data)
        .then((response) => {
          this.loading.processing = false;
          this.imports = response.data.list;
          this.pagination.spage = response.data.paging.spage;
          this.pagination.ppage = response.data.paging.ppage;
          this.pagination.npage = response.data.paging.npage;
          this.pagination.lpage = response.data.paging.lpage;
          this.pagination.cpage = response.data.paging.cpage;
          this.pagination.total = response.data.paging.total;
          this.pagination.limit = response.data.paging.limit;
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
    changePage(link) {
      const info = link
        .toString()
        .substr(this.pagination.url.length)
        .split("/");
      const page = info.length > 1 ? info[1] : 1;
      this.pagination.cpage = parseInt(page);
      this.search();
    },
    deleteItem(id) {
      u.g(`/api/imports/delete/${id}`)
        .then((response) => {
          this.loading.processing = false;
          if (response.status == 200) {
            this.modal.color = "success";
            this.modal.body = "Xóa khách hàng thành công";
            this.modal.show = true;
            this.search();
          }
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
    exit() {
      this.modal.show = false;
    },
  },
  filters: {
    getStatusName(value) {
      return value == 1 ? "Hoàn thành" : "Lỗi";
    },
  },
};
</script>
