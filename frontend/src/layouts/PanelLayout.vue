<template>
  <q-layout v-if="'id' in user" view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn flat dense round icon="mdi-menu" aria-label="Menu" @click="toggleLeftDrawer"/>
        <q-btn flat dense round color="red" class="q-ml-md" icon="mdi-power" aria-label="Logout" @click="logout"/>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered>
      <q-list>
        <q-item class="q-mb-md">
          <q-item-section>
            <q-img
              alt="logo"
              fit="contain"
              src="~assets/logo.png"
              style="height: 150px;"
            />
          </q-item-section>
        </q-item>
        <q-item clickable exact to="/deals">
          <q-item-section avatar>
            <q-icon name="mdi-circle-small" />
          </q-item-section>

          <q-item-section>
            <q-item-label>Deals</q-item-label>
          </q-item-section>
        </q-item>
        <q-item clickable exact to="/logs">
          <q-item-section avatar>
            <q-icon name="mdi-circle-small" />
          </q-item-section>

          <q-item-section>
            <q-item-label>Logs</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
export default {
  name: 'PanelLayout',
  data () {
    return {
      leftDrawerOpen: false
    }
  },
  mounted() {
    if (!this.token) {
      this.$router.push({ path: '/login' })
    }
    else if (!('id' in this.$s.user)) {
      this.checkLogin()
    }
  },
  methods: {
    toggleLeftDrawer () {
      this.leftDrawerOpen = !this.leftDrawerOpen
    },
    checkLogin () {
      const app = this
      app.loading = true

      app.$axios.post(app.$s.api + 'api/v1/auth/is-login')
        .then(function (response) {
          app.$s.user = response.data.user
        })
        .catch(function (error) {
          localStorage.removeItem('token')
          app.$s.user = []
          app.$axios.defaults.headers.common['Authorization'] = ''
          app.$router.push({ path: '/login' })
        })
        .finally(function () {
          app.loading = false
        })
    },
    logout () {
      const app = this
      app.loading = true

      app.$axios.post(app.$s.api + 'api/v1/auth/logout')
        .then(function (response) {
          if (response.data.success) {
            localStorage.removeItem('token')
            app.$s.user = []
            app.$axios.defaults.headers.common['Authorization'] = ''
            app.$router.push({ path: '/login' })
          }
        })
        .finally(function () {
          app.loading = false
        })
    }
  },
  computed: {
    user() {
      return this.$s.user || []
    },
    token() {
      return 'token' in localStorage
    }
  }
}
</script>
