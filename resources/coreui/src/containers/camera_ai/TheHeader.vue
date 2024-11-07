<template>
  <CHeader fixed with-subheader light>
    <CToggler
      in-header
      class="ml-3 d-lg-none"
      @click="$store.commit('toggleSidebarMobile')"
    />
    <CToggler
      in-header
      class="ml-3 d-md-down-none"
      @click="$store.commit('toggleSidebarDesktop')"
    />
    <CHeaderBrand class="mx-auto d-lg-none" to="/camera-ai">
      <img src="img/logo_white.png" class="c-avatar-img " style="max-width: 130px" />
    </CHeaderBrand>

    <CMenu/>

    <CHeaderNav class="mr-4">
      <!-- <CHeaderNavItem class="d-md-down-none mx-2">
        <CHeaderNavLink>
          <select v-model="language" @change="changeLanguage">
            <option value="vi">VI</option>
            <option value="en">EN</option>
          </select>
        </CHeaderNavLink>
      </CHeaderNavItem>
      <CHeaderNavItem class="d-md-down-none mx-2">
        <CHeaderNavLink>
          <CIcon name="cil-envelope-open"/>
        </CHeaderNavLink>
      </CHeaderNavItem>
      <CHeaderNavItem class="d-md-down-none mx-2">
        <CHeaderNavLink>
          <CIcon name="cil-bell"/>
        </CHeaderNavLink>
      </CHeaderNavItem>
      <CHeaderNavItem class="d-md-down-none mx-2">
        <CHeaderNavLink>
          <CIcon name="cil-list"/>
        </CHeaderNavLink>
      </CHeaderNavItem>
      <CHeaderNavItem class="d-md-down-none mx-2">
        <CHeaderNavLink>
          <CIcon name="cil-envelope-open"/>
        </CHeaderNavLink>
      </CHeaderNavItem> -->
      <TheHeaderDropdownAccnt/>
    </CHeaderNav>
    <CSubheader class="px-3">
      <CBreadcrumbRouter class="border-0 mb-0"/>
    </CSubheader>
    <CModal
      :title="modal_checkin.title"
      :show.sync="modal_checkin.show"
      :color="modal_checkin.color"
    >
      <div>
          <div style="float: left; width: 90px;"><img :src="modal_checkin.img_url" class="c-avatar-img"></div>
          <div style="float: left; padding: 10px;">
              <h5 style="margin-bottom: 0px;">{{modal_checkin.name}}</h5>
              <p style="margin-top: 3px;">Mã CRM: {{modal_checkin.crm_id}}</p>
          </div>
      </div>
      <template #header>
        <h5 class="modal-title"><i class="fa fa-check" style="margin-right:10px"></i> {{ modal_checkin.title }}</h5>
      </template>
      <template #footer>
        <CButton :color="'btn btn-secondary'"  type="button"  @click="modal_checkin.show=false"
          >Đóng</CButton
        >
      </template>
    </CModal>
  </CHeader>
</template>
<script>
import CMenu from './Menu'
import TheHeaderDropdownAccnt from './TheHeaderDropdownAccnt'
import u from "../../utilities/utility";

export default {
  name: 'TheHeader',
  components: {
    TheHeaderDropdownAccnt,
    CMenu
  },
  data() {
      return {
          language: this.$i18n.locale,
          modal_checkin: {
            title: "KHÁCH HÀNG CHECKIN",
            show: false,
            color: "success",
            error_message:"",
            name:'',
            crm_id:'',
            img_url:'',
          },
      };
  },
  created(){
    // this.$socket.emit('userConnected', localStorage.getItem("user_id"));
  },
  methods: {
    changeLanguage() {
        localStorage.setItem('language', this.language);
        this.$i18n.locale = this.language;
        fetch(`api/language/${this.language}?token=` + localStorage.getItem("api_token"));
    },
    showModalCheckin(data){
      this.modal_checkin.show = true;
      this.modal_checkin.img_url = data.img_url ? data.img_url : 'img/avatars/avatar.png'
      this.modal_checkin.name = data.name
      this.modal_checkin.crm_id = data.crm_id
    },
  },
  sockets: {
    camera_ai: function (data) { 
      if( localStorage.getItem("branch_id") == 0 || data.branch_id == localStorage.getItem("branch_id")){
        this.showModalCheckin(data)
      }
    },
  },
}
</script>