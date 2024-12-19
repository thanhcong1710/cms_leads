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
            <div class="row">
              <div class="form-group col-sm-4" style="padding: 0px 30px">
                <table class="table table-hover">
                  <tbody>
                    <tr style="background-color: rgba(0, 0, 21, 0.05)">
                      <td colspan="2">
                        Thông tin chung
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">Tổng số data đã chia chưa xử lý</td>
                      <td class="text-right"> {{data_report.total_new}}</td>
                    </tr>
                    <tr>
                      <td  class="text-left">Tổng số data kết nối được</td>
                      <td class="text-right">{{data_report.total_connect}}</td>
                    </tr>
                    <tr>
                      <td  class="text-left">Tổng số data không kết nối được</td>
                      <td class="text-right">{{data_report.total_not_connect}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="form-group col-sm-8" style="padding: 0px 30px">
                <table class="table table-hover">
                  <tbody>
                    <tr  style="background-color: rgba(0, 0, 21, 0.05)">
                      <td colspan="2">
                        Thông tin chi tiết
                      </td>
                    </tr>
                    <tr>
                      <td class="text-left">1. Blank</td>
                      <td class="text-right">{{data_report.detail_1}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">2. Thuê bao - Tắt máy - Sai số</td>
                      <td class="text-right">{{data_report.detail_2}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">3. Location</td>
                      <td class="text-right">{{data_report.detail_3}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">4. Máy bận - Không nghe máy</td>
                      <td class="text-right">{{data_report.detail_4}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">5. KH hẹn gọi lại sau</td>
                      <td class="text-right">{{data_report.detail_5}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">6. KH không có nhu cầu</td>
                      <td class="text-right">{{data_report.detail_6}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">7. Không có con/Không có con trong độ tuổi CMS</td>
                      <td class="text-right">{{data_report.detail_7}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">8. Lý do khác</td>
                      <td class="text-right">{{data_report.detail_8}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">9. KH quan tâm cần follow update</td>
                      <td class="text-right">{{data_report.detail_9}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">10.KH đồng ý đặt lịch check in</td>
                      <td class="text-right">{{data_report.detail_10}}</td>
                    </tr>
                    <tr>
                      <td class="text-left">11. Danh sách đen</td>
                      <td class="text-right">{{data_report.detail_11}}</td>
                    </tr>
                  </tbody>
                </table>
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
    Multiselect,
  },
  name: "List-Parent",
  data() {
    return {
      list_type_status: [
        { id: 1, label: "Nghe máy" },
        { id: 2, label: "Không nghe máy" },
        { id: 3, label: "Máy bận" },
      ],
      list_branches: [],
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
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
            "Tháng 12",
          ],
        },
      },
      source_list:[],
      source_detail_list:[],
      users_manager:[],
      searchData: {
        dateRange: [new Date(), new Date()],
        arr_owner: "",
        arr_source: "",
        arr_source_detail: "",
        from_date:"",
        to_date:"",
        type_status: "",
        branch_id: "",
        type_date: 1,
        keyword: "",
      },
      data_report: [],
    };
  },
  created() {
    u.g(`/api/branches`).then((response) => {
      this.list_branches = response.data;
    });
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
    search(a = 1) {
      const startDate = this.searchData.dateRange!='' && this.searchData.dateRange!= undefined && this.searchData.dateRange[0] ?`${u.dateToString(this.searchData.dateRange[0])}`:''
      const endDate = this.searchData.dateRange!='' && this.searchData.dateRange!= undefined && this.searchData.dateRange[1] ?`${u.dateToString(this.searchData.dateRange[1])}`:''
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
        branch_id: this.searchData.branch_id,
        owner_id: this.searchData.owner_id,
        source_id: this.searchData.source_id,
        source_detail_id: this.searchData.source_detail_id,
        start_date:startDate,
        end_date:endDate,
      };
      const link = "/api/reports/03";

      this.loading.processing = true;
      u.p(link, data)
        .then((response) => {
          this.loading.processing = false;
          this.data_report = response.data;
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
    exportExcel() {
      const startDate = this.searchData.dateRange!='' && this.searchData.dateRange!= undefined && this.searchData.dateRange[0] ?`${u.dateToString(this.searchData.dateRange[0])}`:''
      const endDate = this.searchData.dateRange!='' && this.searchData.dateRange!= undefined && this.searchData.dateRange[1] ?`${u.dateToString(this.searchData.dateRange[1])}`:''
      
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
      var url = `/api/export/report03/`;
      this.key = "";
      this.value = "";
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
      if (this.searchData.type_date) {
        this.key += "type_date,";
        this.value += this.searchData.type_date + ",";
      }
      if (startDate) {
        this.key += "start_date,";
        this.value += startDate + ",";
      }
      if (endDate) {
        this.key += "end_date,";
        this.value += endDate + ",";
      }
      this.key = this.key ? this.key.substring(0, this.key.length - 1) : "_";
      this.value = this.value
        ? this.value.substring(0, this.value.length - 1)
        : "_";
      url +=
        this.key +
        "/" +
        this.value +
        `?token=${localStorage.getItem("api_token")}`;
      window.open(url, "_blank");
    },
  },
};
</script>
<style scoped>
.search_date_type label {
  margin-right: 20px;
}
table tr td{
  border: 1px #d8dbe0 solid;
}
</style>