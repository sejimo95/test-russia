<template>
  <q-page style="display: grid;">
    <div class="q-pa-md column">
      <div class="col">
        <q-table
          :rows-per-page-options="[pagination.rowsPerPage]"
          :columns="columns"
          :filter="filter"
          :rows="data"
          row-key="id"
          :pagination="pagination"
          @request="onRequest"
        >
          <template v-slot:top-left>
            Deals
          </template>
          <template v-slot:top-right>
            <q-input debounce="600" filled dense v-model="filter" label="Search">
              <template v-slot:append>
                <q-icon name="mdi-magnify" />
              </template>
            </q-input>
          </template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="menu">
                <q-btn flat dense round icon="mdi-dots-vertical" @click.stop.prevent v-if="props.row.contacts_count === 0">
                  <q-menu>
                    <q-list style="min-width: 150px;">
                      <q-item clickable v-close-popup @click="storeData(props.row.id)">
                        <q-item-section avatar><q-icon name="mdi-pencil" color="primary" /></q-item-section>
                        <q-item-section>Edit</q-item-section>
                      </q-item>
                    </q-list>
                  </q-menu>
                </q-btn>
              </q-td>
              <q-td key="id">{{ props.row.id }}</q-td>
              <q-td key="name">{{ props.row.name }}</q-td>
              <q-td key="created_at">{{ props.row.created_at }}</q-td>
              <q-td key="updated_at">{{ props.row.updated_at }}</q-td>
            </q-tr>
          </template>
        </q-table>
      </div>
    </div>

    <StoreContactDialog v-model:dialog="StoreContactDialogShow" :info="StoreContactDialogData" @reload="loadData" />
    <q-inner-loading :showing="loading">
      <q-spinner size="30px" color="primary" />
    </q-inner-loading>
  </q-page>
</template>

<script>
import _ from 'lodash'
import StoreContactDialog from './StoreContactDialog'

export default {
  name: 'IndexPage',
  components: { StoreContactDialog },
  data () {
    return {
      loading: false,
      filter: '',
      data: [],
      columns: [
        { name: 'menu', align: 'left' },
        { name: 'id', label: 'ID', align: 'left', field: 'id' },
        { name: 'name', label: 'Name', align: 'left', field: 'name' },
        { name: 'created_at', label: 'Create Date', align: 'left', field: 'created_at' },
        { name: 'updated_at', label: 'Update Date', align: 'left', field: 'updated_at' },
      ],
      pagination: {
        page: 1,
        rowsPerPage: 10,
        rowsNumber: 0
      },
      StoreContactDialogShow: false,
      StoreContactDialogData: []
    }
  },
  mounted() {
    this.loadData()
  },
  methods: {
    onRequest (props) {
      const app = this
      app.loading = true
      var params = {
        params: {
          page: props.pagination.page,
          rowsPerPage: props.pagination.rowsPerPage,
          search: props.filter,
        }
      }

      app.$axios.get(app.$s.api + 'api/v1/panel/deals', params)
        .then(function (response) {
          app.data = response.data.data
          app.rowsNumber = response.data.meta.total
        })
        .catch(function (error) {
          app.notify(error.response.data.message)
        })
        .finally(function () {
          app.loading = false
        })
    },
    loadData () {
      this.onRequest({ pagination: this.pagination, filter: this.filter })
    },
    storeData (dealId) {
      this.StoreContactDialogData = {
        id: dealId,
        name: '',
        phone: '',
        text: ''
      }
      this.StoreContactDialogShow = true
    },
  }
}
</script>
