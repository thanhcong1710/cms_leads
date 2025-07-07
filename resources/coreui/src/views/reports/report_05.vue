<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong> Báo cáo bàn giao khách hàng</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-4">
                <label for="name">Từ khóa</label>
                <input
                  class="form-control"
                  v-model="searchData.keyword"
                  type="text"
                  placeholder="SĐT khách hàng"
                />
              </div>
              <div class="form-group col-sm-4">
                <label for="ccmonth">Ngày bàn giao</label>
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
                <button class="btn btn-success" @click="exportExcel()">
                  <i class="fas fa-file-excel"></i> Xuất báo cáo
                </button>
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
            <div style="overflow: auto;">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Người bàn giao</th>
                    <th>Người được bàn giao</th>
                    <th>Số lượng bàn giao</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in imports" :key="index">
                    <td>
                      {{ index + 1 + (pagination.cpage - 1) * pagination.limit }}
                    </td>
                    <td>{{ item.pre_owner_name }}</td> 
                    <td>{{ item.owner_name }}</td>
                    <td>{{ item.call_status | callStatus}}</td>
                    <td>{{ item.total}}</td>
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
            "Tháng 12",
          ],
        },
      },
      list_branches:[],
      users_manager:[],
      source_list:[],
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      searchData: {
        keyword: "",
        dateRange:[new Date(new Date().getFullYear(), new Date().getMonth(), 1), new Date()],
        pagination: this.pagination
      },
      imports: [],
      pagination: {
        url: "/api/reports/05",
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
    u.g(`/api/branches`)
      .then(response => {
      this.list_branches = response.data
    })
    u.g(`/api/sources`)
      .then(response => {
      this.source_list = response.data
      this.source_detail_list = response.data
    })
     u.g(`/api/source_detail`)
      .then(response => {
      this.source_detail_list = response.data
    })
    this.search(0);
  },
  methods: {
    loadUser(){
      this.arr_owner =[]
      if(this.searchData.branch_id){
        u.g(`/api/user/get-users-by-branch/${this.searchData.branch_id}`)
          .then(response => {
          this.users_manager = response.data
        })
      }else {
        this.users_manager = []
      }
      
    },
    reset() {
      location.reload();
    },
    search(a=1) {
      const startDate = this.searchData.dateRange!='' && this.searchData.dateRange!= undefined && this.searchData.dateRange[0] ?`${u.dateToString(this.searchData.dateRange[0])}`:''
      const endDate = this.searchData.dateRange!='' && this.searchData.dateRange!= undefined && this.searchData.dateRange[1] ?`${u.dateToString(this.searchData.dateRange[1])}`:''
      const data = {
        keyword: this.searchData.keyword,
        start_date:startDate,
        end_date:endDate,
        pagination:this.pagination,
      };
      const link = "/api/reports/05";

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
      const startDate = this.searchData.dateRange!='' && this.searchData.dateRange!= undefined && this.searchData.dateRange[0] ?`${u.dateToString(this.searchData.dateRange[0])}`:''
      const endDate = this.searchData.dateRange!='' && this.searchData.dateRange!= undefined && this.searchData.dateRange[1] ?`${u.dateToString(this.searchData.dateRange[1])}`:''
      const startDateCare = this.searchData.dateRangeCare!='' && this.searchData.dateRangeCare!= undefined && this.searchData.dateRangeCare[0] ?`${u.dateToString(this.searchData.dateRangeCare[0])}`:''
      const endDateCare = this.searchData.dateRangeCare!='' && this.searchData.dateRangeCare!= undefined && this.searchData.dateRangeCare[1] ?`${u.dateToString(this.searchData.dateRangeCare[1])}`:''
      var ids_owner = "";
      this.searchData.owner_id = u.is.obj(this.searchData.owner_id)
        ? [this.searchData.owner_id]
        : this.searchData.owner_id;
      if (this.searchData.owner_id.length) {
        this.searchData.owner_id.map((item) => {
          ids_owner += ids_owner ? "-" + item.id : item.id;
        });
      }
      var ids_source = "";
      this.searchData.arr_source = u.is.obj(this.searchData.arr_source)
        ? [this.searchData.arr_source]
        : this.searchData.arr_source;
      if (this.searchData.arr_source.length) {
        this.searchData.arr_source.map((item) => {
          ids_source += ids_source ? "-" + item.id : item.id;
        });
      }
      
      var url = `/api/export/report05/`;
      this.key ='';
      this.value = ''
      if (this.searchData.keyword) {
        this.key += "keyword,";
        this.value += this.searchData.keyword + ",";
      }
      if (this.searchData.branch_id) {
        this.key += "branch_id,";
        this.value += this.searchData.branch_id + ",";
      }
      if (ids_owner) {
        this.key += "ids_owner,";
        this.value += ids_owner + ",";
      }
       if (ids_source) {
        this.key += "ids_source,";
        this.value += ids_source + ",";
      }
      if (this.searchData.call_status) {
        this.key += "call_status,";
        this.value += this.searchData.call_status + ",";
      }
      if (this.searchData.call_status_sub) {
        this.key += "call_status_sub,";
        this.value += this.searchData.call_status_sub + ",";
      }
      if (startDate) {
        this.key += "start_date,";
        this.value += startDate + ",";
      }
      if (endDate) {
        this.key += "end_date,";
        this.value += endDate + ",";
      }
      if (startDateCare) {
        this.key += "start_date_care,";
        this.value += startDateCare + ",";
      }
      if (endDateCare) {
        this.key += "end_date_care,";
        this.value += endDateCare + ",";
      }
      this.key = this.key? this.key.substring(0, this.key.length - 1):'_'
      this.value = this.value? this.value.substring(0, this.value.length - 1) : "_"
      url += this.key+"/"+this.value +`?token=${localStorage.getItem("api_token")}`
      window.open(url, '_blank');
    },
  },
  filters: {
    callStatus(item){
      let resp = ''
      if(item== 1){
        resp = 'Blank'
      }else if(item==2){
        resp = 'Thuê bao - Tắt máy - Sai số'
      }else if(item==3){
        resp = 'Location'
      }else if(item==4){
        resp = 'Máy bận - Không nghe máy'
      }else if(item==5){
        resp = 'KH hẹn gọi lại sau'
      }else if(item==6){
        resp = 'KH không có nhu cầu'
      }else if(item==7){
        resp = 'Không có con/Không có con trong độ tuổi CMS'
      }else if(item==8){
        resp = 'Lý do khác'
      }else if(item==9){
        resp = 'KH quan tâm cần follow update'
      }else if(item==10){
        resp = 'KH đồng ý đặt lịch checkin'
      }else if(item==11){
        resp = 'Danh sách đen'
      }else{
        resp = ''
      }

      return resp
    },
  },
};
</script>
<style scoped>
.search_date_type label{
  margin-right: 20px;
}
</style>