<template>
  <div>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Settings</div>

          <div class="card-body">Invite a player</div>

          <button
            type="button"
            class="btn btn-primary"
            data-toggle="modal"
            data-target="#leagueInviteModal"
          >Go</button>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="leagueInviteModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="leagueInviteModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="leagueInviteModalLabel">Invite in the league</h5>
          </div>

          <div class="modal-body">
            <div class="form-group">
              <label for="email">Player email</label>
              <div class="alert alert-danger" role="alert" v-if="error">{{error}}</div>
              <input type="text" class="form-control" id="email" v-model="email" />
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
      email: "",
      error: {}
    };
  },

  methods: {
    submit() {
      var leagueId = window.location.href.split("/").pop();

      const formData = new FormData();
      formData.append("email", this.email);

      axios
        .post("/api/v1/leagues/"+leagueId+"/invite", formData)
        .then(response => {
          this.error = response.data;
        })
        .catch(error => {
          this.error = error.response.data.error;
        });
    }
  }
};
</script>