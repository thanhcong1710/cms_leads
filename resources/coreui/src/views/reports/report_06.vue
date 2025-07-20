<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong> Báo cáo tổng đài</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-4">
                <label for="name">Từ khóa</label>
                <input
                  class="form-control"
                  v-model="searchData.keyword"
                  type="text"
                  placeholder="Số điện thoại"
                />
              </div>
              <div class="form-group col-sm-4">
                <label for="name">Số máy lẻ</label>
                <input
                  class="form-control"
                  v-model="searchData.ext"
                  type="text"
                  placeholder="Số máy lẻ"
                />
              </div>
              <div class="form-group col-sm-4">
                <label for="ccmonth">Ngày gọi</label>
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
                <label for="name">Loại</label>
                <select class="form-control"  v-model="searchData.call_type">
                  <option value="">Chọn loại</option>
                  <option value="1">Cuộc gọi nội bộ</option>
                  <option value="2">Cuộc gọi đến</option>
                  <option value="3">Cuộc gọi đi</option>
                  <option value="4">Gọi đi có chuyển tiếp</option>
                </select>
              </div>
              <div class="form-group col-sm-4">
                <label for="name">Trạng thái cuộc gọi</label>
                <select class="form-control"  v-model="searchData.call_status">
                  <option value="">Chọn trạng thái cuộc gọi</option>
                  <option value="ANSWERED">ANSWERED</option>
                  <option value="NO ANSWER">NO ANSWER</option>
                  <option value="BUSY">BUSY</option>
                </select>
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
            <div style="overflow: auto;">
              <table class="table table-striped table-hover" style="width:1600px">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Loại</th>
                    <th>Số gọi</th>
                    <th>Số nhận</th>
                    <th>Thời gian</th>
                    <th>Thời lượng (s)</th>
                    <th>Ghi âm cuộc gọi</th> 
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in imports" :key="index">
                    <td>
                      {{ index + 1 + (pagination.cpage - 1) * pagination.limit }}
                    </td>
                    <td>{{ item.call_type | callType}}</td>
                    <td>{{ item.source}}</td>
                    <td>{{ item.dst}}</td>
                    <td>{{ item.calldate}}</td>
                    <td>{{ item.duration}}</td>
                    <td>
                      <audio v-if="item.recording_url" controls style="height: 40px; width: 256px; border: 1px solid #ccc;">
                        <source :src="item.recording_url" type="audio/x-wav">
                      </audio>
                    </td>
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
        ext:"",
        dateRange:"",
        call_status:"",
        call_type:"",
        pagination: this.pagination
      },
      imports: [],
      pagination: {
        url: "/api/reports/06",
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
    this.search(0);
  },
  methods: {
    reset() {
      location.reload();
    },
    search(a=1) {
      const startDate = this.searchData.dateRange!='' && this.searchData.dateRange!= undefined && this.searchData.dateRange[0] ?`${u.dateToString(this.searchData.dateRange[0])}`:''
      const endDate = this.searchData.dateRange!='' && this.searchData.dateRange!= undefined && this.searchData.dateRange[1] ?`${u.dateToString(this.searchData.dateRange[1])}`:''
      
      const data = {
        keyword: this.searchData.keyword,
        ext:this.searchData.ext,
        call_status: this.searchData.call_status,
        call_type: this.searchData.call_type,
        start_date:startDate,
        end_date:endDate,
        pagination:this.pagination,
      };
      const link = "/api/reports/06";

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
    
  }, 
  filters: {
    callType(item){
      let resp = ''
      if(item== 1){
        resp = 'Cuộc gọi nội bộ'
      }else if(item==2){
        resp = 'Cuộc gọi đến'
      }else if(item==3){
        resp = 'Cuộc gọi đi'
      }else if(item==4){
        resp = 'Gọi đi có chuyển tiếp'
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