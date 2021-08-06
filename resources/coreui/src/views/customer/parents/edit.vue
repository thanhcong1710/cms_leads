<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-6">
        <div class="card">
          <loader :active="loading.processing" :text="loading.text" />
          <div class="card-header">
            <strong>Cập nhật học sinh</strong>
          </div>
          <div class="card-body">
            <form action method="post">
              <div class="form-group">
                <label for="nf-email"
                  >Tên học sinh <span class="text-danger"> (*)</span></label
                >
                <input
                  class="form-control"
                  type="text"
                  name="title"
                  v-model="student.name"
                />
              </div>
              <div class="form-group">
                <label for="nf-email"
                  >Ngày sinh <span class="text-danger"> (*)</span></label
                >
                <datepicker
                  class="form-control calendar"
                  v-model="student.birthday"
                  placeholder="Chọn ngày kết thúc"
                  lang="lang"
                  @change="selectDate"
                />
              </div>
              <div class="form-group">
                <label for="nf-email"
                  >Số điện thoại <span class="text-danger"> (*)</span></label
                >
                <input
                  class="form-control"
                  type="text"
                  name="title"
                  v-model="student.phone"
                />
              </div>
              <div class="form-group">
                <label for="nf-email">Email</label>
                <input
                  class="form-control"
                  type="text"
                  name="title"
                  v-model="student.email"
                />
              </div>
              <div class="form-group">
                <label for="nf-email">Ghi chú</label>
                <editor
                  :api-key="tinymce.key"
                  :init="tinymce.init"
                  id="input_tinymce"
                  :value="student.note"
                />
              </div>
              <div class="form-group">
                <label for="nf-email">Trạng thái</label>
                <select class="form-control" v-model="student.status">
                  <option value="0">Ngừng hoạt động</option>
                  <option value="1">Hoạt động</option>
                </select>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <router-link class="btn btn-danger" :to="`/students`">
              <i class="fas fa-undo-alt"></i> Hủy
            </router-link>
            <button class="btn btn-success" type="button" @click="save">
              <i class="fas fa-save"></i> Cập nhật
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

export default {
  components: {
    loader: loader,
    editor: Editor,
    datepicker,
  },
  name: "Edit-Product",
  data() {
    return {
      tinymce: {
        key: "68xdyo8hz3oyr5p47zv3jyvj3h6xg0hc0khthuj123tnskcx",
        init: {
          entity_encoding: "raw",
          height: 300,
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
        body: "Cập nhật lớp  học thành công",
        closeOnBackdrop: false,
        action_exit: "exit",
      },
      student: {
        name: "",
        birthday: "",
        phone: "",
        note: "",
        email: "",
        status: 1,
      },
    };
  },
  created() {
    this.loading.processing = true;
    axios
      .get(
        `api/students/detail/${this.$route.params.id}?token=` +
          localStorage.getItem("api_token")
      )
      .then((response) => {
        this.loading.processing = false;
        this.student = response.data;
      })
      .catch((e) => {
        u.processAuthen(e);
      });
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
      if (this.student.name == "") {
        mess += " - Tên học sinh không được để trống<br/>";
        resp = false;
      }
      if (this.student.birthday == "") {
        mess += " - Ngày sinh không được để trống<br/>";
        resp = false;
      }
      if (this.student.phone == "") {
        mess += " - Số điện thoại không được để trống<br/>";
        resp = false;
      }
      if (this.student.phone != "" && !u.vld.phone(this.student.phone)) {
        mess += " - Số điện thoại không đúng định dạng<br/>";
        resp = false;
      }
      if (this.student.email != "" && !u.vld.email(this.student.email)) {
        mess += " - Email không đúng định dạng<br/>";
        resp = false;
      }
      if (!resp) {
        this.modal.color = "success";
        this.modal.body = mess;
        this.modal.show = true;
        this.modal.action_exit = "close";
        return false;
      }
      this.loading.processing = true;
      this.student.note = tinymce.get("input_tinymce").getContent();
      axios
        .post(
          `/api/students/update/${this.$route.params.id}?token=` +
            localStorage.getItem("api_token"),
          this.student
        )
        .then((response) => {
          this.loading.processing = false;
          if (response.status == 200) {
            this.modal.color = "success";
            this.modal.body = "Cập nhật học sinh thành công";
            this.modal.show = true;
          }
        })
        .catch((e) => {
          u.processAuthen(e);
        });
    },
    exit() {
      if (this.modal.action_exit == "exit") {
        this.$router.push({ path: "/students" });
      } else {
        this.modal.show = false;
      }
    },
  },
};
</script>
