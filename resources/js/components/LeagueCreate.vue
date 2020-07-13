<template>
  <div>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Welcome</div>

          <div class="card-body">Create a league</div>

          <button
            type="button"
            class="btn btn-primary"
            data-toggle="modal"
            data-target="#leagueCreateModal"
          >Go</button>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="leagueCreateModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="leagueCreateModallLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="leagueCreateModalLabel">Create a league</h5>
          </div>

          <div class="modal-body">
            <div class="form-group">
              <label for="league_name">League name</label>
              <div class="alert alert-danger" role="alert" v-if="errors.league_name">
                <ul>
                  <li v-for="(error, index) in errors.league_name" :key="index">{{error}}</li>
                </ul>
              </div>
              <input type="text" class="form-control" id="league_name" v-model="league_name" />
            </div>

            <div class="form-group">
              <label for="base_points">League base points</label>
              <div class="alert alert-danger" role="alert" v-if="errors.base_points">
                <ul>
                  <li v-for="(error, index) in errors.base_points" :key="index">{{error}}</li>
                </ul>
              </div>
              <input
                type="number"
                class="form-control"
                id="base_points"
                v-model="base_points"
                value="1000"
              />
            </div>

            <div class="form-group">
              <label for="league_type">League type</label>
              <div class="alert alert-danger" role="alert" v-if="errors.league_type">
                <ul>
                  <li v-for="(error, index) in errors.league_type" :key="index">{{error}}</li>
                </ul>
              </div>
              <input
                type="number"
                class="form-control"
                id="league_type"
                v-model="league_type"
                value="0"
              />
            </div>

            <div class="form-group">
              <label for="team_name">Team name</label>
              <div class="alert alert-danger" role="alert" v-if="errors.team_name">
                <ul>
                  <li v-for="(error, index) in errors.team_name" :key="index">{{error}}</li>
                </ul>
              </div>
              <input type="text" class="form-control" id="team_name" v-model="team_name" />
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click="submit">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      league_name: "",
      base_points: "",
      league_type: "",
      team_name: "",
      errors: {}
    };
  },

  methods: {
    submit() {
      const formData = new FormData();
      formData.append("league_name", this.league_name);
      formData.append("base_points", this.base_points);
      formData.append("league_type", this.league_type);
      formData.append("team_name", this.team_name);

      axios
        .post("/api/v1/leagues", formData)
        .then(response => {
          const leagueId = response.data.id;
          window.location.href = "/league/" + leagueId;
        })
        .catch(error => {
          this.errors = error.response.data.error;
        });
    }
  }
};
</script>