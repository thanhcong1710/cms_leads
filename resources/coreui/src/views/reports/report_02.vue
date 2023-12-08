<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong> Báo cáo DATA theo thời gian</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-6">
                <label for="ccmonth">Ngày tìm kiếm</label>
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
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th rowspan="2">#</th>
                  <th rowspan="2">Tên nhân viên</th>
                  <th colspan="5">Target tuần</th>
                  <th colspan="6">Thực đạt tuần</th>
                </tr>
                <tr>	  
                  <th>Call</th>
                  <th>Talktime</th>
                  <th>Trial accept</th>
                  <th>Trial actual</th>
                  <th>New Enroll</th>
                  <th>Call</th>
                  <th>Talktime</th>
                  <th>Trial accept</th>
                  <th>Trial actual</th>
                  <th>New Enroll</th>
                  <th>Collection</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in imports" :key="index">
                  <td>
                    {{ index + 1 + (pagination.cpage - 1) * pagination.limit }}
                  </td>
                  <td>{{item.user_label}}</td>
                  <td>{{ item.target_call }}</td>
                  <td>{{ item.target_talk_time }}</td>
                  <td>{{ item.target_trial_accept }}</td>
                  <td>{{ item.target_trial_actual }}</td>
                  <td>{{ item.target_new_enroll}}</td>
                  <td>{{ item.call}}</td>
                  <td>{{ item.talk_time}}</td>
                  <td>{{ item.trial_accept}}</td>
                  <td>{{ item.trial_actual}}</td>
                  <td>{{ item.new_enroll}}</td>
                  <td>{{ item.collection}}</td>
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
import paging from "../../components/Pagination";
import u from "../../utilities/utility";
import loader from "../../components/Loading";
import select from 'vue-select'
export default {
  components: {
    "vue-select": select,
    loader: loader,
    paging: paging,
  },
  name: "List-Parent",
  data() {
    return {
      list_week:[],
      week:'',
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      searchData: {
        dateRange: [new Date(), new Date()],
        pagination: this.pagination
      },
      imports: [],
      label_report:'',
      pagination: {
        url: "/api/imports/list",
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
    this.search();
  },
  methods: {
    reset() {
      location.reload();
    },
    search(a) {
      const data = {
        report_week_id: this.searchData.week_id,
        keyword: this.searchData.keyword,
        pagination:this.pagination,
      };
      const link = "/api/reports/02";

      this.loading.processing = true;
      u.p(link, data)
        .then((response) => {
          this.loading.processing = false;
          this.imports = response.data.list;
          
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
      
      var url = `/api/export/report02/`;
      this.key ='';
      this.value = ''
      if (this.searchData.keyword){
        this.key += "keyword,"
        this.value += this.searchData.keyword+","
      }
       if (this.searchData.week_id){
        this.key += "report_week_id,"
        this.value += this.searchData.week_id+","
      }
      this.key = this.key? this.key.substring(0, this.key.length - 1):'_'
      this.value = this.value? this.value.substring(0, this.value.length - 1) : "_"
      url += this.key+"/"+this.value +`?token=${localStorage.getItem("api_token")}`
      window.open(url, '_blank');
    },
    selectWeek(data = null){
      if (data && typeof data === 'object') {
        const week_id = data.id
        this.searchData.week_id = week_id
      }else{
        this.searchData.week_id = ""
        this.week = ""
      }
    },
  },
  filters: {
  },
};
</script>
