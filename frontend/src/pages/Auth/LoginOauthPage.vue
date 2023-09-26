<template>
  <q-page class="flex flex-center">
    <q-card style="max-width: 100%;">

      <div
        class="amocrm_oauth"
        charset="utf-8"
        data-client-id="65f16b73-c49a-4745-9319-94317e80bbe2"
        data-title="Login With Amocrm"
        data-compact="false"
        data-class-name="className"
        data-color="default"
        data-error-callback="functionName"
        data-mode="post_message"
      ></div>

      <q-inner-loading :showing="loading">
        <q-spinner color="primary" />
      </q-inner-loading>
    </q-card>
  </q-page>
</template>

<script>
export default {
  name: 'LoginOauthPage',
  data () {
    return {
      loading: false
    }
  },
  mounted() {
    // createElementScript
    this.createElementScript()

    this.checkLogin()
  },
  methods: {
    checkLogin () {
      const app = this
      app.loading = true

      app.$axios.post(app.$s.api + 'api/v1/auth/is-login')
        .then(function (response) {
            app.$router.push({ path: '/panel/deals' })
        })
        .catch(function (error) {
          localStorage.removeItem('token')
          app.$s.user = []
          app.$axios.defaults.headers.common['Authorization'] = ''

          // changeLocalstorage
          app.changeLocalstorage()
        })
        .finally(function () {
          app.loading = false
        })
    },
    createElementScript() {
      let el = document.getElementById("amocrm-script")
      if (el) {
        location.reload()
        return
      }

      let recaptchaScript = document.createElement('script')
      recaptchaScript.setAttribute("id", "amocrm-script")
      recaptchaScript.setAttribute('src', 'https://www.amocrm.ru/auth/button.js')
      document.head.appendChild(recaptchaScript)
    },
    changeLocalstorage() {
      const app = this
      window.addEventListener('storage', function(e) {
        if (e.key === 'token') {
          app.$axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.token
          app.$router.push({ path: '/panel/deals' })
        }
      })
    }
  }
}
</script>

<style scoped>
.title {
  text-align: center;
  padding: 10px 0;
}
</style>
