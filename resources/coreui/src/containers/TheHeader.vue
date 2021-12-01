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
      <svg version="1.0" id="cms-logo" xmlns="http://www.w3.org/2000/svg"
                      width="200px" viewBox="0 0 1020.000000 418.000000"
                      preserveAspectRatio="xMidYMid meet">
                      <g transform="translate(0.000000,418.000000) scale(0.100000,-0.100000)"
                      fill="#cd0118" stroke="none">
                        <path d="M6463 4169 c-74 -14 -132 -46 -192 -106 -80 -80 -105 -147 -98 -261
                        11 -185 124 -308 314 -342 147 -26 323 68 384 205 19 41 23 68 23 145 -1 103
                        -15 156 -60 222 -67 97 -245 162 -371 137z"/>
                        <path d="M3585 4157 c-65 -22 -99 -43 -142 -90 -61 -66 -86 -141 -87 -257 -1
                        -83 2 -102 26 -153 59 -125 175 -199 314 -199 194 1 320 99 369 288 13 49 13
                        66 1 124 -28 132 -93 221 -195 268 -45 21 -74 27 -151 29 -59 2 -110 -2 -135
                        -10z"/>
                        <path d="M6357 3240 c-184 -11 -249 -29 -298 -80 -71 -74 -829 -1136 -829
                        -1160 0 -6 164 -10 440 -10 l440 0 0 -980 0 -980 385 0 385 0 0 1605 0 1605
                        -92 0 c-51 0 -136 2 -188 4 -52 3 -162 0 -243 -4z"/>
                        <path d="M8345 3241 c-420 -9 -474 -18 -648 -108 -53 -27 -98 -62 -168 -132
                        -83 -84 -102 -109 -147 -201 -155 -314 -163 -761 -17 -1050 94 -186 246 -317
                        448 -386 157 -54 199 -57 782 -59 594 -2 608 -3 692 -64 87 -63 125 -212 88
                        -349 -15 -54 -27 -75 -65 -113 -87 -87 -47 -83 -865 -89 -712 -5 -721 -5 -779
                        -27 -91 -35 -116 -60 -178 -179 -71 -139 -212 -463 -204 -471 3 -3 473 -6
                        1043 -6 1147 -1 1138 -1 1317 64 304 111 492 369 541 745 72 552 -136 952
                        -568 1094 -149 49 -190 52 -747 56 -329 3 -546 8 -580 15 -149 30 -219 118
                        -220 276 0 107 26 179 80 226 74 63 45 60 860 67 491 5 758 10 784 18 22 6 51
                        19 65 30 36 26 106 154 226 416 l104 226 -373 0 c-205 0 -541 2 -747 4 -206 2
                        -531 0 -724 -3z"/>
                        <path d="M1390 3229 c-958 -108 -1482 -803 -1375 -1826 61 -581 275 -984 631
                        -1194 157 -93 312 -138 565 -166 189 -20 824 -25 1402 -9 l327 8 0 323 0 323
                        -717 5 c-774 5 -821 8 -974 61 -271 94 -384 354 -383 876 1 531 148 797 492
                        892 72 20 110 22 607 28 324 4 542 11 560 17 107 37 127 58 210 224 47 91 205
                        436 205 445 0 8 -1475 1 -1550 -7z"/>
                        <path d="M3370 1630 l0 -1610 385 0 385 0 0 985 c0 578 4 985 9 985 14 0 139
                        -137 279 -305 69 -82 164 -189 211 -238 l85 -87 373 0 c205 0 373 2 373 4 0 5
                        -491 699 -949 1340 -168 237 -323 447 -343 467 -63 65 -94 69 -475 69 l-333 0
                        0 -1610z"/>
                      </g>
                    </svg>
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
            closeOnBackdrop: true,
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