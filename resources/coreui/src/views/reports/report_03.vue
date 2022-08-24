<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong> Báo cáo cuộc gọi</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-4">
                <label for="name">Ngày giờ bắt đầu tìm kiếm</label>
                <input class="form-control" type="datetime-local" value="" id="from_date">
              </div>
              <div class="form-group col-sm-4">
                <label for="name">Ngày giờ kết thúc tìm kiếm</label>
                <input class="form-control" type="datetime-local" value="" id="to_date">
              </div>
              <div class="form-group col-sm-12 search_date_type">
                <input type="radio" id="type1" name="type_date" v-model="searchData.type_date" value="1" @change="search()">
                <label for="type1">Hôm nay</label>
                <input type="radio" id="type2" name="type_date" v-model="searchData.type_date" value="2"  @change="search()">
                <label for="type2">Hôm qua</label>
                <input type="radio" id="type3" name="type_date" v-model="searchData.type_date" value="3"  @change="search()">
                <label for="type3">Tuần này</label>
                <input type="radio" id="type4" name="type_date" v-model="searchData.type_date" value="4"  @change="search()">
                <label for="type4">Tuần trước</label>
                <input type="radio" id="type5" name="type_date" v-model="searchData.type_date" value="5"  @change="search()">
                <label for="type5">Tháng này</label>
                <input type="radio" id="type6" name="type_date" v-model="searchData.type_date"  value="6"  @change="search()">
                <label for="type6">Tháng trước</label>
              </div>  
              <div class="form-group col-sm-4">
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
              <div class="form-group col-sm-4">
                <label for="name">Từ khóa</label>
                <input
                  class="form-control"
                  v-model="searchData.keyword"
                  type="text"
                  placeholder="Tên mã nhân viên"
                />
              </div>
              <!-- <div class="form-group col-sm-3">
                <label for="ccmonth">Ngày tạo</label>
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
              </div> -->
              <div class="form-group col-sm-4">
                <label for="name">Trạng thái cuộc gọi</label>
                <multiselect
                  placeholder="Chọn trạng thái"
                  select-label="Chọn trạng thái"
                  v-model="searchData.type_status"
                  :options="list_type_status"
                  label="label"
                  :close-on-select="false"
                  :hide-selected="true"
                  :multiple="true"
                  :searchable="true"
                  track-by="id"
                >
                  <span slot="noResult">Không tìm thấy dữ liệu</span>
                </multiselect>
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
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Trung tâm</th>
                  <th>Tên máy nhánh</th>
                  <th>Tổng gọi vào</th>
                  <th>Tổng gọi ra</th>
                  <th>Tổng số cuộc gọi thành công</th>
                  <th>Tổng số cuộc gọi thất bại</th>
                  <!-- <th>Thời gian gọi vào TB</th>
                  <th>Thời gian gọi ra TB</th>
                  <th>Tổng thời gian gọi vào</th>
                  <th>Tổng thời gian gọi ra</th> -->
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in imports" :key="index">
                  <td>
                    {{ index + 1 + (pagination.cpage - 1) * pagination.limit }}
                  </td>
                  <td>{{ item.branch_name }}</td> 
                  <td>{{ item.sip_name }}</td>
                  <td>{{ item.total_inbound }}</td>
                  <td>{{ item.total_outbound }}</td>
                  <td>{{ item.total_call_success }}</td>
                  <td>{{ item.total_call_fail }}</td>
                  <!-- <td>{{ item.duration_inbound}}</td>
                  <td>{{ item.duration_outbound }}</td>
                  <td>{{ item.total_duration_inbound}}</td>
                  <td>{{ item.total_duration_outbound}}</td> -->
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
  name: "List-Parent",
  data() {
    return {
      list_type_status:[
        {id:1,label:'Nghe máy'},
        {id:2,label:'Không nghe máy'},
        {id:3,label:'Máy bận'},
      ],
      list_branches:[],
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      searchData: {
        // from_date:"",
        // to_date:"",
        type_status: "",
        branch_id:"",
        type_date:1,
        keyword: "",
        pagination: this.pagination
      },
      imports: [],
      pagination: {
        url: "/api/reports/03",
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
    this.search(0);
  },
  methods: {
    reset() {
      location.reload();
    },
    search(a=1) {
      const ids_branch_id = []
      this.searchData.branch_id = u.is.obj(this.searchData.branch_id) ? [this.searchData.branch_id] : this.searchData.branch_id
      if (this.searchData.branch_id.length) {
        this.searchData.branch_id.map(item => {
          ids_branch_id.push(item.id)
        })
      }
      const ids_type_status = []
      this.searchData.type_status = u.is.obj(this.searchData.type_status) ? [this.searchData.type_status] : this.searchData.type_status
      if (this.searchData.type_status.length) {
        this.searchData.type_status.map(item => {
          ids_type_status.push(item.id)
        })
      }
      const data = {
        from_date: a ? document.getElementById('from_date').value : "" ,
        to_date:a ? document.getElementById('to_date').value : "",
        branch_id:ids_branch_id,
        type_date:this.searchData.type_date,
        type_status:ids_type_status,
        keyword: this.searchData.keyword,
        pagination:this.pagination,
      };
      const link = "/api/reports/03";

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
      var ids_branch_id = ''
      this.searchData.branch_id = u.is.obj(this.searchData.branch_id) ? [this.searchData.branch_id] : this.searchData.branch_id
      if (this.searchData.branch_id.length) {
        this.searchData.branch_id.map(item => {
          ids_branch_id += ids_branch_id ? '-'+item.id : item.id
        })
      }
      var ids_type_status = ''
      this.searchData.type_status = u.is.obj(this.searchData.type_status) ? [this.searchData.type_status] : this.searchData.type_status
      if (this.searchData.type_status.length) {
        this.searchData.type_status.map(item => {
         ids_type_status += ids_type_status ? '-'+item.id : item.id
        })
      }
      var url = `/api/export/report03/`;
      this.key ='';
      this.value = ''
      if (this.searchData.keyword){
        this.key += "keyword,"
        this.value += this.searchData.keyword+","
      }
      if (ids_type_status){
        this.key += "type_status,"
        this.value += ids_type_status+","
      }
      if (this.searchData.type_date){
        this.key += "type_date,"
        this.value += this.searchData.type_date+","
      }
      if (ids_branch_id){
        this.key += "branch_id,"
        this.value += ids_branch_id+","
      }
      if(document.getElementById('from_date').value){
        this.key += "from_date,"
        this.value += document.getElementById('from_date').value+","
      }
      if(document.getElementById('to_date').value){
        this.key += "to_date,"
        this.value += document.getElementById('to_date').value+","
      }
      this.key = this.key? this.key.substring(0, this.key.length - 1):'_'
      this.value = this.value? this.value.substring(0, this.value.length - 1) : "_"
      url += this.key+"/"+this.value +`?token=${localStorage.getItem("api_token")}`
      window.open(url, '_blank');
    },
  },
};
</script>
<style scoped>
.search_date_type label{
  margin-right: 20px;
}
</style>