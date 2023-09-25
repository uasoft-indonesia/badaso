<template>
  <div>
    <badaso-breadcrumb-row> </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('edit_users')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("user.edit.title") }}</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="user.name"
              size="6"
              :label="$t('user.edit.field.name.title')"
              :placeholder="$t('user.edit.field.name.placeholder')"
              :alert="errors.name"
            ></badaso-text>
            <badaso-text
              v-model="user.username"
              size="6"
              :label="$t('user.edit.field.username.title')"
              :placeholder="$t('user.edit.field.username.placeholder')"
              :alert="errors.username"
            ></badaso-text>
            <badaso-text
              v-model="user.email"
              size="6"
              :label="$t('user.edit.field.email.title')"
              :placeholder="$t('user.edit.field.email.placeholder')"
              :alert="errors.email"
            ></badaso-text>
            <badaso-password
              v-model="user.password"
              size="6"
              :label="$t('user.edit.field.password.title')"
              :placeholder="$t('user.edit.field.password.placeholder')"
              :alert="errors.password"
            ></badaso-password>
            <badaso-switch
              v-model="user.emailVerified"
              size="6"
              :label="$t('user.edit.field.emailVerified.title')"
              :placeholder="$t('user.edit.field.emailVerified.placeholder')"
              :alert="errors.emailVerified"
              onLabel="Yes"
              offLabel="No"
              :tooltip="$t('user.help.emailVerified')"
            ></badaso-switch>
            <badaso-text
              v-model="user.phone"
              size="6"
              :label="$t('user.edit.field.phone.title')"
              :placeholder="$t('user.edit.field.phone.placeholder')"
              :alert="errors.phone"
            ></badaso-text>
            <badaso-select
              v-model="user.gender"
              size="6"
              :label="$t('user.edit.field.gender.title')"
              :placeholder="$t('user.edit.field.gender.placeholder')"
              :items="gender"
              :alert="errors.gender"
            ></badaso-select>
            <badaso-upload-image
              v-model="user.avatar"
              size="12"
              :label="$t('user.edit.field.avatar.title')"
              :placeholder="$t('user.edit.field.avatar.placeholder')"
              :alert="errors.avatar"
            ></badaso-upload-image>
            <badaso-textarea
               v-model="user.address"
               size="12"
              :label="$t('user.edit.field.address.title')"
              :placeholder="$t('user.edit.field.address.placeholder')"
              :alert="errors.address"
            ></badaso-textarea>
            <vs-col vs-lg="12">
              <badaso-code-editor
                v-model="user.additionalInfo"
                size="12"
                :label="$t('user.edit.field.additionalInfo.title')"
                :placeholder="$t('user.edit.field.additionalInfo.placeholder')"
                :alert="errors.additionalInfo"
              ></badaso-code-editor>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card class="action-card">
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> {{ $t("user.edit.button") }}
              </vs-button>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "UserManagementEdit",
  components: {},
  data() {
    return {
         errors: {},
    user: {
      email: "",
      name: "",
      username: "",
      phone: "",
      address: "",
      avatar: "",
      password: "",
      emailVerified: false,
      additionalInfo: "",
      gender:"",
    },
    gender: [
        { label: this.$t("user.gender.man"), value: "man" },
        { label: this.$t("user.gender.woman"), value: "woman" },
    ],
    };
  },
  computed: {
    loggedInUser: {
      get() {
        const user = this.$store.getters["badaso/getUser"];
        return user;
      },
    },
  },
  mounted() {
    this.getUserDetail();
  },
  methods: {
    getUserDetail() {
      this.$openLoader();
      this.$api.badasoUser
        .read({
          id: this.$route.params.id,
        })
        .then((response) => {
          this.$closeLoader();
          this.user = response.data.user;
          this.user.password = "";
          this.user.additionalInfo = this.user.additionalInfo
            ? JSON.parse(this.user.additionalInfo)
            : "";
        })
        .catch((error) => {
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    submitForm() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoUser
        .edit({
          id: this.$route.params.id,
          email: this.user.email,
          name: this.user.name,
          username: this.user.username,
          phone: this.user.phone,
          address: this.user.address,
          avatar: this.user.avatar ? this.user.avatar.base64 : null,
          password: this.user.password,
          emailVerified: this.user.emailVerified,
          additionalInfo: JSON.stringify(this.user.additionalInfo),
          gender: this.user.gender,
        })
        .then((response) => {
          if (this.loggedInUser.id == this.user.id) {
            this.$store.commit("badaso/FETCH_USER");
          }
          this.$closeLoader();
          this.$router.push({ name: "UserManagementBrowse" });
        })
        .catch((error) => {
          this.errors = error.errors;
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
  },
};
</script>
