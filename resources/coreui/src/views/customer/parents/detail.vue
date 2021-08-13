<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Cập nhật khách hàng </strong>
          </div>
          <div class="card-body">
            <form action method="post">
              <div class="row">
                <div class="col-sm-6">
                  <div class="row no-margin">
                    <div class="form-group col-sm-4">
                      <label for="nf-email">Danh xưng <span class="text-danger"> (*)</span></label>
                      <select class="form-control" v-model="parent.gender">
                        <option value="M">Ông</option>
                        <option value="F">Bà</option>
                      </select>
                    </div>
                    <div class="form-group col-sm-8">
                      <label for="nf-email">Họ tên <span class="text-danger"> (*)</span></label>
                      <input
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="parent.name"
                      />
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="nf-email" >Điện thoại <span class="text-danger"> (*)</span></label>
                      <input
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="parent.mobile_1"
                      />
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="nf-email">Email</label>
                      <input
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="parent.email"
                      />
                    </div>
                    <div class="form-group  col-sm-6">
                      <label for="nf-email">Ngày sinh </label>
                      <datepicker
                        class="form-control calendar"
                        v-model="parent.birthday"
                        placeholder="Chọn ngày sinh nhật"
                        lang="lang"
                        @change="selectDate"
                      />
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="nf-email">Nghề nghiệp</label>
                      <vue-select
                            label="title"
                            placeholder="Chọn nghề nghiệp"
                            :options="html.jobs.list"
                            v-model="parent.job"
                            :searchable="true"
                            language="tv-VN"
                            :onChange="saveJob"
                        ></vue-select>
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Ghi chú</label>
                      <editor
                        :api-key="tinymce.key"
                        :init="tinymce.init"
                        id="input_tinymce"
                        :value="parent.note"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="row no-margin">
                    <div class="form-group col-sm-6">
                      <label for="nf-email">Tỉnh Thành Phố</label>
                      <vue-select
                        label="name"
                        placeholder="Chọn Tỉnh/Thành Phố"
                        :options="html.province.list"
                        v-model="parent.province"
                        :searchable="true"
                        language="tv-VN"
                        :onChange="getDistrict"
                      ></vue-select>
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="nf-email">Quận huyện</label>
                      <vue-select
                            label="name"
                            placeholder="Chọn Quận/Huyện/Thị Xã"
                            :options="html.district.list"
                            v-model="parent.district"
                            :searchable="true"
                            language="tv-VN"
                            :onChange="saveDistrict"
                        ></vue-select>
                    </div>
                    <div class="form-group col-sm-12">
                      <label for="nf-email">Địa chỉ</label>
                      <input
                        class="form-control"
                        type="text"
                        name="title"
                        v-model="parent.address"
                      />
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="nf-email">Nguồn</label>
                      <vue-select
                            label="name"
                            placeholder="Chọn nguồn"
                            :options="html.source.list"
                            v-model="parent.source"
                            :searchable="true"
                            language="tv-VN"
                            :onChange="saveSource"
                        ></vue-select>
                    </div>
                  </div>
                </div>
              </div>
            </form>
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