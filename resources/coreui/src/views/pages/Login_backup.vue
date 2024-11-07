<template>
  <div class="d-flex content-center min-vh-100">
    <CContainer fluid>
      <CRow class="justify-content-center">
        <CCol md="6">
          <CCard class="mx-4 mb-0">
            <CCardBody class="p-4">
              <CForm @submit.prevent="login" method="POST">
                <img src="img/logo_scots.png" class="c-avatar-img " style="margin-bottom: 20px;max-width: 180px"/>
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
import u from "../../utilities/utility";

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
      created() {
        // u.g('/api/get-login-redirect').then(response => {
        //   location.href = response.data
        // })
      },
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
            localStorage.setItem('roles', JSON.stringify(response.data.roles));
            localStorage.setItem('user_info', JSON.stringify(response.data.user_info));
            self.$router.push({ path: '/dashboard' });
            // self.$socket.emit('userConnected', response.data.user_id);
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
