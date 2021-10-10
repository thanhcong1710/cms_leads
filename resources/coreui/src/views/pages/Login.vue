<template>
  <div class="d-flex content-center min-vh-100">
    <CContainer fluid>
      <CRow class="justify-content-center">
        <CCol md="6">
          <CCard class="mx-4 mb-0">
            <CCardBody class="p-4">
              <CForm @submit.prevent="login" method="POST">
                <svg  style="margin:20px" version="1.0" id="cms-logo" xmlns="http://www.w3.org/2000/svg"
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
                <h1>Login</h1>
                <p class="text-muted">Sign In to your account</p>
                <CInput
                  v-model="email"
                  prependHtml="<i class='cui-user'></i>"
                  placeholder="Username"
                  autocomplete="username email"
                >
                  <template #prepend-content><CIcon name="cil-user"/></template>
                </CInput>
                <CInput
                  v-model="password"
                  prependHtml="<i class='cui-lock-locked'></i>"
                  placeholder="Password"
                  type="password"
                  autocomplete="curent-password"
                >
                  <template #prepend-content><CIcon name="cil-lock-locked"/></template>
                </CInput>
                <CRow>
                  <CCol col="6" class="text-left">
                    <CButton type="submit" color="primary" class="px-4">Login</CButton>
                  </CCol>
                  <CCol col="6" class="text-right">
                    <CButton color="link" class="px-0">Forgot password?</CButton>
                  </CCol>
                </CRow>
              </CForm>
              <p style="color:red">{{message}}</p>
            </CCardBody>
         </CCard>
        </CCol>
      </CRow>
    </CContainer>
  </div>
</template>

<script>

import axios from "axios";

    export default {
      name: 'Login',
      data() {
        return {
          email: '',
          password: '',
          showMessage: false,
          message: '',
        }
      },
      sockets: {
        connect: function () {
          console.log('socket to notification channel connected')
        },
      },
      created() {},
      methods: {
        goRegister(){
          this.$router.push({ path: 'register' });
        },
        login() {
          let self = this;
          axios.post(  '/api/login', {
            email: self.email,
            password: self.password,
          }).then(function (response) {
            self.email = '';
            self.password = '';
            localStorage.setItem("user_id", response.data.user_id);
            localStorage.setItem("api_token", response.data.access_token);
            localStorage.setItem('roles', response.data.roles);
            self.$router.push({ path: '/dashboard' });
            self.$socket.emit('userConnected', response.data.user_id);
          })
          .catch(function (error) {
            self.message = 'Incorrect E-mail or password';
            self.showMessage = true;
            console.log(error);
          });
  
        }
      }
    }

</script>
