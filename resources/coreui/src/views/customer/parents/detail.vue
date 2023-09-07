<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <div class="row">
              <div class="col-sm-5 border-right">
                  <div style="float: left; width: 90px;">
                    <img class="c-avatar-img" src="img/avatars/avatar.png" >
                  </div>
                  <div style="float: left; padding: 10px;">
                    <h5 style="margin-bottom:0px">{{parent.name}} 
                      <router-link  v-if="!disabled_action" style="padding: 2px 6px"
                        class="btn btn-sm btn-outline-primary"
                        :to="`/parents/${parent.id}/edit`"
                      >
                        <i class="fa fa-edit"></i></router-link></h5>
                    <p style="margin-top: 5px;"><strong> * {{parent.mobile_1}}</strong> 
                      <button :disabled="disabled_action" class="btn btn-sm btn-outline-primary" style="padding: 2px 6px" @click="callPhone(parent.mobile_1)"><i class="fa fa-phone"></i> </button>
                      <button :disabled="disabled_action" class="btn btn-sm btn-outline-primary" style="padding: 2px 6px" @click="showSendSms(parent.mobile_1)"><i class="fa fa-sms"></i> </button></p>
                    <p style="margin-top: 5px;" v-if="parent.mobile_2"> 
                      <strong> * {{parent.mobile_2}}</strong> 
                      <button :disabled="disabled_action" class="btn btn-sm btn-outline-primary" style="padding: 2px 6px" @click="callPhone(parent.mobile_2)"><i class="fa fa-phone"></i> </button>
                      <button :disabled="disabled_action" class="btn btn-sm btn-outline-primary" style="padding: 2px 6px" @click="showSendSms(parent.mobile_2)"><i class="fa fa-sms"></i> </button>
                    </p>
                  </div>
              </div>
              <div class="col-sm-2 border-right text-center">
                <p>Liên hệ lần cuối</p>
                <p>{{parent.last_care}}</p> 
                <hr> 
                <p>Tương tác: {{parent.num_care}}</p>
              </div> 
              <div class="col-sm-2 border-right text-center">
                <p>Trạng thái</p>
                <p> 
                  <select class="form-control" @change="showModalChangeStatus" v-model="tmp_status" :disabled="disabled_action" >
                    <option value="1">KH mới</option>
                    <option value="2">KH tiềm năng</option>
                    <option value="3">KH tiềm năng cần follow up</option>
                    <option value="4">KH bận gọi lại sau</option>
                    <option value="5">KH không nghe máy</option>
                    <option value="6">KH đồng ý đặt lịch checkin</option>
                    <option value="7">KH đã đến checkin</option>
                    <option value="8">KH đã mua gói phí</option>
                    <option value="9">KH không có nhu cầu</option>
                    <option value="10">KH không tiềm năng</option>
                    <option value="11">KH đến hạn tái tục</option>
                    <option value="12">Danh sách đen</option>
                  </select>
                </p>  
              </div>
              <div class="col-sm-3 text-center">
                <p>Người phụ trách</p>
                <p><select class="form-control" @change="showModalAssgin" v-model="tmp_owner_id" :disabled="disabled_action">
                  <option :value="item.id" v-for="(item, index) in users_manager" :key="index">{{item.name}} - {{item.hrm_id}}</option>
                </select></p>  
              </div>
                  
            </div>
          </div>
          <div class="card-body">
              <div class="row">
                <div class="col-sm-3 border-right" >
                  <p><i class="fa fa-calendar"></i> <strong> Lịch chăm sóc tiếp theo</strong></p>
                  <p><input class="form-control" type="datetime-local" :value="parent.next_care_date" id="next_care_date" @change="updateNextCareDate" :disabled="disabled_action"></p>
                  <br>
                  <p><i class="fa fa-info-circle"></i> <strong>Thông tin khách hàng</strong></p>
                  <p>Họ tên: <span class="fl-right">{{parent.name}}</span></p>
                  <p>SĐT 1: <span class="fl-right">{{parent.mobile_1}}</span></p>
                  <p>SĐT 2: <span class="fl-right">{{parent.mobile_2}}</span></p>
                  <p>Email: <span class="fl-right">{{parent.email}}</span></p>
                  <p>Ngày sinh: <span class="fl-right">{{parent.birthday}}</span></p>
                  <p>Nghề nghiệp: <span class="fl-right">{{parent.job_name}}</span></p>
                  <p>Địa chỉ: <span class="fl-right">{{parent.address}}</span></p>
                  <p>Tỉnh/Thành phố: <span class="fl-right">{{parent.province_name}}</span></p>
                  <p>Quận huyện: <span class="fl-right">{{parent.district_name}}</span></p>
                  <p>Nguồn: <span class="fl-right">{{parent.source_name}}</span></p>
                  <p>Nguồn chi tiết: <span class="fl-right">{{parent.source_detail_name}}</span></p>
                  <p>Ngày tạo: <span class="fl-right">{{parent.created_at}}</span></p>
                  <p>Người tạo: <span class="fl-right">{{parent.creator_name}}</span></p>
                  <br>
                  <p><i class="fa fa-info-circle"></i> <strong>Thông tin học sinh</strong></p>
                  <div v-for="(item, index) in students" :key="index">
                    <p>Họ tên: <span class="fl-right">{{ item.name}}</span></p>
                    <p>Ngày sinh: <span class="fl-right">{{ item.birthday}}</span></p>
                  </div>
                </div>
                <div class="col-sm-9">
                  <div class="alert alert-secondary" role="alert" v-if="sms.show">
                    <h5 class="alert-heading"> <i class="fa fa-sms" style="margin-right:10px"></i> {{sms.title}}</h5>
                    <hr>
                      <textarea class="form-control" v-model="sms.content" placeholder="Nhập nội dung tin nhắn"></textarea>
                      <div style="margin-top:5px;text-align:right">
                        <button class="btn btn-success" @click="sendSms"> <i class="fa fa-paper-plane"></i> Gửi</button>
                        <button class="btn btn-secondary" @click="sms.show=false"> <i class="fa fa-times"></i> Hủy</button>
                      </div>
                  </div>
                  <div :class="phone.css_class" role="alert" v-if="phone.show">
                    <h5 class="alert-heading"> <i class="fa fa-phone" style="margin-right:10px"></i> {{phone.title}}</h5>
                    <hr>
                    <div v-html="phone.description"></div>
                    <div>
                      <select class="form-control" @change="phone.note = phone.select_note" v-model="phone.select_note">
                        <option value="">Sử dụng mẫu trả lời</option>
                        <option :value="item.title" v-for="(item, index) in template_note" :key="index">{{item.title}}</option>
                      </select>
                      <br>
                      <textarea class="form-control" v-model="phone.note" placeholder="Thêm ghi chú cuộc gọi"></textarea>
                      <div style="margin-top:5px;text-align:right" v-if="phone.show_input_note">
                        <button class="btn btn-success" @click="updateNotePhone"> <i class="fa fa-save"></i> Lưu</button>
                        <!-- <button class="btn btn-secondary" @click="phone.show=false"> <i class="fa fa-times"></i> Đóng</button> -->
                      </div>
                    </div>
                  </div>
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
                        <button :disabled="disabled_action" class="btn btn-success" @click="showModalCare"><i class="fa fa-plus"></i> Thêm mới</button>
                      </div>
                      <div>
                      <table class="table table-striped table-hover">
                        <thead>
                          <tr>
                            <th width="10%">Thời gian</th>
                            <th width="10%">Phụ trách</th>
                            <th width="10%">Trung tâm</th>
                            <th width="10%">Phương thức</th>
                            <th width="10%">Trạng thái</th>
                            <th width="25%">Chi tiết</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(item, index) in cares" :key="index">
                            <td width="10%">{{ item.care_date }}</td>
                            <td width="10%">{{ item.creator_name }}</td>
                            <td width="10%">{{ item.branch_name }}</td>
                            <td width="10%">{{ item.method_name}}{{item.type_call?" - "+item.type_call:''}}</td>
                            <td width="10%">{{ genStateCall(item.data_state) }} <span v-if="item.status==0"> - Không kích hoạt</span></td>
                            <td width="25%">
                              <p v-if="item.link_record">
                                <audio controls style="height: 40px; width: 256px; border: 1px solid #ccc;">
                                  <source :src="item.link_record" type="audio/x-wav">
                                </audio>
                              </p>
                              <p v-html="item.note"></p>
                              <p v-if="item.attached_file">
                                <a :href="item.attached_file" target="blank">File đính kèm</a>
                              </p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      </div>
                    </div>
                    <div class="tab-pane fade" :class="{ 'active show': isActive('students') }" id="students">
                      <div class="padding-bottom-10">
                        <button :disabled="disabled_action" class="btn btn-success" @click="showModalStudent(0)"><i class="fa fa-plus"></i> Thêm mới học sinh</button>
                      </div>
                      <div class="row">
                        <div class="col-sm-6" v-for="(item, index) in students" :key="index">
                          <div class="card card-accent-info" >
                            <div class="card-header">
                              <strong>{{ item.name }}</strong>
                              <button :disabled="disabled_action" class="btn btn-sm btn-success" @click="showModalStudent(item)"> <i class="fa fa-edit"></i> </button>
                              <button :disabled="disabled_action" v-if="item.status==0" class="btn btn-sm btn-danger" @click="showModalCheckin(item)"> <i class="fa fa-location-arrow"></i></button>
                            </div>
                            <div class="card-body">
                              <p>Ngày sinh: {{ item.birthday }}</p>
                              <p>Giới tính: {{ item.gender | genTextGender}}</p>
                              <!-- <p>Trường: {{ item.school}}</p> -->
                              <p>Ghi chú: {{ item.note}}</p>
                              <p>Ngày tạo: {{ item.created_at}}</p>
                              <p>Người tạo: {{ item.creator_name}}</p>
                              <p>Trạng thái: <b>{{ item.status | genStudentStatus}}</b></p>
                              <p v-if="item.status>0">Trung tâm checkin: {{ item.checkin_branch_name}}</p>
                              <p v-if="item.status>0">Thời gian checkin: {{ item.checkin_at}}</p>
                              <p v-if="item.status>0">Cập nhật checkin: 
                                <button :disabled="disabled_action" v-if="item.status>0" class="btn btn-sm btn-success" @click="showModalUpdateCheckin(item)"> <i class="fa fa-edit"></i></button>
                              </p>
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
              <!-- <div class="form-group col-sm-6">
                <label for="nf-email" >Thời gian</label>
                <input class="form-control" type="datetime-local" id="published_date">
              </div> -->
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
              <div class="form-group col-sm-6">
                <label for="nf-email">File đính kèm</label>
                <input
                      type="file"
                      class="form-control"
                      id="fileUploadExcel"
                      @change="fileChanged"
                    >
              </div>
              <div class="form-group col-sm-12">
                <label for="nf-email">Ghi chú</label>
                <textarea class="form-control" v-model="care.note"></textarea>
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
              <!-- <div class="form-group col-sm-6">
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
              </div> -->
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
    <CModal
      :title="modal_status.title"
      :show.sync="modal_status.show"
      :color="modal_status.color"
      :closeOnBackdrop="modal_status.closeOnBackdrop"
      :size="modal_status.size"
    >
      <div>
        <div class="form-in-list">
          <p>Bạn chắc chắn muốn cập nhật trạng thái của khách hàng ?</p>
        </div>
      </div>
      <template #header>
        <h5 class="modal-title">{{ modal_status.title }}</h5>
      </template>
      <template #footer>
        <CButton :color="'btn btn-success'" @click="changeStatus" type="button"
          >Cập nhật</CButton
        >
        <CButton :color="'btn btn-secondary'" @click="exit('status')" type="button"
          >Hủy</CButton
        >
      </template>
    </CModal>
    <CModal
      :title="modal_checkin.title"
      :show.sync="modal_checkin.show"
      :color="modal_checkin.color"
      :closeOnBackdrop="modal_checkin.closeOnBackdrop"
      :size="modal_checkin.size"
    >
      <div>
        <div class="form-in-list">
          <div class="row">
            <div class="form-group col-sm-6">
              <label for="nf-email">Trung tâm checkin</label>
              <select class="form-control" v-model="modal_checkin.branch_id" :disabled="modal_checkin.disabled">
                <option value="">Chọn trung tâm</option>
                <option :value="item.id" v-for="(item, index) in branches" :key="index">{{item.name}}</option>
              </select>
            </div>
            <div class="form-group col-sm-6">
              <label for="nf-email">Ngày/Giờ Checkin</label>
              <datepicker
                        id="checkin-at"
                        class="form-control calendar"
                        :value="modal_checkin.checkin_at"
                        v-model="modal_checkin.checkin_at"
                        placeholder="Chọn ngày giờ"
                        lang="lang"
                        type="datetime"
                        format="YYYY-MM-DD HH:mm"
                >
                </datepicker>
            </div>
            <div class="form-group col-sm-6">
              <label for="nf-email">Sản phẩm</label>
              <select class="form-control" v-model="modal_checkin.type_product">
                <option value="">Chọn sản phẩm</option>
                <option value="1">CMS</option>
                <option value="2">Accelium</option>
              </select>
            </div>
          </div>
          <p style="color:red" v-html="modal_checkin.error_message"></p>
        </div>
      </div>
      <template #header>
        <h5 class="modal-title">{{ modal_checkin.title }}</h5>
      </template>
      <template #footer>
        <CButton :color="'btn btn-success'" @click="checkin" type="button"
          >Lưu</CButton
        >
        <CButton :color="'btn btn-secondary'" @click="exit('checkin')" type="button"
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
import datepicker from "vue2-datepicker";
import moment from 'moment';
import select from 'vue-select'

export default {
  components: {
    loader: loader,
    datepicker,
    "vue-select": select
  },
  name: "Add-Product",
  data() {
    return {
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
      branches:[],
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
      modal_status: {
        title: "CẬP NHÂT TRẠNG THÁI KHÁCH HÀNG",
        show: false,
        color: "info",
        closeOnBackdrop: false,
        size:"lg",
        error_message:""
      },
      modal_checkin: {
        title: "TẠO CHECKIN HỌC SINH",
        show: false,
        color: "info",
        closeOnBackdrop: false,
        size:"lg",
        error_message:"",
        branch_id:"",
        checkin_at:"",
        student_id:"",
        error_message:"",
        disabled:false,
        type_product:"",
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
        address:"",
        next_care_date:"",
      },
      activeItem: 'customer_care',
      methods:[],
      cares:[],
      care:{
        method_id:"",
        care_date:"",
        note:"",
        parent_id:"",
        attached_file:"",
        file_name:"",
      },
      students:[],
      student:{
        id:0,
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
      tmp_status:"",
      phone:{
        css_class: 'alert alert-success',
        show: false,
        title:'Đang thực hiện cuộc gọi đi',
        status:0,
        description:'',
        show_input_note:false,
        care_id:'',
        note:'',
        select_note:''
      },
      sms:{
        content:'',
        show:false,
        title:'Gửi tin nhắn SMS',
        phone:''
      },
      template_note:[],
      disabled_action:false
    };
  },
  created() {
    const arr_role = JSON.parse(localStorage.getItem("roles")).split(",");
    // if(arr_role.indexOf("Supervisor")> -1){
    //   this.disabled_action = true
    // }
    // this.$socket.emit('userConnected', localStorage.getItem("user_id"));
    u.g(`/api/user/get-users-manager`)
      .then(response => {
      this.users_manager = response.data
    })
    u.g(`/api/methods`)
      .then(response => {
      this.methods = response.data
    })
    u.g(`/api/template_note`)
      .then(response => {
      this.template_note = response.data
    })
    this.loadCares(this.$route.params.id);
    this.loadStudents(this.$route.params.id)
    this.loadDetail();
    u.g(`/api/branches`)
      .then(response => {
      this.branches = response.data
    })
  },
  methods: {
    loadDetail(){
      this.loading.processing = true;
      u.g(`/api/parents/show/${this.$route.params.id}`)
        .then(response => {
        this.loading.processing = false;
        if(response.data.length !== 0){
          this.parent = response.data
          this.tmp_owner_id = response.data.owner_id
          this.tmp_status = response.data.status
          if(this.parent.branch_id!=0){
            this.modal_checkin.branch_id = this.parent.branch_id
            this.modal_checkin.disabled =true
          }
        }else{
          this.$router.push({ path: `/parents` });
        }
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
      }else if(item=='status'){
        this.modal_status.show = false;
        this.tmp_status = this.parent.status
      }else if(item=='checkin'){
        this.modal_checkin.show = false;
      }
    },
    showModalCare(){
      // document.getElementById('published_date').value=""
      this.modal_care.show = true
      this.modal_care.error_message=""
      this.care.method_id=""
      this.care.note=""
      this.care.attached_file=""
      this.care.file_name=""
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
      // this.care.care_date = document.getElementById('published_date').value
      this.care.parent_id = this.parent.id
      let mess = "";
      let resp = true;
      // if (this.care.care_date == "") {
      //   mess += " - Thời gian chăm sóc không được để trống<br/>";
      //   resp = false;
      // }
      if (this.care.method_id == "") {
        mess += " - Phương thức chăm sóc không được để trống<br/>";
        resp = false;
      }
      if (this.care.note == "") {
        mess += " - Nội dung chăm sóc không được để trống<br/>";
        resp = false;
      }
      if (this.care.file_name == "") {
        mess += " - File đính kèm không được để trống<br/>";
        resp = false;
      }
      if(resp){
        this.loading.processing = true;
        this.exit("care");
        u.p(`/api/care/add`,this.care)
        .then((response) => {
          if(response.data.status ==0){
            alert(response.data.message)
          }
          this.loading.processing = false;
          this.loadCares(this.parent.id);
        })
        .catch((e) => {
        });
      }else{
        this.modal_care.error_message = mess;
      }
    },
    showModalStudent(data){
      if(data==0){
        this.modal_student.show = true
        this.modal_student.error_message=""
        this.modal_student.title ="THÊM MỚI HỌC SINH"
        this.student.id =0
        this.student.parent_id =""
        this.student.name =""
        this.student.gender =""
        this.student.school_level =""
        this.student.birthday =""
        this.student.select_school =""
        this.student.note =""
      }else{
        console.log(data)
        this.modal_student.show = true
        this.modal_student.error_message=""
        this.modal_student.title ="CẬP NHẬT THÔNG TIN HỌC SINH"
        this.student.id =data.id
        this.student.parent_id =data.parent_id
        this.student.name =data.name
        this.student.gender =data.gender
        this.student.school_level =data.school_level
        this.student.birthday =data.birthday
        this.student.select_school =data.school
        this.student.school = data.school
        this.student.note =data.note
        this.getSchools()
      }
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
          if(!this.student.id){
            this.student.school = ""
          }
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
      // if (this.student.school_level == "") {
      //   mess += " - Cấp trường không được để trống<br/>";
      //   resp = false;
      // }
      // if (this.student.school == "") {
      //   mess += " - Trường học không được để trống<br/>";
      //   resp = false;
      // }
      if(resp){
        this.loading.processing = true;
        this.exit("student");
        u.p(`/api/students/add`,this.student)
        .then((response) => {
          this.loading.processing = false;
          this.loadStudents(this.parent.id);
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
    showModalChangeStatus(){
      this.modal_status.show =true
    },
    assignCustomer(){
      const data = {
        parent_id: this.parent.id,
        owner_id: this.tmp_owner_id,
      };
      this.modal_assign.show =false
      this.loading.processing = true;
        u.p(`/api/parents/assign`,data)
        .then((response) => {
          this.loading.processing = false;
          this.loadDetail();
        })
        .catch((e) => {
        });
    },
    changeStatus(){
      const data = {
        parent_id: this.parent.id,
        status: this.tmp_status,
      };
      this.modal_status.show =false
      this.loading.processing = true;
        u.p(`/api/parents/change_status`,data)
        .then((response) => {
          this.loading.processing = false;
          this.loadDetail();
        })
        .catch((e) => {
        });
    },
    callPhone(phone){
      this.loading.processing = true;
      u.g(`/api/parents/make_to_call/${this.$route.params.id}?phone=${phone}`)
      .then((response) => {
        this.loading.processing = false;
        this.phone.show = true
        this.phone.status = 0
        this.phone.show_input_note = false
        this.phone.css_class= 'alert alert-success'
        this.phone.title = "Đang thực hiện cuộc gọi đi đến SĐT - "+phone+" ..."
        this.phone.care_id = ''
        this.phone.note=''
      })
      .catch((e) => {
      });
    },
    getInfoCall(data){
      if(data.parent_id == this.$route.params.id){
        this.loading.processing = true;
        u.g(`/api/care/get_info_call/${data.care_id}`)
        .then((response) => {
          this.loading.processing = false;
          this.phone.show = true
          this.phone.status = 1
          this.phone.care_id = data.care_id
          if(response.data.data_state == "ANSWERED"){
            this.phone.css_class= 'alert alert-success'
            this.phone.title = "Kết thúc cuộc gọi - "+this.genStateCall(response.data.data_state)
          }else{
            this.phone.css_class= 'alert alert-danger'
            this.phone.title = "Kết thúc cuộc gọi - "+this.genStateCall(response.data.data_state)
          }
          this.phone.show_input_note = true
          this.phone.description ='<p>Cuộc gọi: '+response.data.type+'</p>'+
            '<p>Số điện thoại: '+response.data.phone+'</p>'+
            '<p>Số máy nhánh: '+response.data.sip_id+'</p>'+
            '<p>Thời gian bắt đầu: '+response.data.start_time+'</p>'+
            '<p>Thời gian bắt đầu: '+response.data.end_time+'</p>'+
            '<p>Thời gian: '+response.data.duration+'s</p>'+
            '<p>Trạng thái: <strong>'+this.genStateCall(response.data.data_state)+'</strong></p>';
        })
        .catch((e) => {
        });
      }
    },
    updateNotePhone(){
      if(this.phone.note){
        const data = {
          care_id: this.phone.care_id,
          note: this.phone.note,
        };
        this.loading.processing = true;
          u.p(`/api/care/udpate_note`,data)
          .then((response) => {
            this.loading.processing = false;
            this.phone.show = false;
            this.loadCares(this.$route.params.id);
          })
          .catch((e) => {
          });
      }
    },
    showSendSms(phone){
      this.sms.show =true
      this.sms.phone =phone
      this.sms.content = ''
      this.sms.title = 'Gửi tin nhắn SMS tới SĐT - '+phone
    },
    sendSms(){
      if(this.sms.content){
        const data = {
          parent_id: this.$route.params.id,
          content: this.sms.content,
          phone: this.sms.phone,
        };
        this.loading.processing = true;
        u.p(`/api/parents/send_sms`,data)
        .then((response) => {
          this.loading.processing = false;
          this.sms.show = false;
          this.loadCares(this.$route.params.id);
        })
        .catch((e) => {
        });
      }
    },
    genStateCall(text){
      let resp = ''
      switch (text) {
        case 'ANSWERED':
          resp = 'Đã nghe máy'
          break
        case 'BUSY':
          resp = 'Máy bận'
          break
        case 'NO ANSWER':
          resp = 'Không nghe máy'
          break
        default:
          resp = text
          break
      }
      return resp
    },
    updateNextCareDate(){
        const data = {
          parent_id: this.$route.params.id,
          next_care_date: document.getElementById('next_care_date').value
        };
        this.loading.processing = true;
        u.p(`/api/parents/update_next_care_date`,data)
        .then((response) => {
          this.loadDetail();
        })
        .catch((e) => {
        });
    },
    showModalCheckin(item){
      this.modal_checkin.show =true
      this.modal_checkin.student_id = item.id
      this.modal_checkin.branch_id = this.parent.branch_id
      this.modal_checkin.checkin_at = ""
      this.modal_checkin.error_message=""
      this.modal_checkin.type_product=""
    },
    checkin(){
      let mess = "";
      let resp = true;
      if (this.modal_checkin.branch_id == "") {
        mess += " - Trung tâm checkin không được để trống<br/>";
        resp = false;
      }
      if (this.modal_checkin.checkin_at == "") {
        mess += " - Thời gian checkin không được để trống<br/>";
        resp = false;
      }
      if (this.modal_checkin.type_product == "") {
        mess += " - Sản phẩm không được để trống<br/>";
        resp = false;
      }
      if(resp){
        const data = {
          student_id: this.modal_checkin.student_id,
          branch_id: this.modal_checkin.branch_id,
          checkin_at: moment(this.modal_checkin.checkin_at).format('YYYY-MM-DD HH:mm'),
          type_product: this.modal_checkin.type_product
        };
        this.loading.processing = true;
        this.exit("checkin");
        u.p(`/api/students/checkin`,data)
        .then((response) => {
          this.loading.processing = false;
          this.loadStudents(this.parent.id);
        })
        .catch((e) => {
        });
      }else{
        this.modal_checkin.error_message = mess;
      }
    },
    showModalUpdateCheckin(item){
      this.modal_checkin.show =true
      this.modal_checkin.student_id = item.id
      this.modal_checkin.branch_id = item.checkin_branch_id
      this.modal_checkin.checkin_at = item.checkin_at
      this.modal_checkin.type_product = item.type_product
      this.modal_checkin.error_message=""
    },
    fileChanged(e) {
      const fileReader = new FileReader();
      const fileName = e.target.value.split("\\").pop();
      this.care.file_name = fileName
      fileReader.readAsDataURL(e.target.files[0]);
      fileReader.onload = e => {
        this.care.attached_file = e.target.result;
      };
    },
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
    },
    genStudentStatus(item){
      let resp = ''
      if(item== 0){
        resp = 'Mới tạo'
      }else if(item==1){
        resp = 'Thêm mới checkin'
      }else if(item==2){
        resp = 'Đã đến checkin'
      }
      return resp
    },
  },
  sockets: {
    connect: function () {
      console.log('socket to notification channel connected')
    },
    call_end: function (data) { 
      console.log(data);
      if(data.user_id == localStorage.getItem("user_id")){
        this.getInfoCall(data)
      }
    },
  },
};
</script>
<style scoped>
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
tbody {
    display:block;
    height:500px;
    overflow:auto;
}
thead, tbody tr {
    display:table;
    width:100%;
    table-layout:fixed;
}
thead {
    width: calc( 100% - 1em )
}
</style>