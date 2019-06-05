<template>
    <div>
        <h2>Hello world!</h2>
    </div>
</template>

<script>
  export default {
    name: "track",
    data() {
      return {
        query: {
          type: '',
          id: ''
        },
        url: ''
      }
    },
    methods: {
      async sendData() {
        const response = await axios.post(this.url, {...this.query});
      },

      getQuery() {
        this.query.type = this.getParameterByName('type');
        this.query.id = this.getParameterByName('id');
      },

      getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
      },

      getUrl() {
        switch (this.query.type) {
          case 'email': this.url = '/emails/log'; break;
          case 'sms': this.url = '/sms/log'; break;
          default: this.url = '/link/log'; break;
        }
      },
    },
    mounted() {
      this.getQuery();
      this.getUrl();
      this.sendData();
    }
  }
</script>

<style scoped>

</style>