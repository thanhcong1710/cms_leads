<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Danh sách khách hàng</strong>
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
              <div class="form-group col-sm-3">
                <label for="ccmonth">Trạng thái</label>
                <multiselect
                  placeholder="Chọn trạng thái"
                  select-label="Chọn trạng thái"
                  v-model="searchData.arr_status"
                  :options="list_status"
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
              <div class="form-group col-sm-3">
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
              <div class="form-group col-sm-3">
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
               <div class="form-group col-sm-3">
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
              <div class="form-group col-sm-3">
                <label for="ccmonth">Lịch chăm sóc tiếp theo</label>
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
              <div class="form-group col-sm-3">
                <label for="ccmonth">Năm sinh của học sinh</label>
                <input type="number"  v-model="searchData.studentYear" class="form-control" placeholder="YYYY" min="1999" max="2030">
              </div>
              <div class="form-group col-sm-12">
                <router-link v-if="!disabled_action" class="btn btn-success" :to="'/parents/add'">
                  <i class="fa fa-plus"></i> Thêm mới
                </router-link>
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
              <div class="form-group col-sm-12" v-if="temp.length>0 && !disabled_action">
                <p>Bạn đã lựa chọn <b>{{temp.length}}</b> khách hàng   <button  style="margin-left:30px;" class="btn btn-outline-primary" type="button" @click="showModalAssgin">Bàn giao</button></p>
              </div>  
            </div>
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link" @click.prevent="setActive('all')" :class="{ active: isActive('all') }" href="#all">Tất cả <span class="badge badge-sm bg-danger ms-auto">{{total.total_0}}</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" @click.prevent="setActive('type_1')" :class="{ active: isActive('type_1') }" href="#type_1">Chưa chăm sóc <span class="badge badge-sm bg-danger ms-auto">{{total.total_1}}</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" @click.prevent="setActive('type_2')" :class="{ active: isActive('type_2') }" href="#type_2">Lịch chăm sóc trong ngày <span class="badge badge-sm bg-danger ms-auto">{{total.total_2}}</span></a>
              </li>
               <li class="nav-item">
                <a class="nav-link" @click.prevent="setActive('type_3')" :class="{ active: isActive('type_3') }" href="#type_3">KH quá hạn xử lý <span class="badge badge-sm bg-danger ms-auto">{{total.total_3}}</span></a>
              </li>
            </ul>
            <div class="wrapper1">
                <div class="div1" style="width:1800px;height: 1px;">
                </div>
            </div>
            <div class="wrapper2">
              <table class="table table-striped table-hover" style="width:1800px">
                <thead>
                  <tr>
                    <th class="sticky-col st1-col"><b-form-checkbox class="check-item" id="select-all" v-model="selectAll"></b-form-checkbox></th>
                    <th class="sticky-col st2-col">STT</th>
                    <th class="sticky-col st3-col">Tên khách hàng</th>
                    <th class="sticky-col st4-col">Số điện thoại</th>
                    <th>Học sinh 2</th>
                    <th>Học sinh 1</th>
                    <th>Nguồn</th>
                    <th>Nguồn chi tiết</th>
                    <th>Người phụ trách</th>
                    <th>Lịch chăm sóc tiếp theo</th>
                    <th>Lịch sử chăm sóc</th>
                    <th>Thời gian chăm sóc gần nhất</th>
                    <th>Trạng thái</th>
                    <th class="sticky-col last-col">Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in parents" :key="index">
                    <td  class="sticky-col st1-col">
                      <b-form-checkbox
                        class="check-item"
                        v-model="temp"
                        :value="item.id"
                        @change.native="toggleSelectRow()"
                        number
                      ></b-form-checkbox>
                    </td>
                    <td  class="sticky-col st2-col">
                      {{ index + 1 + (pagination.cpage - 1) * pagination.limit }}
                    </td>
                    <td class="sticky-col st3-col"><router-link :to="`/parents/${item.id}/detail`"><a>{{ item.name }}</a></router-link></td>
                    <td class="sticky-col st4-col"><router-link :to="`/parents/${item.id}/detail`"><a>{{ item.mobile_1 }}</a></router-link></td>
                    <td>{{ item.hs2_name }}</td>
                    <td>{{ item.hs1_name }}</td>
                    <td>{{ item.source_name }}</td>
                    <td>{{ item.source_detail_name }}</td>
                    <td>{{ item.owner_name }}</td>
                    <td>{{ item.next_care_date}}</td>
                    <td>{{ item.last_care }}</td>
                    <td>{{ item.last_time_care }}</td>
                    <td>{{ item.status | getStatusName }}</td>
                    <td class="sticky-col last-col">
                      <router-link  v-if="!disabled_action"
                        class="btn btn-sm btn-success"
                        :to="`/parents/${item.id}/edit`"
                      >
                        <i class="fa fa-edit"></i> </router-link>
                      <button  v-if="!disabled_action"
                        class="btn btn-sm btn-danger"
                        type="button"
                        @click="deleteItem(item.id)"
                      >
                        <i class="fas fa-times"></i></button>
                      <router-link
                        class="btn btn-sm  btn-info"
                        :to="`/parents/${item.id}/detail`"
                      >
                        <i class="fa fa-eye"></i></router-link>  
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
                <div class="last page" style="width: 60px;float: left;height: 30px;">
                  <select v-model="pagination.limit" style="width: 100%;height: 100%;" @change="search()">
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                    <option value="300">300</option>
                  </select>
                </div>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
    <CModal
      :title="modal.title"
      :show.sync="modal.show"
      :color="modal.color"
      :closeOnBackdrop="modal.closeOnBackdrop"
    >
      {{ modal.body }}
      <template #header>
        <h5 class="modal-title">{{ modal.title }}</h5>
      </template>
      <template #footer>
        <CButton :color="'btn btn-' + modal.color" @click="exit" type="button"
          >Đóng</CButton
        >
      </template>
    </CModal>
    <CModal
      :title="modal_assign.title"
      :show.sync="modal_assign.show"
      :color="modal_assign.color"
      :closeOnBackdrop="modal_assign.closeOnBackdrop"
      :size="modal_assign.size"
    >
      <div>
        <div class="form-in-list">
          <p>Chọn người phụ trách</p>
          <multiselect
            placeholder="Chọn người phụ trách"
            select-label="Chọn một người phụ trách"
            v-model="owners"
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
      </div>
      <template #header>
        <h5 class="modal-title">{{ modal_assign.title }}</h5>
      </template>
      <template #footer>
        <CButton :color="'btn btn-success'" @click="assignCustomer" type="button"
          >Bàn giao</CButton
        >
        <CButton :color="'btn btn-secondary'" @click="modal_assign.show=false" type="button"
          >Hủy</CButton
        >
      </template>
    </CModal>
  </div>
</template>

<script>
import axios from "axios";
import paging from "../../../components/Pagination";
import u from "../../../utilities/utility";
import loader from "../../../components/Loading";
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
      source_list:[],
      source_detail_list:[],
      list_status:[
        {id:1,label:'KH mới'},
        {id:2,label:'KH không liên lạc được'},
        {id:3,label:'KH ở vùng CMS không có cơ sở'},
        {id:4,label:'KH hẹn gọi lại sau'},
        {id:5,label:'KH không quan tâm'},
        {id:6,label:'KH quan tâm, cần follow up date'},
        {id:7,label:'KH đồng ý đặt lịch Checkin'},
        {id:8,label:'KH đã đến checkin'},
        {id:9,label:'KH đã mua gói phí'},
        {id:10,label:'KH đến hạn tái tục'},
        {id:11,label:'Danh sách đen'},
      ],
      checked_list: [],
      temp: [],
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
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      searchData: {
        keyword: "",
        arr_status: "",
        arr_owner: "",
        arr_source: "",
        arr_source_detail: "",
        status: "",
        owner_id: "",
        source_id: "",
        source_detail_id: "",
        pagination: this.pagination,
        dateRange: "",
        type_seach: 1,
        studentYear:"",
      },
      users_manager:[],
      parents: [],
      pagination: {
        url: "/api/parents/list",
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
      modal: {
        title: "THÔNG BÁO",
        show: false,
        color: "success",
        body: "Cập nhật khách hàng thành công",
        closeOnBackdrop: false,
      },
      modal_assign: {
        title: "BÀN GIAO KHÁCH HÀNG",
        show: false,
        color: "info",
        closeOnBackdrop: true,
        error_message:""
      },
      owner_id:"",
      owners:[],
      activeItem: 'all',
      total:{
        total_0:0,
        total_1:0,
        total_2:0,
      },
      disabled_action:false
    };
  },
  mounted() {
    let externalScript = document.createElement('script')
    externalScript.setAttribute('src', 'https://code.jquery.com/jquery-1.12.4.js')
    document.head.appendChild(externalScript)
    let externalScript1 = document.createElement('script')
    externalScript1.setAttribute('src', './js/scroll.js')
    document.head.appendChild(externalScript1)
  },
  computed: {
    selectAll: {
      get: function() {
        return (
          parseInt(this.checked_list.length) === parseInt(this.parents.length)
        );
      },
      set: function(value) {
        const selected_list = [];
        if (value) {
          this.parents.forEach(parent => {
            selected_list.push(parent.id);
          });
        }
        this.checked_list = selected_list;
        this.temp = selected_list;
      }
    }
  },
  created() {
    // const arr_role = JSON.parse(localStorage.getItem("roles")).split(",");
    // if(!(arr_role.indexOf("admin")> -1)){
    //   this.disabled_action = true
    // }
    u.g(`/api/user/get-users-manager`)
      .then(response => {
      this.users_manager = response.data
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
    if(localStorage.getItem("parents_searchData")){
      this.searchData =  JSON.parse(localStorage.getItem("parents_searchData"));
    }
    if(this.searchData.type_seach ==0){
      this.activeItem = "all"
    }else if(this.searchData.type_seach == 1){
      this.activeItem = "type_1"
    }else if(this.searchData.type_seach == 2){
      this.activeItem = "type_2"
    }else if(this.searchData.type_seach == 3){
      this.activeItem = "type_3"
    }
    this.search();
  },
  methods: {
    showModalAssgin(){
      this.owner_id =""
      this.modal_assign.show =true
    },
    reset() {
      localStorage.setItem("parents_searchData", '');
      location.reload();
    },
    search(a) {
      const startDate = this.searchData.dateRange!='' && this.searchData.dateRange[0] ?`${u.dateToString(this.searchData.dateRange[0])}`:''
      const endDate = this.searchData.dateRange!='' && this.searchData.dateRange[1] ?`${u.dateToString(this.searchData.dateRange[1])}`:''
      const ids = []
      this.searchData.arr_status = u.is.obj(this.searchData.arr_status) ? [this.searchData.arr_status] : this.searchData.arr_status
      if (this.searchData.arr_status.length) {
        this.searchData.arr_status.map(item => {
          ids.push(item.id)
        })
      }
      this.searchData.status = ids

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
        status: this.searchData.status,
        owner_id: this.searchData.owner_id,
        source_id: this.searchData.source_id,
        source_detail_id: this.searchData.source_detail_id,
        start_date:startDate,
        end_date:endDate,
        pagination:this.pagination,
        type_seach:this.searchData.type_seach,
        student_year: this.searchData.studentYear
      };
      localStorage.setItem("parents_searchData", JSON.stringify(this.searchData));
      const link = "/api/parents/list";

      this.loading.processing = true;
      u.p(link, data)
        .then((response) => {
          this.loading.processing = false;
          this.parents = response.data.list;
          this.total = response.data.detail_total
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
    deleteItem(id) {
      u.g(`/api/parents/delete/${id}`)
        .then((response) => {
          this.loading.processing = false;
          if (response.status == 200) {
            this.modal.color = "success";
            this.modal.body = "Xóa khách hàng thành công";
            this.modal.show = true;
            this.search();
          }
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
    exit() {
      this.modal.show = false;
    },
    toggleSelectRow(){
      console.log(this.temp)
    },
    assignCustomer(){
      if(this.owners.length){
        const ids = []
        this.owners = u.is.obj(this.owners) ? [this.owners] : this.owners
        if (this.owners.length) {
          this.owners.map(item => {
            ids.push(item.id)
          })
        }
        const data = {
          parents: this.temp,
          owners: ids,
        };
        this.modal_assign.show =false
        this.loading.processing = true;
          u.p(`/api/parents/assign_list`,data)
          .then((response) => {
            this.loading.processing = false;
            this.modal.color = "success";
            this.modal.body = "Bàn giao khách hàng thành công";
            this.modal.show = true;
            this.search();
            this.temp=[]
          })
          .catch((e) => {
          });
      }
    },
    isActive (menuItem) {
      return this.activeItem === menuItem
    },
    setActive (menuItem) {
      if(menuItem=="all"){
        this.searchData.type_seach=0
      }else if(menuItem=="type_1"){
        this.searchData.type_seach=1
      }if(menuItem=="type_2"){
        this.searchData.type_seach=2
      }if(menuItem=="type_3"){
        this.searchData.type_seach=3
      }
      this.activeItem = menuItem
      this.search();
    },
  },
  filters: {
    getStatusName(value) {
      let resp = ''
      switch (Number(value)) {
          case 1:
              resp = 'KH mới';
              break;
          case 2:
              resp = 'KH không liên lạc được';
              break;
          case 3:
              resp = 'KH ở vùng CMS không có cơ sở';
              break;
          case 4:
              resp = 'KH hẹn gọi lại sau';
              break;
          case 5:
              resp = 'KH không quan tâm';
              break;
          case 6:
              resp = 'KH quan tâm, cần follow up date';
              break;
          case 7:
              resp = 'KH đồng ý đặt lịch Checkin';
              break;
          case 8:
              resp = 'KH đã đến checkin';
              break;
          case 9:
              resp = 'KH đã mua gói phí';
              break;
          case 10:
              resp = 'KH đến hạn tái tục';
              break;
          case 11:
              resp = 'Danh sách đen';
              break;
          default:
              resp = 'KH mới'
              break
      }
      return resp
    },
  },
};
</script>
<style scoped>
.wrapper2 {
  position: relative;
  overflow: auto;
}
.wrapper1 {
  overflow-x: scroll;
}
.sticky-col {
  position: -webkit-sticky;
  position: sticky;
  background-color: white !important;
}
.sticky-col {
  position: -webkit-sticky;
  position: sticky;
  background-color: white !important;
}
.table-striped tbody tr:nth-of-type(odd) .sticky-col{
  background-color: rgb(241 241 241) !important;
}

.st1-col {
  width: 60px;
  min-width: 60px;
  max-width: 60px;
  left: 0px;
}

.st2-col {
  width: 60px;
  min-width: 60px;
  max-width: 60px;
  left: 60px;
}

.st3-col {
  width: 160px;
  min-width: 160px;
  max-width: 160px;
  left: 120px;
}

.st4-col {
  width: 120px;
  min-width: 120px;
  max-width: 120px;
  left: 280px;
}
/* .last-col {
  width: 120px;
  min-width: 120px;
  max-width: 120px;
  right: 0px;
} */
</style>
