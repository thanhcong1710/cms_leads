<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong> Báo cáo cuộc gọi chi tiết</strong>
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
                <label for="name">Trung tâm</label>
                <select class="form-control" v-model="searchData.branch_id" @change="loadUser()">
                  <option value="0">Chọn trung tâm</option>
                  <option :value="item.id" v-for="(item, index) in list_branches" :key="index">{{item.name}}</option>
                </select>
              </div>
              <div class="form-group col-sm-4">
                <label for="ccmonth">Người phụ trách</label>
                <multiselect
                  placeholder="Chọn người phụ trách"
                  select-label="Chọn một người phụ trách"
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
                <label for="ccmonth">Ngày cập nhật</label>
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
             
              <div class="form-group col-sm-4">
                <label for="ccmonth">Nguồn</label>
                 <multiselect
                  placeholder="Chọn nguồn"
                  select-label="Chọn nguồn"
                  v-model="searchData.arr_source"
                  :options="source_list"
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
                <label for="ccmonth">Nguồn chi tiết</label>
                 <multiselect
                  placeholder="Chọn nguồn chi tiết"
                  select-label="Chọn nguồn chi tiết "
                  v-model="searchData.arr_source_detail"
                  :options="source_detail_list"
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
                <label for="name">Trạng thái cuộc gọi</label>
                <select class="form-control"  v-model="searchData.call_status">
                  <option value="">Chọn trạng thái cuộc gọi</option>
                  <option value="1">Thuê bao - Tắt máy - Sai số</option>
                  <option value="2">Location</option>
                  <option value="3">Máy bận - Không nghe máy</option>
                  <option value="4">KH hẹn gọi lại sau</option>
                  <option value="5">KH Từ chối nói chuyện</option>
                  <option value="6">KH không phù hợp</option>
                  <option value="7">KH tiềm năng</option>
                  <option value="9">Blacklist</option>
                </select>
              </div>
              <div class="form-group col-sm-4">
                <label for="name">Trạng thái cuộc gọi chi tiết</label>
                <select class="form-control"  v-model="searchData.call_status_sub">
                  <option value="">Chọn trạng thái cuộc gọi chi tiết</option>
                  <option value="51" v-if="searchData.call_status==5">KH đã từng sử dụng dịch vụ</option>
                  <option value="52" v-if="searchData.call_status==5">KH không quan tâm</option>
                  <option value="53" v-if="searchData.call_status==5">KH thực sự không muốn nói chuyện</option>
                  <option value="61" v-if="searchData.call_status==6">Không có con</option>
                  <option value="62" v-if="searchData.call_status==6">Lý do khác</option>
                  <option value="71" v-if="searchData.call_status==7">KH đang cân nhắc</option>
                  <option value="72" v-if="searchData.call_status==7">KH hẹn thời gian khác</option>
                  <option value="73" v-if="searchData.call_status==7">KH ko muốn làm phiền</option>
                  <option value="74" v-if="searchData.call_status==7">Confirm 1</option>
                </select>
              </div>
              <div class="form-group col-sm-4">
                <label for="ccmonth">Ngày hẹn chăm sóc</label>
                  <date-picker
                    style="width:100%;"
                    v-model="searchData.dateRangeCare"
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
              <table class="table table-striped table-hover" style="width:1600px">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Tên khách hàng</th>
                    <th>SĐT</th>
                    <th>Trạng thái cuộc gọi</th>
                    <th>Trạng thái cuộc gọi chi tiết</th>
                    <th>Ngày hẹn chăm sóc</th>
                    <th>Trung tâm</th>
                    <th>Sale</th>
                    <th>Ngày cập nhật</th>
                    <th>Nguồn</th> 
                    <th>Nguồn chi tiết</th> 
                    <th>Ghi chú</th> 
                    <th>Thao tác</th> 
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in imports" :key="index">
                    <td>
                      {{ index + 1 + (pagination.cpage - 1) * pagination.limit }}
                    </td>
                    <td>{{ item.name }}</td> 
                    <td>{{ item.mobile_1 }}</td>
                    <td>{{ item.call_status | callStatus}}</td>
                    <td>{{ item.call_status_sub | callStatusSub}}</td>
                    <td>{{ item.next_care_date}}</td>
                    <td>{{ item.branch_name }}</td>
                    <td>{{ item.sale_name}}</td>
                    <td>{{ item.created_at}}</td>
                    <td>{{ item.source_name}}</td>
                    <td>{{ item.source_detail_name}}</td>
                    <td>{{ item.note}}</td>
                    <td><router-link
                        class="btn btn-sm  btn-info"
                        :to="`/parents/${item.parent_id}/detail`"
                      >
                        <i class="fa fa-phone"></i></router-link> </td>
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
      source_detail_list:[],
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      searchData: {
        keyword: "",
        branch_id:"",
        arr_owner:"",
        dateRange:"",
        arr_source:"",
        arr_source_detail:"",
        call_status:"",
        call_status_sub:"",
        dateRangeCare:"",
        pagination: this.pagination
      },
      imports: [],
      pagination: {
        url: "/api/reports/04",
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
      const startDateCare = this.searchData.dateRangeCare!='' && this.searchData.dateRangeCare!= undefined && this.searchData.dateRangeCare[0] ?`${u.dateToString(this.searchData.dateRangeCare[0])}`:''
      const endDateCare = this.searchData.dateRangeCare!='' && this.searchData.dateRangeCare!= undefined && this.searchData.dateRangeCare[1] ?`${u.dateToString(this.searchData.dateRangeCare[1])}`:''
      const ids_owner = []
      this.searchData.arr_owner = u.is.obj(this.searchData.arr_owner) ? [this.searchData.arr_owner] : this.searchData.arr_owner
      if (this.searchData.arr_owner.length) {
        this.searchData.arr_owner.map(item => {
          ids_owner.push(item.id)
        })
      }
      this.searchData.owner_id = ids_owner

      const ids_source = []
      this.searchData.arr_source = u.is.obj(this.searchData.arr_source) ? [this.searchData.arr_source] : this.searchData.arr_source
      if (this.searchData.arr_source.length) {
        this.searchData.arr_source.map(item => {
          ids_source.push(item.id)
        })
      }
      this.searchData.source_id = ids_source

       const ids_source_detail = []
      this.searchData.arr_source_detail = u.is.obj(this.searchData.arr_source_detail) ? [this.searchData.arr_source_detail] : this.searchData.arr_source_detail
      if (this.searchData.arr_source_detail.length) {
        this.searchData.arr_source_detail.map(item => {
          ids_source_detail.push(item.id)
        })
      }
      this.searchData.source_detail_id = ids_source_detail

      const data = {
        keyword: this.searchData.keyword,
        branch_id:this.searchData.branch_id,
        owner_id: this.searchData.owner_id,
        source_id: this.searchData.source_id,
        source_detail_id: this.searchData.source_detail_id,
        call_status: this.searchData.call_status,
        call_status_sub: this.searchData.call_status_sub,
        start_date:startDate,
        end_date:endDate,
        start_date_care:startDateCare,
        end_date_care:endDateCare,
        pagination:this.pagination,
      };
      const link = "/api/reports/04";

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
      var ids_source_detail = "";
      this.searchData.arr_source_detail = u.is.obj(this.searchData.arr_source_detail)
        ? [this.searchData.arr_source_detail]
        : this.searchData.arr_source_detail;
      if (this.searchData.arr_source_detail.length) {
        this.searchData.arr_source_detail.map((item) => {
          ids_source_detail += ids_source_detail ? "-" + item.id : item.id;
        });
      }
      var url = `/api/export/report04/`;
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
       if (ids_source_detail) {
        this.key += "ids_source_detail,";
        this.value += ids_source_detail + ",";
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
        resp = 'Thuê bao - Tắt máy - Sai số'
      }else if(item==2){
        resp = 'Location'
      }else if(item==3){
        resp = 'Máy bận - Không nghe máy'
      }else if(item==4){
        resp = 'KH hẹn gọi lại sau'
      }else if(item==5){
        resp = 'KH Từ chối nói chuyện'
      }else if(item==6){
        resp = 'KH không phù hợp'
      }else if(item==7){
        resp = 'KH tiềm năng'
      }else if(item==9){
        resp = 'Blacklist'
      }else{
        resp = ''
      }

      return resp
    },
    callStatusSub(item){
      let resp = ''
      if(item== 51){
        resp = 'KH đã từng sử dụng dịch vụ'
      }else if(item==52){
        resp = 'KH thực sự không muốn nói chuyện'
      }else if(item==61){
        resp = 'Không có con'
      }else if(item==62){
        resp = 'Lý do khác'
      }else if(item==71){
        resp = 'KH đang cân nhắc'
      }else if(item==72){
        resp = 'KH hẹn thời gian khác'
      }else if(item==73){
        resp = 'KH ko muốn làm phiền'
      }else if(item==94){
        resp = 'Confirm 1'
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