<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="title-1 m-b-25">СМС-сообщения</h2>
                    </div>
                    <div class="col">
                        <button class="btn btn-outline-success" @click="show('sms_form')">
                            Отправить СМС
                        </button>
                    </div>
                </div>
                <div class="table-responsive table--no-card m-b-40" v-if="smses.length">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <th>
                                Номер телефона
                            </th>
                            <th>
                                Сообщение
                            </th>
                            <th>
                                Ссылка
                            </th>
                            <th>
                                Дата отправки
                            </th>
                        </thead>
                        <tbody>
                            <tr v-for="(sms, index) in smses" :key="sms.id">
                                <td>
                                    {{ sms.phone }}
                                </td>
                                <td @click="getInfo(sms.id)">
                                    {{ sms.message }}
                                </td>
                                <td>
                                    {{ sms.link }}
                                </td>
                                <td>
                                    {{ sms.created_at }}
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
                    Не найдено ни одного отправленного СМС-сообщения
                </div>
            </div>
        </div>
        <modal name="sms_form" responsible="true" height="auto">
            <div class="modal-header">
                Отправить СМС
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">
                        Номер телефона
                    </label>
                    <input type="text" class="form-control" v-model="new_sms.phone">
                </div>
                <div class="form-group">
                    <label for="">
                        Текст сообщения
                    </label>
                    <textarea class="form-control" v-model="new_sms.message">$link</textarea>
                </div>
                <div class="form-group">
                    <label for="">
                        Ссылка
                    </label>
                    <input type="text" class="form-control" v-model="new_sms.link">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" @click="close('sms_form')" v-if="! checkLenFormParams">
                    Закрыть
                </button>
                <button class="btn btn-primary" v-else @click="sendSMS">
                    Отправить
                </button>
            </div>
        </modal>
        <!--<modal name="sms_info">-->
            <!--<div class="modal-header">-->
                <!--{{ sms-info.phone }}-->
            <!--</div>-->
            <!--<div class="body">-->
                <!--{{ sms_info.message }}-->
                <!--<p>-->
                    <!--Ссылка: {{ sms_info.link }}-->
                <!--</p>-->
            <!--</div>-->
            <!--<div class="modal-footer">-->
                <!--<button class="btn btn-outline-primary" @click="close('sms_info')">-->
                    <!--Закрыть-->
                <!--</button>-->
            <!--</div>-->
        <!--</modal>-->
    </div>
</template>

<script>
  export default {
    name: "sms",
    data() {
      return {
        smses: [],
        new_sms: {
          phone: '',
          link: '',
          message: ''
        },
        sms_info: {
          phone: '',
          link: '',
          message: ''
        },

        pagination: {
          last_page: 1,
          page: 1
        }
      }
    },

    methods: {
      switchPage(page) {
        this.pagination.page = page;
        this.loadData();
      },

      async sendSMS() {
        const response = await axios.post('/sms/create', this.new_sms);
        if (response.status !== 200 || response.data.status === 'error')  {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.$swal('Успешно!', response.data.msg, 'success');
        this.loadData();
      },

      async loadData() {
        const response = await axios.get('/sms', { params: { page: this.pagination.page }});
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.smses = response.data.sms.data;
        this.pagination.last_page = response.data.sms.last_page;
      },

      async getInfo(id) {
        const response = axios.get(`'/sms/${id}`);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.sms_info = response.data.sms;
        this.show('sms_info');
      },

      checkLenFormParams() {
        return this.new_sms.phone.length && this.new_sms.message.length;
      },

      close(modal) {
        this.$modal.hide(modal);
      },

      show(modal) {
        this.$modal.show(modal);
      }
    },

    created() {
      this.loadData();
    }
  }
</script>

<style scoped>

</style>