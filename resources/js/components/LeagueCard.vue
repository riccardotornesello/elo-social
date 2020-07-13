<template>
  <div class="col-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{ league.name }}</h5>
        <p class="card-text">{{ league.pivot.name }}</p>
        <p class="card-text">{{ league.pivot.rating }} points</p>
        <a :href="'league/' + league.id" class="btn btn-primary">Go</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    league: Object
  }
};
</script>