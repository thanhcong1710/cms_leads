<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Báo cáo tổng quan khách hàng quá hạn xử lý theo người phụ trách</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-3">
                <label>Trung tâm</label>
                <select class="form-control" v-model="searchData.branch_id">
                  <option value="">Tất cả</option>
                  <option :value="item.id" v-for="(item, index) in branches" :key="index">{{item.name}}</option>
                </select>
              </div>
              <div class="form-group col-sm-3">
                <label for="ccmonth">Lịch hẹn chăm sóc</label>
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
                  <th>Tổng quá hạn</th>
                  <th>Chưa xử lý</th>
                  <th>Đã xử lý</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in imports" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.branch_name }}</td>
                  <td>{{ item.owner_name }}</td>
                  <td>{{ item.hrm_id }}</td>
                  <td>{{ item.total_overdue }}</td>
                  <td><span class="text-danger">{{ item.total_unresolved }}</span></td>
                  <td><span class="text-success">{{ item.total_resolved }}</span></td>
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
export default {
  components: {
    DatePicker,
    loader: loader,
  },
  name: "Report-Overdue-Overview",
  data() {
    return {
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      searchData: {
        dateRange: "",
        branch_id: "",
      },
      imports: [],
      branches: [],
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
      this.branches = response.data
    })
    this.search();
  },
  methods: {
    reset() {
      location.reload();
    },
    search() {
      const startDate = this.searchData.dateRange!='' && this.searchData.dateRange[0] ?`${u.dateToString(this.searchData.dateRange[0])}`:'';
      const endDate = this.searchData.dateRange!='' && this.searchData.dateRange[1] ?`${u.dateToString(this.searchData.dateRange[1])}`:'';
      const data = {
        start_date: startDate,
        end_date: endDate,
        branch_id: this.searchData.branch_id,
      };
      const link = "/api/reports/overdue_overview";

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
      const startDate = this.searchData.dateRange!='' && this.searchData.dateRange[0] ?`${u.dateToString(this.searchData.dateRange[0])}`:'';
      const endDate = this.searchData.dateRange!='' && this.searchData.dateRange[1] ?`${u.dateToString(this.searchData.dateRange[1])}`:'';
      
      var url = `/api/export/overdue_overview/`;
      this.key ='';
      this.value = ''
      if (this.searchData.branch_id){
        this.key += "branch_id,"
        this.value += this.searchData.branch_id+","
      }
      if (startDate){
        this.key += "start_date,"
        this.value += startDate+","
      }
      if (endDate){
        this.key += "end_date,"
        this.value += endDate+","
      }
      this.key = this.key? this.key.substring(0, this.key.length - 1):'_'
      this.value = this.value? this.value.substring(0, this.value.length - 1) : "_"
      url += this.key+"/"+this.value +`?token=${localStorage.getItem("api_token")}`
      window.open(url, '_blank');
    },
  },
};
</script>
