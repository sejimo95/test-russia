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
          hide-pagination
        >
          <template v-slot:top-left>Logs</template>
          <template v-slot:body="props">
            <q-tr :props="props">
              <q-td key="id">{{ props.row.id }}</q-td>
              <q-td key="result">
                <q-badge color="red" v-if="props.row.result === 'failed'">{{props.row.result}}</q-badge>
                <q-badge color="green" v-else>{{props.row.result}}</q-badge>
              </q-td>
              <q-td key="action">{{ props.row.action }}</q-td>
              <q-td key="created_at">{{ props.row.created_at }}</q-td>
              <q-td key="updated_at">{{ props.row.updated_at }}</q-td>
            </q-tr>
          </template>
        </q-table>

        <div class="row justify-center q-mt-md">
          <q-pagination v-model="pagination.page" color="teal" :max="lastPage" :max-pages="6" :boundary-numbers="true" size="sm"/>
        </div>
      </div>
    </div>

    <q-inner-loading :showing="loading">
      <q-spinner size="30px" color="primary" />
    </q-inner-loading>
  </q-page>
</template>

<script>
export default {
  name: 'LogIndexPage',
  data () {
    return {
      loading: false,
      lastPage: 0,
      filter: '',
      data: [],
      columns: [
        { name: 'id', label: 'ID', align: 'left', field: 'id' },
        { name: 'result', label: 'Result', align: 'left', field: 'result' },
        { name: 'action', label: 'Action', align: 'left', field: 'action' },
        { name: 'created_at', label: 'Create Date', align: 'left', field: 'created_at' },
        { name: 'updated_at', label: 'Update Date', align: 'left', field: 'updated_at' },
      ],
      pagination: {
        page: 1,
        rowsPerPage: 10,
        rowsNumber: 0
      }
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

      app.$axios.get(app.$s.api + 'api/v1/panel/log', params)
        .then(function (response) {
          app.data = response.data.data
          app.rowsNumber = response.data.meta.total
          app.lastPage = response.data.meta.last_page
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
    }
  },
  watch: {
    'pagination.page' () {
      this.loadData()
    }
  }
}
</script>
