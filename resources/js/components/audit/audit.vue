<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive table--no-card m-b-40" v-if="logs.length">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <th>
                                IP
                            </th>
                            <th>
                                Событие
                            </th>
                            <th>
                                Субъект
                            </th>
                            <th>
                                Браузер
                            </th>
                            <th>
                                Дата действия
                            </th>
                        </thead>
                        <tbody>
                        <tr v-for="(log, index) in logs" :key="log.id">
                            <td>
                                {{ log.ip }}
                            </td>
                            <td>
                                {{ log.event }}
                            </td>
                            <td>
                                <a :href="log.subject.link">
                                    {{ log.subject.type}}
                                </a>
                            </td>
                            <td>
                                {{ log.browser }}
                            </td>
                            <td>
                                {{ log.created_at }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <paginate v-model="pagination.page"
                              v-if="pagination.last_page"
                              :page-count="pagination.last_page"
                              :container-class="'pagination'"
                              :page-class="'page-item'"
                              :page-link-class="'page-link'"
                              :prev-text="'Пред.'"
                              :prev-class="'page-item'"
                              :prev-link-class="'page-link'"
                              :next-text="'След.'"
                              :next-class="'page-item'"
                              :next-link-class="'page-link'"
                              :click-handler="switchPage"></paginate>
                </div>
                <div class="alert alert-info" v-else>
                    Не найдено ни одной записи журнала
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "audit",

    data() {
      return {
        logs: [],
        pagination: {
          page: 1,
          last_page: 1
        }
      }
    },

    methods: {
      switchPage(page) {
        this.page = page;
        this.loadData();
      },

      async loadData() {
        const response = await axios.get('/audit', { params: { page: this.pagination.page }});
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.logs = response.data.logs.data;
        this.pagination.last_page = response.data.logs.last_page;
      }
    },

    created() {
      this.loadData();
    }
  }
</script>

<style scoped>

</style>