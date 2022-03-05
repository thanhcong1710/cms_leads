<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Danh sách mục tiêu tuần</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-6">
                <label for="name">Từ khóa</label>
                <input
                  class="form-control"
                  v-model="searchData.keyword"
                  type="text"
                  placeholder="Tên nhân viên, mã nhân viên"
                />
              </div>
              <div class="form-group col-sm-6">
                <label for="ccmonth">Tuần</label>
                  <vue-select
                        label="label"
                        placeholder="Chọn tuần"
                        :options="list_week"
                        v-model="week"
                        :searchable="true"
                        language="tv-VN"
                        :onChange="selectWeek"
                    ></vue-select>
              </div>
              <div class="form-group col-sm-12">
                <router-link class="btn btn-success" :to="'/setting/target/add'">
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
                  <th>Nhân viên</th>
                  <th>Tuần</th>
                  <th>Call</th>
                  <th>Talk time</th>
                  <th>Trial accept</th>
                  <th>Trial actual</th>
                  <th>New enroll</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in users" :key="index">
                  <td>
                    {{ index + 1 + (pagination.cpage - 1) * pagination.limit }}
                  </td>
                  <td>{{ item.user_label }}</td>
                  <td>{{ item.week_label }}</td>
                  <td>{{ item.call}}</td>
                  <td>{{ item.talk_time}}</td>
                  <td>{{ item.trial_accept}}</td>
                  <td>{{ item.trial_actual}}</td>
                  <td>{{ item.new_enroll}}</td>
                  <td>
                    <router-link
                      class="btn btn-sm btn-success"
                      :to="`/setting/target/${item.id}/edit`"
                      v-if="item.can_edit == 1"
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
import select from 'vue-select'

export default {
  components: {
    loader: loader,
    paging: paging,
    "vue-select": select,
  },
  name: "List-Parent",
  data() {
    return {
      list_week:[],
      week:'',
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      searchData: {
        keyword: "",
        week_id: "",
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
    u.g(`/api/report_week?type=all`)
      .then(response => {
      this.list_week = response.data
    })
    this.search();
  },
  methods: {
    selectWeek(data = null){
      if (data && typeof data === 'object') {
        const week_id = data.id
        this.searchData.week_id = week_id
      }else{
        this.searchData.week_id = ""
      }
    },
    reset() {
      location.reload();
    },
    search(a) {
      const data = {
        keyword: this.searchData.keyword,
        week_id: this.searchData.week_id,
      };
      const link = "/api/target/list";

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
      return value == 1 ? "Kích hoạt" : "Không kích hoạt";
    },
  },
};
</script>
