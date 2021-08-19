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
                  <ul class="nav nav-tabs" id="myTab1" role="tablist">
<li class="nav-item"><a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Chăm sóc</a></li>
 <li class="nav-item"><a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Học sinh</a></li>
<li class="nav-item"><a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Lịch sử cập nhật</a></li>
</ul>
<div class="tab-content" id="myTab1Content">
<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro
synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher
retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip
placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.
</div>
<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1
labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft
beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad
vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica
VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson
8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester
stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.
</div>
<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro
fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone
skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings
gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel
fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer
blog stumptown. Pitchfork sustainable tofu synth chambray yr.
</div>
</div>
                </div>
              </div>
          </div>
          <div class="card-footer">
            <router-link class="btn btn-danger" :to="`/parents`">
              <i class="fas fa-undo-alt"></i> Hủy
            </router-link>
            <button class="btn btn-success" type="button" @click="save">
              <i class="fas fa-save"></i> Lưu
            </button>
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
      tmp_district_id:"",
    };
  },
  created() {
    u.g(`/api/provinces`)
      .then(response => {
      this.html.province.list = response.data
    })
    u.g(`/api/jobs`)
      .then(response => {
      this.html.jobs.list = response.data
    })
    u.g(`/api/sources`)
      .then(response => {
      this.html.source.list = response.data
    })
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
    selectDate(date) {
      if (date) {
        this.student.birthday = moment(date).format("YYYY-MM-DD");
      }
    },
    save() {
      let mess = "";
      let resp = true;
      if (this.parent.gender == "") {
        mess += " - Danh xưng không được để trống<br/>";
        resp = false;
      }
      if (this.parent.name == "") {
        mess += " - Họ tên không được để trống<br/>";
        resp = false;
      }
      if (this.parent.mobile_1 == "") {
        mess += " - Số điện thoại không được để trống<br/>";
        resp = false;
      }
      if (this.parent.mobile_1 != "" && !u.vld.phone(this.parent.mobile_1)) {
        mess += " - Số điện thoại không đúng định dạng<br/>";
        resp = false;
      }
      if (!resp) {
        this.modal.color = "warning";
        this.modal.body = mess;
        this.modal.show = true;
        this.modal.action_exit = "close";
        return false;
      }
      this.parent.note = tinymce.get("input_tinymce").getContent();
      this.loading.processing = true;
      u.p(`/api/parents/update/${this.$route.params.id}`,this.parent)
        .then((response) => {
          this.loading.processing = false;
          if (response.status == 200) {
            this.modal.color = "success";
            this.modal.body = "Cập nhật khách hàng thành công";
            this.modal.show = true;
            this.modal.action_exit = "exit";
          }
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
    exit() {
      if (this.modal.action_exit == "exit") {
        this.$router.push({ path: "/parents" });
      } else {
        this.modal.show = false;
      }
    },
    getDistrict(data = null){
      if (data && typeof data === 'object') {
        const province_id = data.id
        this.parent.province = data
        this.parent.province_id = province_id
        this.loading.processing = true
        u.g(`/api/provinces/${province_id}/districts`).then(response => {
          this.loading.processing = false
          this.html.district.list = response.data
          if(this.tmp_district_id){
            this.parent.district = this.html.district.list.filter(item => item.id == this.tmp_district_id)[0]
          }else{
            this.parent.district_id = ""
            this.parent.district = ""
          } 
        })
      }else{
        this.parent.province = ""
        this.parent.province_id = ""
        this.html.district.list = []
        this.parent.district = ""
        this.parent.district_id = ""
      }
    },
    saveDistrict(data = null){
      if (data && typeof data === 'object') {
        const district_id = data.id
        this.parent.district = data
        this.parent.district_id = district_id
      }else{
        this.parent.district = ""
        this.parent.district_id = ""
      }
    },
    saveJob(data = null){
      if (data && typeof data === 'object') {
        const job_id = data.id
        this.parent.job = data
        this.parent.job_id = job_id
      }else{
        this.parent.job = ""
        this.parent.job_id = ""
      }
    },
    saveSource(data = null){
      if (data && typeof data === 'object') {
        const source_id = data.id
        this.parent.source = data
        this.parent.source_id = source_id
      }else{
        this.parent.source = ""
        this.parent.source_id = ""
      }
    }
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