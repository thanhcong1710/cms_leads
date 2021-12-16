<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong> Báo cáo thông tin khách hàng</strong>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="form-group col-sm-3">
                <label for="name">Từ khóa</label>
                <input
                  class="form-control"
                  v-model="searchData.keyword"
                  type="text"
                  placeholder="Tên khách hàng, số điện thoại"
                />
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
                  <th>Mã KH</th>
                  <th>Tên KH</th>
                  <th>Tên HS</th>
                  <th>Năm sinh</th>
                  <th>Nguồn</th>
                  <th>Trạng thái</th>
                  <th>TVV</th>
                  <th>Trung tâm</th>
                  <th>Ngày nhập data</th>
                  <th>Ngày chăm sóc gần nhất</th>
                  <th>Hình thức chăm sóc gần nhất</th>
                  <th>Tổng số lần tương tác</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in imports" :key="index">
                  <td>
                    {{ index + 1 + (pagination.cpage - 1) * pagination.limit }}
                  </td>
                  <td>{{item.id}}</td>
                  <td>{{ item.parent_name }}</td>
                  <td>{{ item.student_name }}</td>
                  <td>{{ item.student_year }}</td>
                  <td>{{ item.source_name }}</td>
                  <td>{{ item.status | getStatusName}}</td>
                  <td>{{ item.owner_name}}</td>
                  <td>{{ item.branch_name}}</td>
                  <td>{{ item.created_date}}</td>
                  <td>{{ item.last_care_date}}</td>
                  <td>{{ item.last_method}}</td>
                  <td>{{ item.total_care}}</td>
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
export default {
  components: {
    loader: loader,
    paging: paging,
  },
  name: "List-Parent",
  data() {
    return {
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      searchData: {
        keyword: "",
        pagination: this.pagination
      },
      imports: [],
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
        keyword: this.searchData.keyword,
      };
      const link = "/api/reports/01";

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
      var url = `/api/export/report01/`;
      this.key ='';
      this.value = ''
      if (this.searchData.keyword){
        this.key += "keyword,"
        this.value += this.searchData.keyword+","
      }
      this.key = this.key? this.key.substring(0, this.key.length - 1):'_'
      this.value = this.value? this.value.substring(0, this.value.length - 1) : "_"
      url += this.key+"/"+this.value +`?token=${localStorage.getItem("api_token")}`
      window.open(url, '_blank');
    },
  },
  filters: {
    getStatusName(value) {
      let resp = ''
      switch (Number(value)) {
          case 1:
              resp = 'KH mới'
              break
          case 2:
              resp = 'KH tiềm năng'
              break
          case 3:
              resp = 'KH tiềm năng cần follow up'
              break
          case 4:
              resp = 'KH bận gọi lại sau'
              break
          case 5:
              resp = 'KH không nghe máy'
              break
          case 6:
              resp = 'KH đồng ý đặt lịch checkin'
              break
          case 7:
              resp = 'KH đã đến checkin'
              break
          case 8:
              resp = 'KH đã mua gói phí'
              break
          case 9:
              resp = 'KH không có nhu cầu'
              break
          case 10:
              resp = 'KH không tiềm năng'
              break
          case 11:
              resp = 'KH đến hạn tái tục'
              break
          case 12:
              resp = 'Danh sách đen'
              break 
          default:
              resp = 'KH mới'
              break
      }
      return resp
    },
  },
};
</script>
