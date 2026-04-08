<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Báo cáo chi tiết chăm sóc khách hàng</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-4">
                <label for="name">Từ khóa</label>
                <input
                  class="form-control"
                  v-model="searchData.keyword"
                  type="text"
                  placeholder="Tên khách hàng, SĐT"
                />
              </div>
              <div class="form-group col-sm-4">
                <label>Trung tâm</label>
                <select class="form-control" v-model="searchData.branch_id" @change="loadUser()">
                  <option value="">Tất cả trung tâm</option>
                  <option :value="item.id" v-for="(item, index) in list_branches" :key="index">{{item.name}}</option>
                </select>
              </div>
              <div class="form-group col-sm-4">
                <label>Người phụ trách</label>
                <multiselect
                  placeholder="Chọn người phụ trách"
                  select-label="Chọn người phụ trách"
                  v-model="searchData.arr_owner"
                  :options="users_manager"
                  label="label_name"
                  :close-on-select="false"
                  :hide-selected="true"
                  :multiple="true"
                  :searchable="true"
                  track-by="id"
                >
                  <span slot="noResult">Không tìm thấy dữ liệu</span>
                </multiselect>
              </div>
              <div class="form-group col-sm-4">
                <label>Phương thức</label>
                <multiselect
                  placeholder="Chọn phương thức"
                  select-label="Chọn phương thức"
                  v-model="searchData.arr_method"
                  :options="list_methods"
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
              <div class="form-group col-sm-4">
                <label for="ccmonth">Thời gian tạo</label>
                  <date-picker
                    style="width:100%;"
                    v-model="searchData.dateRange"
                    :clearable="true"
                    :lang="datepickerOptions.lang"
                    range
                    format="YYYY-MM-DD"
                    id="apax-date-range"
                    placeholder="Chọn khoảng thời gian"
                  ></date-picker>
              </div>
              <div class="form-group col-sm-12">
                <button class="btn btn-success" @click="exportExcel()">
                  <i class="fas fa-file-excel"></i> Xuất báo cáo
                </button>
                <button class="btn btn-info" type="submit" @click="search(1)">
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
                  <th>#</th>
                  <th>Họ tên KH</th>
                  <th>Số điện thoại KH</th>
                  <th>Phương thức</th>
                  <th>Trung tâm</th>
                  <th>Người phụ trách</th>
                  <th>Thời gian tạo</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in imports" :key="index">
                  <td>{{ index + 1 + (pagination.cpage - 1) * pagination.limit }}</td>
                  <td>{{ item.parent_name }}</td>
                  <td>{{ item.parent_phone }}</td>
                  <td>{{ item.method_name }}</td>
                  <td>{{ item.branch_name }}</td>
                  <td>{{ item.creator_name }}</td>
                  <td>{{ item.created_at_formatted }}</td>
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
  </div>
</template>

<script>
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
  name: "Report-Care-Detail",
  data() {
    return {
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      searchData: {
        keyword: "",
        dateRange: [new Date(), new Date()],
        branch_id: "",
        arr_method: [],
        method_id: "",
        arr_owner: [],
        owner_id: "",
      },
      imports: [],
      list_branches: [],
      list_methods: [],
      users_manager: [],
      pagination: {
        url: "/api/reports/care_detail",
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
    };
  },
  created() {
    u.g(`/api/branches`)
      .then(response => {
      this.list_branches = response.data
    })
    
    u.g(`/api/methods`)
      .then(response => {
      this.list_methods = response.data
    })
    
    this.search(0);
  },
  methods: {
    loadUser(){
      this.searchData.arr_owner = [];
      if(this.searchData.branch_id){
        u.g(`/api/user/get-users-by-branch/${this.searchData.branch_id}`)
          .then(response => {
          this.users_manager = response.data
        })
      } else {
        u.g(`/api/user/get-users-manager`)
          .then(response => {
          this.users_manager = response.data
        })
      }
    },
    reset() {
      location.reload();
    },
    search(a=1) {
      const startDate = this.searchData.dateRange!='' && this.searchData.dateRange!=undefined && this.searchData.dateRange[0] ?`${u.dateToString(this.searchData.dateRange[0])}`:'';
      const endDate = this.searchData.dateRange!='' && this.searchData.dateRange!=undefined && this.searchData.dateRange[1] ?`${u.dateToString(this.searchData.dateRange[1])}`:'';
      
      const ids_method = [];
      this.searchData.arr_method = u.is.obj(this.searchData.arr_method) ? [this.searchData.arr_method] : this.searchData.arr_method;
      if (this.searchData.arr_method.length) {
        this.searchData.arr_method.map(item => {
          ids_method.push(item.id);
        });
      }
      this.searchData.method_id = ids_method;

      const ids_owner = [];
      this.searchData.arr_owner = u.is.obj(this.searchData.arr_owner) ? [this.searchData.arr_owner] : this.searchData.arr_owner;
      if (this.searchData.arr_owner.length) {
        this.searchData.arr_owner.map(item => {
          ids_owner.push(item.id);
        });
      }
      this.searchData.owner_id = ids_owner;

      const data = {
        keyword: this.searchData.keyword,
        start_date: startDate,
        end_date: endDate,
        branch_id: this.searchData.branch_id,
        method_id: this.searchData.method_id,
        owner_id: this.searchData.owner_id,
        pagination: this.pagination,
      };
      
      const link = "/api/reports/care_detail";

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
    exportExcel() {
      const startDate = this.searchData.dateRange!='' && this.searchData.dateRange!=undefined && this.searchData.dateRange[0] ?`${u.dateToString(this.searchData.dateRange[0])}`:'';
      const endDate = this.searchData.dateRange!='' && this.searchData.dateRange!=undefined && this.searchData.dateRange[1] ?`${u.dateToString(this.searchData.dateRange[1])}`:'';
      
      var ids_method = "";
      this.searchData.arr_method = u.is.obj(this.searchData.arr_method)
        ? [this.searchData.arr_method]
        : this.searchData.arr_method;
      if (this.searchData.arr_method.length) {
        this.searchData.arr_method.map((item) => {
          ids_method += ids_method ? "-" + item.id : item.id;
        });
      }

      var ids_owner = "";
      this.searchData.arr_owner = u.is.obj(this.searchData.arr_owner)
        ? [this.searchData.arr_owner]
        : this.searchData.arr_owner;
      if (this.searchData.arr_owner.length) {
        this.searchData.arr_owner.map((item) => {
          ids_owner += ids_owner ? "-" + item.id : item.id;
        });
      }

      var url = `/api/export/care_detail/`;
      var key = '';
      var value = '';
      
      if (this.searchData.keyword){
        key += "keyword,";
        value += this.searchData.keyword+",";
      }
      if (this.searchData.branch_id){
        key += "branch_id,";
        value += this.searchData.branch_id+",";
      }
      if (ids_owner){
        key += "owner_id,";
        value += ids_owner+",";
      }
      if (ids_method){
        key += "method_id,";
        value += ids_method+",";
      }
      if (startDate){
        key += "start_date,";
        value += startDate+",";
      }
      if (endDate){
        key += "end_date,";
        value += endDate+",";
      }

      key = key ? key.substring(0, key.length - 1) : '_';
      value = value ? value.substring(0, value.length - 1) : '_';
      
      url += key + "/" + value + `?token=${localStorage.getItem("api_token")}`;
      window.open(url, '_blank');
    },
  },
};
</script>
