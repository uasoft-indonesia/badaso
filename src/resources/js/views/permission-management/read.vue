<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4">
        <div style="float: right">
          <vs-button color="warning" type="relief"
            :to="{name: 'PermissionEdit', params: {id: $route.params.id}}"
            v-if="$helper.isAllowed('edit_permissions')"
            ><vs-icon icon="edit"></vs-icon> {{ $t('permission.detail.button') }}</vs-button
          >
        </div>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('read_permissions')">
      <vs-col vs-lg="12">
        <vs-card>
            <div slot="header">
            <h3>{{ $t('permission.detail.title') }}</h3>
          </div>
            <table class="table">
                <tr>
                    <th>{{ $t('permission.detail.key') }}</th>
                    <td>{{ permission.key }}</td>
                </tr>
                <tr>
                    <th>{{ $t('permission.detail.description') }}</th>
                    <td>{{ permission.description }}</td>
                </tr>
                <tr>
                    <th>{{ $t('permission.detail.tableName') }}</th>
                    <td>{{ permission.tableName }}</td>
                </tr>
                <tr>
                    <th>{{ $t('permission.detail.alwaysAllow.title') }}</th>
                    <td>
                        <span v-if="permission.alwaysAllow === 1">{{ $t('permission.detail.alwaysAllow.yes') }}</span>
                        <span v-else>{{ $t('permission.detail.alwaysAllow.no') }}</span>
                    </td>
                </tr>
            </table>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t('permission.warning.notAllowedToRead') }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb.vue";

export default {
  name: "Browse",
  components: {
      BadasoBreadcrumb
  },
  data: () => ({
    permission: {}
  }),
  mounted() {
        this.getPermissionDetail();
  },
  methods: {
    getPermissionDetail() {
        this.$vs.loading({
        type: "sound",
      });
      this.$api.permission
        .read({
            id: this.$route.params.id
        })
        .then((response) => {
          this.$vs.loading.close();
          this.permission = response.data.permission;
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: this.$t('alert.danger'),
            text: error.message,
            color: "danger",
          });
        });
    }
  },
};
</script>