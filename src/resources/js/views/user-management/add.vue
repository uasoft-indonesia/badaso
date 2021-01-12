<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('add_users')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Add User</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="user.name"
              size="12"
              label="Name"
              placeholder="Name"
              :alert="errors.name"
            ></badaso-text>
            <badaso-text
              v-model="user.email"
              size="6"
              label="Email"
              placeholder="Email"
              :alert="errors.email"
            ></badaso-text>
            <badaso-password
              v-model="user.password"
              size="6"
              label="Password"
              placeholder="Password"
              :alert="errors.password"
            ></badaso-password>
            <badaso-upload-image
              v-model="user.avatar"
              size="12"
              label="Avatar"
              placeholder="Avatar"
              :alert="errors.avatar"
            ></badaso-upload-image>
            <vs-col vs-lg="12" class="mb-3">
              <label for="" class="vs-input--label"
                >Additional Info (JSON)</label
              >
              <badaso-code-editor
                v-model="user.additionalInfo"
                size="12"
                label="Avatar"
                placeholder="Avatar"
                :alert="errors.additionalInfo"
              ></badaso-code-editor>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> Save
              </vs-button>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import BadasoText from "../../components/BadasoText";
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb";
import BadasoPassword from "../../components/BadasoPassword.vue";
import BadasoCodeEditor from "../../components/BadasoCodeEditor.vue";
import BadasoUploadImage from "../../components/BadasoUploadImage.vue";

export default {
  name: "Browse",
  components: {
    BadasoText,
    BadasoBreadcrumb,
    BadasoCodeEditor,
    BadasoPassword,
    BadasoUploadImage,
  },
  data: () => ({
    errors: {},
    user: {
      email: "",
      name: "",
      avatar: {},
      password: "",
      additionalInfo: "",
    },
  }),
  mounted() {},
  methods: {
    submitForm() {
      this.errors = {};
      try {
        if (this.user.additionalInfo && this.user.additionalInfo != "") {
          JSON.parse(this.user.additionalInfo);
        }
        this.$vs.loading({
          type: "sound",
        });
        this.$api.user
          .add({
            email: this.user.email,
            name: this.user.name,
            avatar: this.user.avatar.base64,
            password: this.user.password,
            additionalInfo: this.user.additionalInfo,
          })
          .then((response) => {
            this.$vs.loading.close();
            this.$router.push({ name: "UserBrowse" });
          })
          .catch((error) => {
            this.errors = error.errors
            this.$vs.loading.close();
            this.$vs.notify({
              title: "Danger",
              text: error.message,
              color: "danger",
            });
          });
      } catch (e) {
        this.$vs.notify({
          title: "Danger",
          text: "Additional Info is invalid",
          color: "danger",
        });
      }
    },
  },
};
</script>
