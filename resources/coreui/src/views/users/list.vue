<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Danh sách nhân viên</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-3">
                <label for="name">Từ khóa</label>
                <input
                  class="form-control"
                  v-model="searchData.keyword"
                  type="text"
                  placeholder="Tên nhân viên, mã nhân viên"
                />
              </div>
              <div class="form-group col-sm-3">
                <label for="ccmonth">Trạng thái</label>
                <select class="form-control" v-model="searchData.status">
                  <option value>Chọn trạng thái</option>
                  <option value="0">Ngừng hoạt động</option>
                  <option value="1">Hoạt động</option>
                </select>
              </div>
               <div class="form-group col-sm-3">
                <label for="ccmonth">Vai trò</label>
                <select class="form-control" v-model="searchData.role_id">
                  <option value="">Chọn vai trò</option>
                  <option :value="role.id" v-for="(role,index) in roles" :key="index">{{role.name}}</option>
                </select>
              </div>
              <div class="form-group col-sm-12">
                <router-link class="btn btn-success" :to="'/users/add'">
                  <i class="fa fa-plus"></i> Thêm mới
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
                  <th>Trung tâm</th>
                  <th>Mã nhân viên</th>
                  <th>Tên nhân viên</th>
                  <th>Ngày tạo</th>
                  <th>Vai trò</th>
                  <th>Người quản lý</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in users" :key="index">
                  <td>
                    {{ index + 1 + (pagination.cpage - 1) * pagination.limit }}
                  </td>
                  <td>{{ item.branch_name }}</td>
                  <td>{{ item.hrm_id }}</td>
                  <td>{{ item.name }}</td>
                  <td>{{ item.created_at }}</td>
                  <td>{{ item.menuroles }}</td>
                  <td>{{ item.manager_name }}</td>
                  <td><CBadge :color="getBadge(item.status)">{{ getTitleStatus(item.status) }}</CBadge></td>
                  <td>
                    <router-link
                      class="btn btn-sm btn-success"
                      :to="`/users/${item.id}/edit`"
                    >
                      <i class="fa fa-edit"></i> </router-link>
                    <router-link
                      class="btn btn-sm  btn-info"
                      :to="`/users/${item.id}`"
                    >
                      <i class="fa fa-eye"></i></router-link>  
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
import paging from "../../components/Pagination";
import u from "../../utilities/utility";
import loader from "../../components/Loading";

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
      users_manager:[],
      users: [],
      pagination: {
        url: "/api/users/list",
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
    getBadge (status) {
      return status === 1 ? 'success' : 'danger'
    },
    getTitleStatus(status){
      return status === 1 ? 'Kích hoạt' : 'Không kích hoạt'
    },
    reset() {
      location.reload();
    },
    search(a) {
      const data = {
        keyword: this.searchData.keyword,
        status: this.searchData.status,
        role_id: this.searchData.role_id,
      };
      const link = "/api/users/list";

      this.loading.processing = true;
      u.p(link, data)
        .then((response) => {
          this.loading.processing = false;
          this.users = response.data.list;
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
  filters: {
    getStatusName(value) {
      return value == 1 ? "Hoạt động" : "Ngừng hoạt động";
    },
  },
};
</script>
