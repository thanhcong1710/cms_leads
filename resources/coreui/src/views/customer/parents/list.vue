<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Danh sách khách hàng</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-3">
                <label for="name">Từ khóa</label>
                <input
                  class="form-control"
                  v-model="searchData.keyword"
                  type="text"
                  placeholder="Tên khách hàng, số điện thoại"
                />
              </div>
              <div class="form-group col-sm-3">
                <label for="ccmonth">Trạng thái</label>
                <select class="form-control" v-model="searchData.status">
                  <option value>Chọn trạng thái</option>
                  <option value="1">Data</option>
                  <option value="2">Enquiry</option>
                  <option value="3">S1</option>
                  <option value="4">S2</option>
                  <option value="5">Checkin</option>
                  <option value="6">New</option>
                  <option value="7">Renew</option>
                </select>
              </div>
              <div class="form-group col-sm-3">
                <label for="ccmonth">Người phụ trách</label>
                <p><select class="form-control" v-model="searchData.owner_id">
                  <option value="">Chọn người phụ trách</option>
                  <option :value ="item.id" v-for="(item, index) in users_manager" :key="index">{{item.name}} - {{item.hrm_id}}</option>
                </select></p>  
              </div>
              <div class="form-group col-sm-3">
                <label for="ccmonth">Lịch chăm sóc tiếp theo</label>
                  <date-picker
                    style="width:100%;"
                    v-model="searchData.dateRange"
                    :clearable="true"
                    :lang="datepickerOptions.lang"
                    range
                    format="YYYY-MM-DD"
                    id="apax-date-range"
                    placeholder="Chọn thời gian tìm kiếm từ ngày đến ngày"
                  ></date-picker>
              </div>
              <div class="form-group col-sm-12">
                <router-link class="btn btn-success" :to="'/parents/add'">
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
                  <th>Tên khách hàng</th>
                  <th>Số điện thoại</th>
                  <th>Email</th>
                  <th>Nguồn</th>
                  <th>Người phụ trách</th>
                  <th>Lần cuối liên hệ</th>
                  <th>Lịch chăm sóc</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in parents" :key="index">
                  <td>
                    {{ index + 1 + (pagination.cpage - 1) * pagination.limit }}
                  </td>
                  <td><router-link :to="`/parents/${item.id}/detail`"><a>{{ item.name }}</a></router-link></td>
                  <td>{{ item.mobile_1 }}</td>
                  <td>{{ item.email }}</td>
                  <td>{{ item.source_name }}</td>
                  <td>{{ item.owner_name }}</td>
                  <td>{{ item.last_care }}</td>
                  <td>{{ item.next_care_date }}</td>
                  <td>{{ item.status | getStatusName }}</td>
                  <td>
                    <router-link
                      class="btn btn-sm btn-success"
                      :to="`/parents/${item.id}/edit`"
                    >
                      <i class="fa fa-edit"></i> </router-link>
                    <button
                      class="btn btn-sm btn-danger"
                      type="button"
                      @click="deleteItem(item.id)"
                    >
                      <i class="fas fa-times"></i></button>
                    <router-link
                      class="btn btn-sm  btn-info"
                      :to="`/parents/${item.id}/detail`"
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
import paging from "../../../components/Pagination";
import u from "../../../utilities/utility";
import loader from "../../../components/Loading";
import DatePicker from "vue2-datepicker";

export default {
  components: {
    DatePicker,
    loader: loader,
    paging: paging,
  },
  name: "List-Parent",
  data() {
    return {
      datepickerOptions: {
        closed: true,
        value: "",
        minDate: "",
        lang: {
          days: ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
          months: [
            "Tháng 1",
            "Tháng 2",
            "Tháng 3",
            "Tháng 4",
            "Tháng 5",
            "Tháng 6",
            "Tháng 7",
            "Tháng 8",
            "Tháng 9",
            "Tháng 10",
            "Tháng 11",
            "Tháng 12"
          ]
        }
      },
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      searchData: {
        keyword: "",
        status: "",
        owner_id: "",
        pagination: this.pagination,
        dateRange: "",
      },
      users_manager:[],
      parents: [],
      pagination: {
        url: "/api/parents/list",
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
    u.g(`/api/user/get-users-manager`)
      .then(response => {
      this.users_manager = response.data
    })
    this.search();
  },
  methods: {
    reset() {
      location.reload();
    },
    search(a) {
      const startDate = this.searchData.dateRange!='' && this.searchData.dateRange[0] ?`${u.dateToString(this.searchData.dateRange[0])}`:''
      const endDate = this.searchData.dateRange!='' && this.searchData.dateRange[1] ?`${u.dateToString(this.searchData.dateRange[1])}`:''
      const data = {
        keyword: this.searchData.keyword,
        status: this.searchData.status,
        owner_id: this.searchData.owner_id,
        start_date:startDate,
        end_date:endDate
      };
      const link = "/api/parents/list";

      this.loading.processing = true;
      u.p(link, data)
        .then((response) => {
          this.loading.processing = false;
          this.parents = response.data.list;
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
      u.g(`/api/parents/delete/${id}`)
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
      let resp = ''
      switch (Number(value)) {
          case 1:
              resp = 'Data'
              break
          case 2:
              resp = 'Enquiry'
              break
          case 3:
              resp = 'S1'
              break
          case 4:
              resp = 'S2'
              break
          case 5:
              resp = 'Checkin'
              break
          case 6:
              resp = 'Enroll'
              break
          case 7:
              resp = 'Renew'
              break
          default:
              resp = 'Data'
              break
      }
      return resp
    },
  },
};
</script>
