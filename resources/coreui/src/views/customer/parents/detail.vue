<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <div class="row">
              <div class="col-sm-3 border-right">
                  <div style="float: left; width: 90px;">
                    <img class="c-avatar-img" src="img/avatars/1.jpg" >
                  </div>
                  <div style="float: left; padding: 10px;">
                    <h5 style="margin-bottom:0px">{{parent.name}} </h5>
                    <p><strong>{{parent.mobile_1}}</strong></p>
                    <p><router-link
                        class="btn btn-sm btn-success"
                        :to="`/parents/${parent.id}/edit`"
                      >
                        <i class="fa fa-edit"></i> </router-link></p>
                  </div>
              </div>
              <div class="col-sm-2 border-right text-center">
                <p>Liên hệ lần cuối</p>
                <p><strong>{{parent.last_care}}</strong></p>  
              </div> 
              <div class="col-sm-2 border-right text-center">
                <p>Tương tác</p>
                <strong>{{parent.num_care}}</strong>
              </div> 
              <div class="col-sm-2 border-right text-center">
                <p>Trạng thái</p>
                <p><select class="form-control">
                  <option>Chọn trạng thái</option>
                </select></p>  
              </div>
              <div class="col-sm-3 text-center">
                <p>Người phụ trách</p>
                <p><select class="form-control" @change="showModalAssgin" v-model="tmp_owner_id">
                  <option :value="item.id" v-for="(item, index) in users_manager" :key="index">{{item.name}} - {{item.hrm_id}}</option>
                </select></p>  
              </div>
                  
            </div>
          </div>
          <div class="card-body">
              <div class="row">
                <div class="col-sm-3 border-right" >
                  <p><i class="fa fa-info-circle"></i> <strong>Thông tin khách hàng</strong></p>
                  <p>Họ tên: <span class="fl-right">{{parent.name}}</span></p>
                  <p>SĐT: <span class="fl-right">{{parent.mobile_1}}</span></p>
                  <p>Email: <span class="fl-right">{{parent.email}}</span></p>
                  <p>Ngày sinh: <span class="fl-right">{{parent.birthday}}</span></p>
                  <p>Nghề nghiệp: <span class="fl-right">{{parent.job_name}}</span></p>
                  <p>Địa chỉ: <span class="fl-right">{{parent.address}}</span></p>
                  <p>Tỉnh/Thành phố: <span class="fl-right">{{parent.province_name}}</span></p>
                  <p>Quận huyện: <span class="fl-right">{{parent.district_name}}</span></p>
                  <p>Nguồn: <span class="fl-right">{{parent.source_name}}</span></p>
                  <p>Ngày tạo: <span class="fl-right">{{parent.created_at}}</span></p>
                  <p>Người tạo: <span class="fl-right">{{parent.creator_name}}</span></p>
                </div>
                <div class="col-sm-9">
                  <ul class="nav nav-tabs nav-justified">
                    <li class="nav-item">
                      <a class="nav-link" @click.prevent="setActive('customer_care')" :class="{ active: isActive('customer_care') }" href="#customer_care">Chăm sóc</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" @click.prevent="setActive('students')" :class="{ active: isActive('students') }" href="#students">Học sinh</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" @click.prevent="setActive('logs')" :class="{ active: isActive('logs') }" href="#logs">Lịch sử cập nhật</a>
                    </li>
                  </ul>
                  <div class="tab-content py-3" id="myTabContent">
                    <div class="tab-pane fade" :class="{ 'active show': isActive('customer_care') }" id="customer_care">
                      <div class="padding-bottom-10">
                        <button class="btn btn-success" @click="showModalCare"><i class="fa fa-plus"></i> Thêm mới</button>
                      </div>
                      <table class="table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>Thời gian</th>
                            <th>Người chăm sóc</th>
                            <th>Phương thức</th>
                            <th>Chi tiết</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(item, index) in cares" :key="index">
                            <td>{{ item.care_date }}</td>
                            <td>{{ item.creator_name }}</td>
                            <td>{{ item.method_name }}</td>
                            <td v-html="item.note"></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="tab-pane fade" :class="{ 'active show': isActive('students') }" id="students">
                      <div class="padding-bottom-10">
                        <button class="btn btn-success" @click="showModalStudent"><i class="fa fa-plus"></i> Thêm mới học sinh</button>
                      </div>
                      <div class="row">
                        <div class="col-sm-6" v-for="(item, index) in students" :key="index">
                          <div class="card card-accent-info" >
                            <div class="card-header"><strong>{{ item.name }}</strong></div>
                            <div class="card-body">
                              <p>Ngày sinh: {{ item.birthday }}</p>
                              <p>Giới tính: {{ item.gender | genTextGender}}</p>
                              <p>Trường: {{ item.school}}</p>
                              <p>Ghi chú: {{ item.note}}</p>
                              <p>Ngày tạo: {{ item.created_at}}</p>
                              <p>Người tạo: {{ item.creator_name}}</p>
                              <p>Trạng thái: <b>Mới tạo</b></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" :class="{ 'active show': isActive('logs') }" id="logs">
                      <table class="table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>Thời gian</th>
                            <th>Nội dung</th>
                            <th>Người thực hiện</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(item, index) in logs" :key="index">
                            <td>{{ item.created_at }}</td>
                            <td>{{ item.content }}</td>
                            <td>{{ item.creator_name }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
    <CModal
      :title="modal_care.title"
      :show.sync="modal_care.show"
      :color="modal_care.color"
      :closeOnBackdrop="modal_care.closeOnBackdrop"
      :size="modal_care.size"
    >
      <div>
        <div class="form-in-list">
          <form action method="post">
            <div class="row">
              <div class="form-group col-sm-6">
                <label for="nf-email" >Thời gian</label>
                <input class="form-control" type="datetime-local" id="published_date">
              </div>
              <div class="form-group col-sm-6">
                <label for="nf-email">Phương thức</label>
                <select class="form-control" v-model="care.method_id">
                  <option value="">Chọn phương thức</option>
                  <option
                    :value="method.id"
                    v-for="(method, index) in methods"
                    :key="index"
                  >{{method.name}}</option>
                </select>
              </div>
              <div class="form-group col-sm-12">
                <label for="nf-email">Ghi chú</label>
                <editor
                  :api-key="tinymce.key"
                  :init="tinymce.init"
                  id="input_tinymce"
                />
              </div>
            </div>
          </form>
          <p style="color:red" v-html="modal_care.error_message"></p>
        </div>
      </div>
      <template #header>
        <h5 class="modal-title">{{ modal_care.title }}</h5>
      </template>
      <template #footer>
        <CButton :color="'btn btn-success'" @click="addCare" type="button"
          >Lưu</CButton
        >
        <CButton :color="'btn btn-secondary'" @click="exit('care')" type="button"
          >Đóng</CButton
        >
      </template>
    </CModal>
    <CModal
      :title="modal_student.title"
      :show.sync="modal_student.show"
      :color="modal_student.color"
      :closeOnBackdrop="modal_student.closeOnBackdrop"
      :size="modal_student.size"
    >
      <div>
        <div class="form-in-list">
          <form action method="post">
            <div class="row">
              <div class="form-group col-sm-6">
                <label for="nf-email" >Họ tên học sinh</label>
                <input class="form-control" type="text" v-model="student.name">
              </div>
              <div class="form-group col-sm-6">
                <label for="nf-email">Ngày sinh</label>
                <datepicker
                  class="form-control calendar"
                  v-model="student.birthday"
                  placeholder="Chọn ngày sinh nhật"
                  lang="lang"
                  @change="selectDate"
                />
              </div>
              <div class="form-group col-sm-6">
                <label for="nf-email">Giới tính</label>
                <select class="form-control" v-model="student.gender">
                  <option value="M">Nam</option>
                  <option value="F">Nữ</option>
                </select>
              </div>
              <div class="form-group col-sm-6">
                <label for="nf-email">Cấp trường</label>
                <select class="form-control" id="" v-model="student.school_level" name="school_level"  @change="getSchools()" :disabled="parent.district_id == ''">
                  <option value="" disabled>Chọn cấp trường</option>
                  <option value="Mẫu giáo">Mẫu giáo</option>
                  <option value="Tiểu học">Tiểu học</option>
                </select>
              </div>
              <div class="form-group col-sm-6">
                <label for="nf-email">Trường học</label>
                <select class="form-control" id="" v-model="student.select_school" name="select_school"  data-vv-rules="required" @change="selectSchool" :disabled="parent.district_id == '' || student.school_level==''">
                    <option :value="school.name" v-for="(school, index) in html.schools.list" :key="index">{{ school.name }}</option>
                    <option value="Other">Khác...</option>
                </select>
                <input
                    v-show="(student.select_school == 'Other' || (Array.isArray(html.schools.list) && html.schools.list.length == 0)) && (parent.district_id != '' && student.school_level!='')"
                    class="form-control input-top"
                    type="text"
                    v-model="student.school"
                    placeholder="Nhập tên trường học"
                    maxlength="200"
                      :disabled="parent.district_id == ''"
                      @input="validShoolName"
                >
              </div>
              <div class="form-group col-sm-12">
                <label for="nf-email">Ghi chú</label>
                <textarea class="form-control" v-model="student.note"></textarea>
              </div>
            </div>
          </form>
          <p style="color:red" v-html="modal_student.error_message"></p>
        </div>
      </div>
      <template #header>
        <h5 class="modal-title">{{ modal_student.title }}</h5>
      </template>
      <template #footer>
        <CButton :color="'btn btn-success'" @click="addStudent" type="button"
          >Lưu</CButton
        >
        <CButton :color="'btn btn-secondary'" @click="exit('student')" type="button"
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
          <p>Bạn chắc chắn muốn thay đổi người phụ trách ?</p>
        </div>
      </div>
      <template #header>
        <h5 class="modal-title">{{ modal_assign.title }}</h5>
      </template>
      <template #footer>
        <CButton :color="'btn btn-success'" @click="assignCustomer" type="button"
          >Bàn giao</CButton
        >
        <CButton :color="'btn btn-secondary'" @click="exit('assign')" type="button"
          >Hủy</CButton
        >
      </template>
    </CModal>
  </div>
</template>

<script>
import axios from "axios";
import u from "../../../utilities/utility";
import loader from "../../../components/Loading";
import Editor from "@tinymce/tinymce-vue";
import datepicker from "vue2-datepicker";
import moment from 'moment';
import select from 'vue-select'

export default {
  components: {
    loader: loader,
    editor: Editor,
    datepicker,
    "vue-select": select
  },
  name: "Add-Product",
  data() {
    return {
      tinymce: {
        key: "68xdyo8hz3oyr5p47zv3jyvj3h6xg0hc0khthuj123tnskcx",
        init: {
          entity_encoding: "raw",
          height: 240,
          menubar: true,
          plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table paste code help wordcount",
          ],
          toolbar:
            "undo redo | bold italic backcolor | image| media |\
           alignleft aligncenter alignright alignjustify | \
           bullist numlist outdent indent | removeformat | help",
          images_upload_url:
            "/api/upload/upload_file?token=" +
            localStorage.getItem("api_token"),
          images_upload_base_path: "",
        },
      },
      loading: {
        text: "Đang tải dữ liệu...",
        processing: false,
      },
      html:{
        schools: {
          item: '',
          list: []
        },
      },
      modal_care: {
        title: "THÊM MỚI CHĂM SÓC",
        show: false,
        color: "info",
        closeOnBackdrop: false,
        size:"lg",
        error_message:""
        //'sm', 'lg', 'xl'
      },
      modal_student: {
        title: "THÊM MỚI HỌC SINH",
        show: false,
        color: "info",
        closeOnBackdrop: false,
        size:"lg",
        error_message:""
      },
      modal_assign: {
        title: "BÀN GIAO KHÁCH HÀNG",
        show: false,
        color: "info",
        closeOnBackdrop: false,
        size:"lg",
        error_message:""
      },
      parent: {
        id:"",
        gender: "",
        name: "",
        birthday: "",
        mobile_1: "",
        note: "",
        email: "",
        status: 1,
        province_id:"",
        district_id:"",
        job_id:"",
        source_id:"",
        source:"",
        job:"",
        province:"",
        district:"",
        address:""
      },
      activeItem: 'customer_care',
      methods:[],
      cares:[],
      care:{
        method_id:"",
        care_date:"",
        note:"",
        parent_id:"",
      },
      students:[],
      student:{
        parent_id:"",
        name:"",
        gender:"",
        school_level:"",
        birthday:"",
        select_school:"",
        note:"",
      },
      logs:[],
      users_manager:[],
      tmp_owner_id:"",
    };
  },
  created() {
    u.g(`/api/user/get-users-manager`)
      .then(response => {
      this.users_manager = response.data
    })
    u.g(`/api/methods`)
      .then(response => {
      this.methods = response.data
    })
    this.loadCares(this.$route.params.id);
    this.loadDetail();
  },
  methods: {
    loadDetail(){
      this.loading.processing = true;
      u.g(`/api/parents/show/${this.$route.params.id}`)
        .then(response => {
        this.loading.processing = false;
        this.parent = response.data
        this.tmp_owner_id = response.data.owner_id
      })
    },
    isActive (menuItem) {
      return this.activeItem === menuItem
    },
    setActive (menuItem) {
      if(menuItem=="students"){
        this.loadStudents(this.$route.params.id)
      }else if(menuItem=="logs"){
        this.loadLogs(this.$route.params.id)
      }
      this.activeItem = menuItem
    },
    exit(item) {
      if(item=='care'){
        this.modal_care.show = false;
      }else if(item=='student'){
        this.modal_student.show = false;
      }else if(item=='assign'){
        this.modal_assign.show = false;
        this.tmp_owner_id = this.parent.owner_id
      }
    },
    showModalCare(){
      document.getElementById('published_date').value=""
      this.modal_care.show = true
      this.modal_care.error_message=""
    },
    loadCares(parent_id){
      this.loading.processing = true;
        u.g(`/api/care/get_all_data/${parent_id}`)
        .then((response) => {
          this.loading.processing = false;
          this.cares=response.data;
        })
        .catch((e) => {
        });
    },
    addCare(){
      this.care.care_date = document.getElementById('published_date').value
      this.care.note = tinymce.get("input_tinymce").getContent();
      this.care.parent_id = this.parent.id
      let mess = "";
      let resp = true;
      if (this.care.care_date == "") {
        mess += " - Thời gian chăm sóc không được để trống<br/>";
        resp = false;
      }
      if (this.care.method_id == "") {
        mess += " - Phương thức chăm sóc không được để trống<br/>";
        resp = false;
      }
      if (this.care.note == "") {
        mess += " - Nội dung chăm sóc không được để trống<br/>";
        resp = false;
      }
      if(resp){
        this.loading.processing = true;
        u.p(`/api/care/add`,this.care)
        .then((response) => {
          this.loading.processing = false;
          this.loadCares(this.parent.id);
          this.exit("care");
        })
        .catch((e) => {
        });
      }else{
        this.modal_care.error_message = mess;
      }
    },
    showModalStudent(){
      this.modal_student.show = true
      this.modal_student.error_message=""
    },
    selectDate(date) {
      if (date) {
        this.student.birthday = moment(date).format("YYYY-MM-DD");
      }
    },
    getSchools(e){
      const school_level = this.student.school_level ? this.student.school_level : e.target.value ? e.target.value : 'Tiểu học'
      const district_id = parseInt(this.parent.district_id, 10)
      const province_id = parseInt(this.parent.province_id, 10)
      if (school_level && district_id > 0 && province_id > 0) {        
        u.g(`/api/get/${province_id}/${district_id}/${school_level}/schools`).then(response => {
          this.html.schools.list = response.data
          this.student.school = ""
        })        
      }
    },
    selectSchool () {
      if (this.student.select_school != 'Other') {
        this.student.school = this.student.select_school
      } else {
        this.student.school = ''
      }
    },
    validShoolName() {
      this.student.school = this.student.school.replace(/[~`!@#$%^&*()=+{}[,\]./<>?;'\\:"|]/gi, '');
    },
    addStudent(){
      this.student.parent_id = this.parent.id
      let mess = "";
      let resp = true;
      if (this.student.name == "") {
        mess += " - Tên học sinh không được để trống<br/>";
        resp = false;
      }
      if (this.student.birthday == "") {
        mess += " - Ngày sinh không được để trống<br/>";
        resp = false;
      }
      if (this.student.gender == "") {
        mess += " - Giới tính không được để trống<br/>";
        resp = false;
      }
      if (this.student.school_level == "") {
        mess += " - Cấp trường không được để trống<br/>";
        resp = false;
      }
      if (this.student.school == "") {
        mess += " - Trường học không được để trống<br/>";
        resp = false;
      }
      if(resp){
        this.loading.processing = true;
        u.p(`/api/students/add`,this.student)
        .then((response) => {
          this.loading.processing = false;
          this.loadStudents(this.parent.id);
          this.exit("student");
        })
        .catch((e) => {
        });
      }else{
        this.modal_student.error_message = mess;
      }
    },
    loadStudents(parent_id){
      this.loading.processing = true;
        u.g(`/api/students/get_all_data/${parent_id}`)
        .then((response) => {
          this.loading.processing = false;
          this.students=response.data;
        })
        .catch((e) => {
        });
    },
    loadLogs(parent_id){
      this.loading.processing = true;
        u.g(`/api/parents/get_logs/${parent_id}`)
        .then((response) => {
          this.loading.processing = false;
          this.logs=response.data;
        })
        .catch((e) => {
        });
    },
    showModalAssgin(){
      this.modal_assign.show =true
    },
    assignCustomer(){
      const data = {
        parent_id: this.parent.id,
        owner_id: this.tmp_owner_id,
      };
      this.loading.processing = true;
        u.p(`/api/parents/assign`,data)
        .then((response) => {
          this.modal_assign.show =false
          this.loading.processing = false;
          this.loadDetail();
        })
        .catch((e) => {
        });
    }
  },
  filters: {
    genTextGender(item){
      let resp = ''
      if(item== 'M'){
        resp = 'Nam'
      }else{
        resp = 'Nữ'
      }
      return resp
    }
  }
};
</script>
<style>
p{
  margin-bottom: 3px;
}
.fl-right{
  float: right;
}
.border-right{
  border-right: 1px solid #ccc;
}
.padding-bottom-10{
  padding-bottom: 10px; 
}
</style>