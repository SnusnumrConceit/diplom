<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="title-1 m-b-25">Пользователи</h2>
                    </div>
                    <button class="btn btn-outline-success" @click="">
                        Добавить
                    </button>
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
                <div class="table-responsive table--no-card m-b-40" v-if="users.length">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <th @click="setFilter('number')" class="text-left">
                            Column #1
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'number' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'number' && filter.type === 'ASC'"></i>
                        </th>
                        <th @click="setFilter('price')" class="text-left">
                            Column #1
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'price' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'price' && filter.type === 'ASC'"></i>
                        </th>
                        <th></th>
                        </thead>
                        <tbody v-for="(user, index) in users" :key="user.id">
                        <td>
                            <i class="fa fa-trash text-danger" @click="remove(index, user.id)"></i>
                        </td>
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
    </div>
</template>

<script>
  export default {
    name: "users",
    data() {
      return {
        users: [],
        pagination: {
          last_page: 1,
          page: 1
        }
      }
    },
    methods: {
      async loadData() {
        const response = await axios.get('/users', {params: {page: this.pagination.page }});
        if (response.status !== 200 |response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        }
        this.users = response.data.users.data;
        this.pagination.last_page = response.data.users.last_page;
      },

      switchPage(page) {
        this.page = page;
        this.loadData();
      }
    },

    created() {
      this.loadData();
    }
  }
</script>

<style scoped>

</style>