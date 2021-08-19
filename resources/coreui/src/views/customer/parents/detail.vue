<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <div class="row">
              <div class="col-sm-4 border-right">
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
                <p><strong>2021-08-18 10:20:00</strong></p>  
              </div> 
              <div class="col-sm-2 border-right text-center">
                <p>Tương tác</p>
                <strong>4</strong>
              </div> 
              <div class="col-sm-2 border-right text-center">
                <p>Trạng thái</p>
                <p><select class="form-control">
                  <option>Chọn trạng thái</option>
                </select></p>  
              </div>
              <div class="col-sm-2 text-center">
                <p>Người phụ trách</p>
                <p><select class="form-control">
                  <option>Chọn trạng thái</option>
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
                  <p>Ngày tạo: <span class="fl-right">{{parent.source_name}}</span></p>
                  <p>Người tạo: <span class="fl-right">{{parent.source_name}}</span></p>
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
                    <div class="tab-pane fade" :class="{ 'active show': isActive('customer_care') }" id="customer_care">Home content</div>
                    <div class="tab-pane fade" :class="{ 'active show': isActive('students') }" id="students">Profile content</div>
                    <div class="tab-pane fade" :class="{ 'active show': isActive('logs') }" id="logs">Contact content</div>
                  </div>
                </div>
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
      <div v-html="modal.body"></div>
      <template #header>
        <h5 class="modal-title">{{ modal.title }}</h5>
      </template>
      <template #footer>
        <CButton :color="'btn btn-' + modal.color" @click="exit" type="button"
          >Đóng</CButton
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
      modal: {
        title: "THÔNG BÁO",
        show: false,
        color: "success",
        body: "Thêm mới lớp học thành công",
        closeOnBackdrop: false,
        action_exit: "exit",
      },
      html:{
        province: {
          item: '',
          list: []
        },
        district: {
          item: '',
          list: []
        },
        jobs: {
          item: '',
          list: []
        },
        source: {
          item: '',
          list: []
        },
      },
      parent: {
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
      activeItem: 'customer_care'
    };
  },
  created() {
    this.loading.processing = true;
    u.g(`/api/parents/detail/${this.$route.params.id}`)
      .then(response => {
      this.loading.processing = false;
      this.parent = response.data
      this.parent.job = this.html.jobs.list.filter(item => item.id == this.parent.job_id)[0]
      this.parent.source = this.html.source.list.filter(item => item.id == this.parent.source_id)[0]
      this.parent.province = this.html.province.list.filter(item => item.id == this.parent.province_id)[0]
      this.tmp_district_id = this.parent.district_id
    })
  },
  methods: {
    isActive (menuItem) {
      return this.activeItem === menuItem
    },
    setActive (menuItem) {
      this.activeItem = menuItem
    },
    exit() {
      if (this.modal.action_exit == "exit") {
        this.$router.push({ path: "/parents" });
      } else {
        this.modal.show = false;
      }
    },
  },
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
</style>