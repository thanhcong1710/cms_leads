<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Danh sách sự kiện</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-3" v-if="list_branches.length > 1">
                <label for="name">Trung tâm</label>
                <multiselect
                  placeholder="Chọn trung tâm"
                  select-label="Chọn trung tâm"
                  v-model="searchData.branch_id"
                  :options="list_branches"
                  label="name"
                  :close-on-select="false"
                  :hide-selected="true"
                  :multiple="true"
                  :searchable="true"
                  track-by="id"
                >
                  <span slot="noResult">Không tìm thấy dữ liệu</span>
                </multiselect>
              </div>
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
                <label for="ccmonth">Thời gian</label>
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
            <div>
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>STT</th>
                    <th>Trung tâm</th>
                    <th>Học sinh</th>
                    <th>Thời gian checkin</th>
                    <th>Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in parents" :key="index">
                    <td>
                      {{ index + 1 + (pagination.cpage - 1) * pagination.limit }}
                    </td>
                    <td>{{ item.branch_name }}</td>
                    <td>{{ item.student_name }}</td>
                    <td>{{ item.date }}</td>
                    <td><button class="btn btn-sm btn-info" @click="showModalImg(item.detected_image_url)"><i class="fa fa-eye"></i> </button></td>
                  </tr>
                </tbody>
              </table>
            </div>
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
    <CModal
      :title="modal.title"
      :show.sync="modal.show"
      :color="modal.color"
    >
      <img :src="modal.img_url" width="100%">
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
import DatePicker from "vue2-datepicker";
import Multiselect from "vue-multiselect";

export default {
  components: {
    DatePicker,
    loader: loader,
    paging: paging,
    Multiselect
  },
  name: "List-Parent",
  data() {
    return {
      list_branches:[],
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
        branch_id:"",
        keyword: "",
        pagination: this.pagination,
        dateRange: ""
      },
      users_manager:[],
      parents: [],
      pagination: {
        url: "/api/camera-ai/list-action",
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
        title: "CHI TIẾT",
        show: false,
        color: "success",
        body: "Cập nhật khách hàng thành công",
        img_url:"",
        closeOnBackdrop: false,
      },
    };
  },
  created() {
    u.g(`/api/branches?permission=1`)
      .then(response => {
      this.list_branches = response.data
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
      const ids_branch_id = []
      this.searchData.branch_id = u.is.obj(this.searchData.branch_id) ? [this.searchData.branch_id] : this.searchData.branch_id
      if (this.searchData.branch_id.length) {
        this.searchData.branch_id.map(item => {
          ids_branch_id.push(item.id)
        })
      }

      const data = {
        keyword: this.searchData.keyword,
        branch_id:ids_branch_id,
        pagination:this.pagination,
        end_date:endDate,
        start_date:startDate
      };
      const link = "/api/camera-ai/list-action";

      this.loading.processing = true;
      u.p(link, data)
        .then((response) => {
          this.loading.processing = false;
          this.parents = response.data.list;
          this.total = response.data.detail_total
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
    showModalImg(img_url){
      this.modal.img_url = img_url
      this.modal.show = true
    },
    exit() {
      this.modal.show = false;
    },
  },
  sockets: {
    camera_ai: function (data) { 
      if( localStorage.getItem("branch_id") == 0 || data.branch_id == localStorage.getItem("branch_id")){
        this.search();
      }
    },
  },
};
</script>

