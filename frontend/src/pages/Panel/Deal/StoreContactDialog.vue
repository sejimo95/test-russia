<template>
  <q-dialog v-model="_dialog" @hide="hide">
    <q-card style="width: 500px; max-width: 100%;">
      <q-card-section class="q-pa-sm">
        <q-toolbar class="q-pa-none">
          <q-btn flat dense round icon="mdi-close" v-close-popup />
          <q-space />
          <q-btn class="q-px-md" dense color="primary" label="Save" no-caps @click="store" />
        </q-toolbar>
      </q-card-section>
      <q-card-section class="q-pa-md">
        <div class="row q-col-gutter-sm">
          <div class="col-12">
            <div>Name</div>
            <q-input filled dense v-model="_info.name" />
          </div>
          <div class="col-12">
            <div>Phone</div>
            <q-input filled dense v-model="_info.phone" />
          </div>
          <div class="col-12">
            <div>Text</div>
            <q-input type="textarea" filled dense v-model="_info.text" />
          </div>
        </div>
      </q-card-section>

      <q-inner-loading :showing="loading">
        <q-spinner size="24px" color="primary" />
      </q-inner-loading>
    </q-card>
  </q-dialog>
</template>

<script>
export default {
  name: 'StoreContactDialog',
  props: ['dialog', 'info'],
  data () {
    return {
      loading: false
    }
  },
  methods: {
    hide () {
      this.loading = false
    },
    store () {
      const app = this
      app.loading = true
      const form = new FormData()
      form.append('id', app._info.id)
      form.append('name', app._info.name)
      form.append('phone', app._info.phone)
      form.append('text', app._info.text)
      var path = app.$s.api + 'api/v1/panel/contact'

      app.$axios.post(path, form)
        .then(function () {
          app.$emit('reload')
          app._dialog = false
        })
        .catch(function (error) {
          const errors = error.response.data.errors
          const errorKey = Object.keys(errors);
          app.notify(errors[errorKey[0]])
        })
        .finally(function () {
          app.loading = false
        })
    }
  },
  computed: {
    _dialog: {
      get () {
        return this.dialog
      },
      set (val) {
        this.$emit('update:dialog', val)
      }
    },
    _info: {
      get () {
        return this.info
      },
      set (val) {
        this.$emit('update:info', val)
      }
    }
  }
}
</script>

<style scoped></style>
