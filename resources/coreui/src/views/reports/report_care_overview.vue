<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Báo cáo tổng quan chăm sóc khách hàng</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-4">
                <label>Trung tâm</label>
                <select class="form-control" v-model="searchData.branch_id">
                  <option value="">Tất cả</option>
                  <option :value="item.id" v-for="(item, index) in list_branches" :key="index">{{item.name}}</option>
                </select>
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
                  <th>Người phụ trách</th>
                  <th>Mã HRM</th>
                  <th>Tổng số nội dung CS</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in imports" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.branch_name }}</td>
                  <td>{{ item.creator_name }}</td>
                  <td>{{ item.hrm_id }}</td>
                  <td>{{ item.total_care }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import u from "../../utilities/utility";
import loader from "../../components/Loading";
import DatePicker from "vue2-datepicker";
import Multiselect from "vue-multiselect";

export default {
  components: {
    DatePicker,
    loader: loader,
    Multiselect
  },
  name: "Report-Care-Overview",
  data() {
    return {
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      searchData: {
        dateRange: [new Date(), new Date()],
        branch_id: "",
        arr_method: [],
        method_id: "",
      },
      imports: [],
      list_branches: [],
      list_methods: [],
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
    this.search();
  },
  methods: {
    reset() {
      location.reload();
    },
    search() {
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

      const data = {
        start_date: startDate,
        end_date: endDate,
        branch_id: this.searchData.branch_id,
        method_id: this.searchData.method_id,
      };
      const link = "/api/reports/care_overview";

      this.loading.processing = true;
      u.p(link, data)
        .then((response) => {
          this.loading.processing = false;
          this.imports = response.data;
        })
        .catch((e) => {
          u.processAuthen(e);
        });
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

      var url = `/api/export/care_overview/`;
      var key = '';
      var value = '';
      
      if (this.searchData.branch_id){
        key += "branch_id,";
        value += this.searchData.branch_id+",";
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
