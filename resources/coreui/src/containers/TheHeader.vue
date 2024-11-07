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
    <CHeaderBrand class="mx-auto d-lg-none" to="/">
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
      :title="modal_inbound.title"
      :show.sync="modal_inbound.show"
      :color="modal_inbound.color"
      :closeOnBackdrop="modal_inbound.closeOnBackdrop"
    >
      <div>
        <div class="form-in-list" style="text-align:center">
          <h3>{{modal_inbound.phone}}</h3>
          <h2>{{modal_inbound.name}}</h2>
        </div>
      </div>
      <template #header>
        <h5 class="modal-title"> <i class="fa fa-phone" style="margin-right:10px"></i> {{ modal_inbound.title }}</h5>
      </template>
      <template #footer>
        <CButton :color="'btn btn-info'"  type="button" @click="viewDetail"
          >Xem thông tin</CButton
        >
        <CButton :color="'btn btn-secondary'"  type="button"  @click="modal_inbound.show=false"
          >Đóng</CButton
        >
      </template>
    </CModal>
  </CHeader>
</template>
<script>
import CMenu from './Menu'
import TheHeaderDropdownAccnt from './TheHeaderDropdownAccnt'
import u from "../utilities/utility";

export default {
  name: 'TheHeader',
  components: {
    TheHeaderDropdownAccnt,
    CMenu
  },
  data() {
      return {
          language: this.$i18n.locale,
          modal_inbound: {
            title: "CUỘC GỌI ĐẾN TỪ KHÁCH HÀNG",
            show: false,
            color: "success",
            closeOnBackdrop: false,
            error_message:"",
            parent_id:'',
            name:'',
            phone:'',
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
    changeModalInbound(data){
      if(this.modal_inbound.show == true){
        this.modal_inbound.title = "CUỘC GỌI NHỠ TỪ KHÁCH HÀNG"
        this.modal_inbound.color = "danger"
      }
    },
    showModalInbound(data){
      u.g(`/api/parents/get_info_by_phone/${data.phone}`)
        .then((response) => {
          this.modal_inbound.show = true;
          this.modal_inbound.name = response.data.name
          this.modal_inbound.phone = response.data.mobile_1
          this.modal_inbound.parent_id = response.data.id
        })
        .catch((e) => {
        });
    },
    viewDetail(){
      this.modal_inbound.show = false;
       this.$router.push({ path: `/parents/${this.modal_inbound.parent_id}/detail` });
    }
  },
  sockets: {
    inbound: function (data) { 
      if(data.user_id == localStorage.getItem("user_id")){
        this.showModalInbound(data)
      }
    },
    call_end: function (data) { 
      if(data.user_id == localStorage.getItem("user_id")){
        this.changeModalInbound(data)
      }
    },
  },
}
</script>