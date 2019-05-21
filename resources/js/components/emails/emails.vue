<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="title-1 m-b-25">Письма</h2>
                    </div>
                    <div class="col">
                        <button class="btn btn-outline-success" @click="$modal.show('email')">
                            Отправить письмо
                        </button>
                    </div>
                    <!--<div class="col-md-4 players__search">-->
                        <!--<div class="input-group">-->
                            <!--&lt;!&ndash;<div class="input-group-btn">&ndash;&gt;-->
                            <!--&lt;!&ndash;<button class="btn btn-secondary" :disabled="search.processing" @click="searchWrap">&ndash;&gt;-->
                            <!--&lt;!&ndash;<i class="fa fa-search"></i>&ndash;&gt;-->
                            <!--&lt;!&ndash;</button>&ndash;&gt;-->
                            <!--&lt;!&ndash;</div>&ndash;&gt;-->
                            <!--<input type="text"-->
                                   <!--class="form-control"-->
                                   <!--style="font-size: 14px;"-->
                                   <!--v-model.lazy="search.keyword"-->
                                   <!--v-debounce="300"-->
                                   <!--placeholder="Поиск...">-->
                            <!--<button v-if="isSearch && search.keyword.length" @click="resetSearch">-->
                                <!--<i class="fa fa-times"></i>-->
                            <!--</button>-->
                        <!--</div>-->
                    <!--</div>-->
                </div>
                <div class="table-responsive table--no-card m-b-40" v-if="emails.length">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <th @click="setFilter('number')" class="text-left">
                                SMTP
                                <!--<i class="fa fa-sort-amount-up" v-if="filter.name === 'number' && filter.type === 'DESC'"></i>-->
                                <!--<i class="fa fa-sort-amount-down" v-else-if="filter.name === 'number' && filter.type === 'ASC'"></i>-->
                            </th>
                            <th @click="setFilter('price')" class="text-left">
                                Recipient/Получатель
                                <!--<i class="fa fa-sort-amount-up" v-if="filter.name === 'price' && filter.type === 'DESC'"></i>-->
                                <!--<i class="fa fa-sort-amount-down" v-else-if="filter.name === 'price' && filter.type === 'ASC'"></i>-->
                            </th>
                            <th>
                                Subject/Тема
                            </th>
                            <th>
                                Opens/Открытие
                            </th>
                            <th>
                                Clicks/Клики
                            </th>
                            <th>
                                Sent At/Отправлено
                            </th>
                            <th>
                                View Email/Тело письма
                            </th>
                            <th>
                                Clicks/Клики
                            </th>
                        </thead>
                        <tbody>
                            <tr v-for="(email, index) in emails" :key="email.id" :class="email.report_class">
                                <td>
                                    <a @click="show(email.id, 'smtp_detail')">
                                        {{ email.smtp_info }}
                                    </a>
                                    <!--<i class="fa fa-trash text-danger" @click="remove(index, email.id)"></i>-->
                                </td>
                                <td>{{ email.recipient }}</td>
                                <td>{{ email.subject }}</td>
                                <td>{{ email.opens }}</td>
                                <td>{{ emails.clicks }}</td>
                                <td>{{ email.created_at }}</td>
                                <td>
                                    <button class="btn btn-outline-info" @click="show(email.id, 'content')">
                                        Содержимое
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-outline-info" v-if="email.clicks > 0" @click="show(email.id, 'url_detail')">
                                        Details/Детали
                                    </button>
                                    <span v-else> Нет кликов </span>
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
                    Не найдено ни одного отправленного письма
                </div>
            </div>
        </div>
        <modal name="content" :responsible="true" height="auto">
            <div class="modal-header">
                <h2>
                    Содержимое письма
                </h2>
            </div>
            <div class="modal-body">
                <code v-html="content"></code>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-primary" @click="close('content')">
                    Закрыть
                </button>
            </div>
        </modal>
        <modal name="url_detail" :responsible="true" height="auto">
            <!--<div class="row">-->
                <!--<div class="col-sm-12">-->
                    <!--Recipient: {{$details->first()->email->recipient}} <br>-->
                    <!--Subject: {{$details->first()->email->subject}} <br>-->
                    <!--Sent At: {{$details->first()->email->created_at->format(config('mail-tracker.date-format'))}}-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="row">-->
                <!--<div class="col-sm-12">-->
                    <!--<table class="table table-striped">-->
                        <!--<th>Url</th>-->
                        <!--<th>Clicks</th>-->
                        <!--<th>First Click At</th>-->
                        <!--<th>Last Click At</th>-->
                        <!--@foreach($details as $detail)-->
                        <!--<tr>-->
                            <!--<td>{{$detail->url}}</td>-->
                            <!--<td>{{$detail->clicks}}</td>-->
                            <!--<td>{{$detail->created_at->format(config('mail-tracker.date-format'))}}</td>-->
                            <!--<td>{{$detail->updated_at->format(config('mail-tracker.date-format'))}}</td>-->
                        <!--</tr>-->
                        <!--@endforeach-->
                    <!--</table>-->
                <!--</div>-->
            <!--</div>-->
        </modal>
        <modal name="smtp_detail" :responsible="true" height="auto">
            <!--<div class="row">-->
                <!--<div class="col-sm-12 text-center">-->
                    <!--<h3>-->
                        <!--SMTP detail for Email ID {{$details->id}}-->
                    <!--</h3>-->
                    <!--<a href="{{ route('mailTracker_ShowEmail',$details->id) }}" class="btn btn-default" target="_blank">-->
                        <!--View Message-->
                    <!--</a>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="row">-->
                <!--<div class="col-sm-12">-->
                    <!--Recipient: {{$details->recipient}} <br>-->
                    <!--Subject: {{$details->subject}} <br>-->
                    <!--Sent At: {{$details->created_at->format(config('mail-tracker.date-format'))}} <br>-->
                    <!--SMTP Details: {{ $details->smtp_info }}-->
                <!--</div>-->
            <!--</div>-->
        </modal>
        <modal name="email" :responsible="true" height="auto">
            <div class="modal-header">
                <h2>
                    Форма отправления письма
                </h2>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="email"
                                   class="form-control"
                                   v-model.trim="email_form.email">
                        </div>
                        <div class="form-group">
                            <label for="">Имя</label>
                            <input type="text"
                                   class="form-control"
                                   v-model.trim="email_form.name">
                        </div>
                        <div class="form-group">
                            <label for="">Тема</label>
                            <input type="text"
                                   class="form-control"
                                   v-model.trim="email_form.subject">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-success" @click="sendEmail">
                    Отправить
                </button>
            </div>
        </modal>
    </div>
</template>

<script>
  export default {
    name: "emails",
    data() {
      return {
        emails: [],
        pagination: {
          page: 1,
          last_page: 1
        },

        content: ``,
        url_detail: {},
        smtp_detail: {},

        email_form: {
          email: '',
          subject: '',
          name: ''
        },

        swal: {
          errors: [],
          message: ''
        }
      }
    },
    methods: {
      switchPage(page) {
        this.page = page;
        this.loadData();
      },

      async sendEmail() {
        const response = await axios.post('/emails/send', this.email_form);
        if (response.data.status === 'error') {
          this.swal.errors = (response.data.errors !== undefined) ? response.data.errors : {};
          this.swal.message = this.getSwalMessage();
          this.$swal({
            title: 'Ошибка!',
            html: response.data.msg + this.swal.message,
            type: 'error'
          });
          return false;
        }
        console.log(response.data);
        this.close('email');
        this.loadData();
        this.email_form = {};
      },

      async show(id, route) {
        const response = await axios.get(`/emails/${id}/${route}`);
        if (response.data.status === 'error' || response.status !== 200) {
           this.$swal('Ошибка!', response.data.msg, 'error');
           return false;
        }

        switch (route) {
          case 'content': this.content = response.data.content; break;
          case 'url_detail': this.url_detail = response.data.url_detail; break;
          case 'smtp_detail': this.smtp_detail = response.data.smtp_detail; break;
          default: break;
        }
        this.$modal.show(route);
      },

      close(route) {
        switch (route) {
          case 'content': this.content = ``; break;
          case 'url_detail': break;
          case 'smtp_detail': break;
          default: break;
        }
        this.$modal.hide(route);
      },

      async loadData() {
        const response = await axios.get('/emails', { params: { page: this.pagination.page } });
        if (response.data.status === 'error' || response.status !== 200) {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.emails = response.data.emails.data;
        this.pagination.last_page = response.data.emails.last_page;
      },

      getSwalMessage() {
        return (Object.keys(this.swal.errors).length) ?
            `<div class="alert alert-danger m-t-20">
                        <ul class="p-l-20 p-r-20">
                            ${Object.values(this.swal.errors).map(err => `<li class="text-danger">${err[0]}</li>`)}
                        </ul>
                </div>`
            : '';
      }
    },
    created() {
      this.loadData();
    }
  }
</script>

<style scoped>

</style>