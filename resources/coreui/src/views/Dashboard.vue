<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Dashboard</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <!-- <div class="form-group col-sm-3">
                <label for="ccmonth">Loại cuộc gọi</label>
                <select class="form-control" v-model="searchData.type">
                    <option value="">Chọn tất cả</option>
                    <option value="1">Gọi vào</option>
                    <option value="2">Gọi ra</option>
                </select>
              </div>
              <div class="form-group col-sm-3">
                <label for="ccmonth">Trạng thái</label>
                <select class="form-control" v-model="searchData.status">
                    <option value="">Chọn trạng thái</option>
                    <option value="1">Trả lời</option>
                    <option value="2">Khai thác</option>
                    <option value="3">Đồng ý đặt lịch</option>
                </select>
              </div> -->
              <div class="form-group col-sm-3">
                <label for="ccmonth">Nhân viên</label>
                <p><select class="form-control" v-model="searchData.owner_id">
                  <option value="">Chọn nhân viên</option>
                  <option :value ="item.id" v-for="(item, index) in users_manager" :key="index">{{item.name}} - {{item.hrm_id}}</option>
                </select></p>  
              </div>
              <div class="form-group col-sm-3">
                <label for="ccmonth">Thời gian</label>
                  <date-picker
                    style="width:100%;"
                    v-model="searchData.dateRange"
                    :clearable="true"
                    :lang="datepickerOptions.lang"
                    range
                    format="YYYY-MM-DD"
                    id="apax-date-range"
                    placeholder="Chọn thời gian từ ngày đến ngày"
                  ></date-picker>
              </div>
              <div class="form-group col-sm-12">
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
                <button
                  class="btn btn-success"
                  @click="exportExcel()"
                >
                  <i class="fa fa-file-excel-o"></i> Export
                </button>
              </div>
            </div>
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>STT</th>
                  <th>Nhân viên</th>
                  <th>Trung tâm</th>
                  <th>Tổng số cuộc gọi</th>
                  <th>Trả lời</th>
                  <th>Không trả lời</th>
                  <th>Gọi nhỡ</th>
                  <th>Gọi vào</th>
                  <th>Gọi ra</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in parents" :key="index">
                  <td>
                    {{ index + 1 + (pagination.cpage - 1) * pagination.limit }}
                  </td>
                  <td>{{ item.name }} - {{item.hrm_id}}</td>
                  <td>{{ item.branch_name }}</td>
                  <td>{{ item.tongcuocgoi }}</td>
                  <td>{{ item.traloi }}</td>
                  <td>{{ item.khongtraloi }}</td>
                  <td>{{ item.goinho }}</td>
                  <td>{{ item.vao }}</td>
                  <td>{{ item.ra }}</td>
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
                <div class="last page" style="width: 60px;float: left;height: 30px;">
                  <select v-model="pagination.limit" style="width: 100%;height: 100%;" @change="search()">
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                    <option value="300">300</option>
                  </select>
                </div>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import paging from "../../../components/Pagination";
import u from "../../../utilities/utility";
import loader from "../../../components/Loading";
import DatePicker from "vue2-datepicker";
import Multiselect from "vue-multiselect";
import saveAs from "file-saver";

export default {
  components: {
    DatePicker,
    loader: loader,
    paging: paging,
    Multiselect,
    saveAs,
  },
  name: "List-Parent",
  data() {
    return {
      checked_list: [],
      temp: [],
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
        type: "",
        status: "",
        owner_id: "",
        pagination: this.pagination,
        dateRange: "",
      },
      users_manager:[],
      parents: [],
      pagination: {
        url: "/api/dashboard/list",
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
        owner_id: this.searchData.owner_id,
        start_date:startDate,
        end_date:endDate,
        pagination:this.pagination,
        type_seach:this.searchData.type_seach
      };
      const link = "/api/dashboard/list";

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
    exportExcel() {
      this.loading.processing = true
      const params = {
        owner_id: this.searchData.owner_id,
        start_date:startDate,
        end_date:endDate,
        pagination:this.pagination,
        type_seach:this.searchData.type_seach
      };
      var urlApi = "/api/exel/dashboard"
      u.g(urlApi, params)
        .then(response => {
          saveAs(response, "THÔNG TIN THỐNG KÊ DỮ LIỆU CUỘC GỌI.xlsx");
          this.loading.processing = false;
        })
        .catch(e => {
          this.loading.processing = false;
        });
    },
  },
  filters: {
  },
};
</script>
<style scoped>
.bg-danger {
    color: #fff;
}
</style>
