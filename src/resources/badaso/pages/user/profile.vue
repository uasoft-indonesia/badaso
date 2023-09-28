<template>
  <div>
    <badaso-breadcrumb-row> </badaso-breadcrumb-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-tabs :color="adminPanelHeaderFontColor">
            <vs-tab :label="$t('myProfile.profile')">
              <vs-row class="profile__container">
                <badaso-text
                  v-model="user.name"
                  size="12"
                  :label="$t('myProfile.name')"
                  :placeholder="$t('myProfile.name')"
                  :alert="errors.name"
                ></badaso-text>
                <badaso-text
                  v-model="user.username"
                  size="12"
                  :label="$t('myProfile.username')"
                  :placeholder="$t('myProfile.username')"
                  :alert="errors.username"
                ></badaso-text>
                <badaso-select
                v-model="user.gender"
                size="12"
                :label="$t('myProfile.gender')"
                :placeholder="$t('myProfile.gender')"
                :items="gender"
                :alert="errors.gender"
                ></badaso-select>
                 <badaso-text
                  v-model="user.phone"
                  size="12"
                  :label="$t('myProfile.phone')"
                  :placeholder="$t('myProfile.phone')"
                  :alert="errors.phone"
                ></badaso-text>
                <badaso-upload-image
                  v-model="user.avatar"
                  size="12"
                  :label="$t('myProfile.avatar')"
                  :placeholder="$t('myProfile.avatar')"
                  :alert="errors.avatar"
                ></badaso-upload-image>
                <badaso-textarea
                  v-model="user.address"
                  size="12"
                  :label="$t('myProfile.address')"
                  :placeholder="$t('myProfile.address')"
                  :alert="errors.address"
                ></badaso-textarea>
                <vs-col vs-lg="12">
                  <badaso-code-editor
                    v-model="user.additionalInfo"
                    size="12"
                    :label="$t('myProfile.additionalInfo')"
                    :placeholder="$t('myProfile.additionalInfo')"
                    :alert="errors.additionalInfo"
                  ></badaso-code-editor>
                </vs-col>
                <vs-col vs-lg="12">
                  <vs-button
                    color="primary"
                    type="relief"
                    @click="updateProfile"
                  >
                    <vs-icon icon="save"></vs-icon>
                    {{ $t("myProfile.buttons.updateProfile") }}
                  </vs-button>
                </vs-col>
              </vs-row>
            </vs-tab>
            <vs-tab :label="$t('myProfile.email')">
              <vs-row class="profile__container" v-if="shouldVerifyEmail">
                <badaso-text
                  v-model="token"
                  size="12"
                  :label="$t('myProfile.token')"
                  :placeholder="$t('myProfile.token')"
                  :alert="errors.token"
                ></badaso-text>
                <vs-col vs-lg="12">
                  <vs-button color="primary" type="relief" @click="verifyEmail">
                    <vs-icon icon="save"></vs-icon>
                    {{ $t("myProfile.buttons.verifyEmail") }}
                  </vs-button>
                </vs-col>
              </vs-row>
              <vs-row class="profile__container" v-else>
                <badaso-email
                  v-model="user.email"
                  size="12"
                  :label="$t('myProfile.email')"
                  :placeholder="$t('myProfile.email')"
                  :alert="errors.email"
                ></badaso-email>
                <vs-col vs-lg="12">
                  <vs-button color="primary" type="relief" @click="updateEmail">
                    <vs-icon icon="save"></vs-icon>
                    {{ $t("myProfile.buttons.updateEmail") }}
                  </vs-button>
                </vs-col>
              </vs-row>
            </vs-tab>
            <vs-tab :label="$t('myProfile.password')">
              <vs-row class="profile__container">
                <badaso-password
                  v-model="user.oldPassword"
                  size="12"
                  :label="$t('myProfile.oldPassword')"
                  :placeholder="$t('myProfile.oldPassword')"
                  :alert="errors.oldPassword"
                ></badaso-password>
                <badaso-password
                  v-model="user.newPassword"
                  size="12"
                  :label="$t('myProfile.newPassword')"
                  :placeholder="$t('myProfile.newPassword')"
                  :alert="errors.newPassword"
                ></badaso-password>
                <badaso-password
                  v-model="user.newPasswordConfirmation"
                  size="12"
                  :label="$t('myProfile.newPasswordConfirmation')"
                  :placeholder="$t('myProfile.newPasswordConfirmation')"
                  :alert="errors.newPasswordConfirmation"
                ></badaso-password>
                <vs-col vs-lg="12">
                  <vs-button
                    color="primary"
                    type="relief"
                    @click="changePassword"
                  >
                    <vs-icon icon="save"></vs-icon>
                    {{ $t("myProfile.buttons.changePassword") }}
                  </vs-button>
                </vs-col>
              </vs-row>
            </vs-tab>
          </vs-tabs>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "UserProfile",
  components: {},
 data() {
    return{
        errors: {},
        user: {
        email: "",
        name: "",
        username: "",
        avatar: "",
        phone:"",
        address:"",
        additionalInfo: "",
        oldPassword: "",
        newPassword: "",
        newPasswordConfirmation: "",
        gender:"",
        },
        token: "",
        shouldVerifyEmail: false,
        gender: [
            { label: this.$t("user.gender.man"), value: "man" },
            { label: this.$t("user.gender.woman"), value: "woman" },
        ],
    };
  },
  mounted() {
    this.getUser();
  },
  computed: {
    adminPanelHeaderFontColor: {
      get() {
        return "#06bbd3";
      },
    },
  },
  methods: {
    getUser() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoAuthUser
        .user({})
        .then((response) => {
          this.$closeLoader();
          this.user = response.data.user;
          this.user.additionalInfo = this.user.additionalInfo
            ? JSON.parse(this.user.additionalInfo)
            : "";
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
    updateProfile() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoAuthUser
        .updateProfile({
          name: this.user.name,
          username: this.user.username,
          phone: this.user.phone,
          address: this.user.address,
          avatar: this.user.avatar,
          gender: this.user.gender,
          additionalInfo:
            this.user.additionalInfo !== ""
              ? JSON.stringify(this.user.additionalInfo)
              : null,
        })
        .then((response) => {
          this.$store.commit("badaso/FETCH_USER");
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.success"),
            text: "Profile updated",
            color: "success",
          });
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
    updateEmail() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoAuthUser
        .updateEmail({
          email: this.user.email,
        })
        .then((response) => {
          this.shouldVerifyEmail = response.data.shouldVerifyEmail;
          if (this.shouldVerifyEmail) {
            this.$vs.notify({
              title: this.$t("alert.success"),
              text: response.data.message,
              color: "success",
            });
          } else {
            this.$store.commit("badaso/FETCH_USER");
            this.$vs.notify({
              title: this.$t("alert.success"),
              text: "Email updated",
              color: "success",
            });
          }
          this.$closeLoader();
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
    verifyEmail() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoAuthUser
        .verifyEmail({
          email: this.user.email,
          token: this.token,
        })
        .then((response) => {
          this.$store.commit("badaso/FETCH_USER");
          this.shouldVerifyEmail = false;
          this.token = "";
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.success"),
            text: "Email updated",
            color: "success",
          });
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
    changePassword() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoAuthUser
        .changePassword({
          oldPassword: this.user.oldPassword,
          newPassword: this.user.newPassword,
          newPasswordConfirmation: this.user.newPasswordConfirmation,
        })
        .then((response) => {
          this.$closeLoader();
          this.user.oldPassword = "";
          this.user.newPassword = "";
          this.user.newPasswordConfirmation = "";
          this.$vs.notify({
            title: this.$t("alert.success"),
            text: "Password updated",
            color: "success",
          });
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
