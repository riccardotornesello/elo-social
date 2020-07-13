<template>
  <div>
    <h1>{{league.name}}</h1>
  </div>
</template>

<script>
export default {
  data() {
    return {
      league: {}
    };
  },
  mounted() {
    var leagueId = window.location.href.split("/").pop();
    axios.get("/api/v1/leagues/" + leagueId).then(response => {
      this.league = response.data;
    });
    // TODO: error handling
  }
};
</script>