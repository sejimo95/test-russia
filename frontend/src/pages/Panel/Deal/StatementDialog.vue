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
            <div>Date</div>
            <q-input ref="dateInput" filled dense v-model="_info.date" @click="$refs.dateInput.select()">
              <template v-slot:prepend>
                <q-icon color="primary" name="mdi-calendar-month">
                  <q-menu ref="dateMenu">
                    <q-date today-btn landscape v-model="_info.date" @update:model-value="$refs.dateMenu.hide()" />
                  </q-menu>
                </q-icon>
              </template>
            </q-input>
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
  name: 'StatementDialog',
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
      form.append('date', app._info.date)
      form.append('name', app._info.name)

      var path = app.$s.api + 'api/v1/panel/client/statement'
      if (app._info.id > 0) {
        form.append('_method', 'PATCH');
        path = path + '/' + app._info.id
      }

      app.$axios.post(path, form)
        .then(function (response) {
          if (response.data.success) {
            app.$emit('reload')
            app._dialog = false
          }
          app.notify(response.data.message)
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
