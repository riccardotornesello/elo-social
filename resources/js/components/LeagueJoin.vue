<template>
  <div>
    <h3>Enter the league?</h3>
    <button type="button" class="btn btn-primary" @click="submit">Yes</button>
    <a class="btn btn-primary" href="/home" role="button">No</a>
    <input type="text" class="form-control" id="team_name" v-model="team_name" />
    <h3>Response: {{ response }}</h3>
  </div>
</template>

<script>
export default {
  data() {
    return {
      response: "",
      team_name: ""
    };
  },

  methods: {
    submit() {
      var leagueId = window.location.href.split("/")[4];

      const formData = new FormData();
      formData.append("team_name", this.team_name);

      axios
        .post("/api/v1/leagues/" + leagueId + "/join", formData)
        .then(response => {
          this.response = response.data;
        })
        .catch(error => {
          this.response = error.response.data;
        });
    }
  }
};
</script>