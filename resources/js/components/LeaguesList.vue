<template>
  <div>
    <h2>Your leagues</h2>
    <div class="row">
      <league-card v-for="league in leagues" :key="league.id" :league="league"></league-card>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      leagues: ""
    };
  },

  mounted() {
    axios.get("/api/v1/leagues").then(response => {
      this.leagues = response.data;
    });
  }
};
</script>