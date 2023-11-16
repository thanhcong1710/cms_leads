<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Danh sách dải đầu số</strong>
          </div>
          <div class="card-body">
            <div class="row">
              
              <div class="form-group col-sm-12">
                <router-link class="btn btn-success" :to="'/setting/ext_phone/add'">
                  <i class="fa fa-plus"></i> Thêm mới
                </router-link>
              </div>
            </div>
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>Bắt đầu</th>
                  <th>Kết thúc</th>
                  <th>Số gọi ra</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in lists" :key="index">
                  <td>{{ item.ext_start }}</td>
                  <td>{{ item.ext_end}}</td>
                  <td>{{ item.ext_phone }}</td>
                  <td>
                    <router-link
                      class="btn btn-sm btn-success"
                      :to="`/setting/ext_phone/${item.id}/edit`"
                    >
                      <i class="fa fa-edit"></i> </router-link>  
                  </td>
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
        role_id: "",
        pagination: this.pagination
      },
      lists: [],
      pagination: {
        url: "/api/ext_phone/list",
        id: "",
        style: "line",
        class: "",
        spage: 1,
        ppage: 1,
        npage: 0,
        lpage: 1,
        cpage: 1,
        total: 0,
        limit: 50,
        limitSource: [10, 20, 30, 40, 50],
        pages: [],
      },
      modal: {
        title: "THÔNG BÁO",
        show: false,
        color: "success",
        body: "Cập nhật dải đầu số thành công",
        closeOnBackdrop: false,
      },
      roles:[]
    };
  },
  created() {
    u.g('/api/roles').then((response) => {
          this.roles = response.data;
    }).catch((e) => {
    });
    this.search();
  },
  methods: {
    reset() {
      location.reload();
    },
    search(a) {
      const data = {
      };
      const link = "/api/ext_phone/list";

      this.loading.processing = true;
      u.p(link, data)
        .then((response) => {
          this.loading.processing = false;
          this.lists = response.data.list;
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
    exit() {
      this.modal.show = false;
    },
  },
};
</script>
