<template>
  <div>
    <badaso-breadcrumb-hover full>
      <template slot="action">
        <download-excel
          :data="users"
          :fields="fieldsForExcel"
          :worksheet="'User Management'"
          :name="'User Management' + '.xls'"
          class="crud-generated__excel-button"
        >
          <badaso-dropdown-item icon="file_upload">
            {{ $t("action.exportToExcel") }}
          </badaso-dropdown-item>
        </download-excel>
        <badaso-dropdown-item icon="file_upload" @click="generatePdf">
          {{ $t("action.exportToPdf") }}
        </badaso-dropdown-item>
        <badaso-dropdown-item icon="add" :to="{ name: 'UserManagementAdd' }">
          {{ $t("action.add") }}
        </badaso-dropdown-item>
        <badaso-dropdown-item
          icon="delete_sweep"
          v-if="selected.length > 0 && $helper.isAllowed('delete_roles')"
          @click.stop
          @click="confirmDeleteMultiple"
        >
          {{ $t("action.bulkDelete") }}
        </badaso-dropdown-item>
      </template>
    </badaso-breadcrumb-hover>
    <vs-row v-if="$helper.isAllowed('browse_users')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("user.title") }}</h3>
          </div>
          <div>
            <badaso-table
              multiple
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="users"
              stripe
              description
              :description-items="descriptionItems"
              :description-title="$t('user.footer.descriptionTitle')"
              :description-connector="$t('user.footer.descriptionConnector')"
              :description-body="$t('user.footer.descriptionBody')"
            >
              <template slot="thead">
                <vs-th sort-key="name"> {{ $t("user.header.name") }} </vs-th>
                <vs-th sort-key="email"> {{ $t("user.header.email") }} </vs-th>
                <vs-th> {{ $t("user.header.action") }} </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].name">
                    {{ data[indextr].name }}
                  </vs-td>
                  <vs-td :data="data[indextr].email">
                    {{ data[indextr].email }}
                  </vs-td>
                  <vs-td class="badaso-table__td">
                    <badaso-dropdown vs-trigger-click>
                      <vs-button
                        size="large"
                        type="flat"
                        icon="more_vert"
                      ></vs-button>
                      <vs-dropdown-menu>
                        <badaso-dropdown-item
                          icon="visibility"
                          :to="{
                            name: 'UserManagementRead',
                            params: { id: data[indextr].id },
                          }"
                          v-if="$helper.isAllowed('read_users')"
                        >
                          Detail
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="list"
                          :to="{
                            name: 'UserManagementRoles',
                            params: { id: data[indextr].id },
                          }"
                          v-if="$helper.isAllowed('browse_user_role')"
                        >
                          Roles
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="edit"
                          :to="{
                            name: 'UserManagementEdit',
                            params: { id: data[indextr].id },
                          }"
                          v-if="$helper.isAllowed('edit_users')"
                        >
                          Edit
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="delete"
                          @click="confirmDelete(data[indextr].id)"
                          v-if="$helper.isAllowed('delete_users')"
                        >
                          Delete
                        </badaso-dropdown-item>
                      </vs-dropdown-menu>
                    </badaso-dropdown>
                  </vs-td>
                </vs-tr>
              </template>
            </badaso-table>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
import downloadExcel from "vue-json-excel";
import jsPDF from "jspdf";
import "jspdf-autotable";
export default {
  name: "UserManagementBrowse",
  components: { downloadExcel },
  data: () => ({
    selected: [],
    descriptionItems: [10, 50, 100],
    users: [],
    willDeleteId: null,
    fieldsForExcel: {},
    fieldsForPdf: [],
    dataType: {
      fields: ["name", "email"],
    },
  }),
  mounted() {
    this.getUserList();
  },
  methods: {
    confirmDelete(id) {
      this.willDeleteId = id;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.deleteUser,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
    confirmDeleteMultiple(id) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.bulkDeleteUser,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {},
      });
    },
    getUserList() {
      this.$openLoader();
      this.$api.badasoUser
        .browse()
        .then((response) => {
          this.$closeLoader();
          this.selected = [];
          this.users = response.data.users;
          this.prepareExcelExporter();
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
    deleteUser() {
      this.$openLoader();
      this.$api.badasoUser
        .delete({
          id: this.willDeleteId,
        })
        .then((response) => {
          this.$closeLoader();
          this.getUserList();
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
    bulkDeleteUser() {
      const ids = this.selected.map((item) => item.id);
      this.$openLoader();
      this.$api.badasoUser
        .deleteMultiple({
          ids: ids.join(","),
        })
        .then((response) => {
          this.$closeLoader();
          this.getUserList();
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
    prepareExcelExporter() {
      for (const iterator of this.dataType.fields) {
        let field = iterator;
        if (field.includes("_")) {
          field = field.split("_");
          field =
            field[0].charAt(0).toUpperCase() +
            field[0].slice(1) +
            " " +
            field[1].charAt(0).toUpperCase() +
            field[1].slice(1);
        }
        field = field.charAt(0).toUpperCase() + field.slice(1);

        this.fieldsForExcel[field] =
          this.$caseConvert.stringSnakeToCamel(iterator);
      }

      for (let iterator of this.dataType.fields) {
        if (iterator.includes("_")) {
          iterator = iterator.split("_");
          iterator =
            iterator[0] +
            " " +
            iterator[1].charAt(0).toUpperCase() +
            iterator[1].slice(1);
        }

        const string = this.$caseConvert.stringSnakeToCamel(iterator);
        this.fieldsForPdf.push(
          string.charAt(0).toUpperCase() + string.slice(1)
        );
      }
    },
    generatePdf() {
      let data = this.users;

      const fields = [];

      for (const iterator in this.dataType.fields) {
        const string = this.$caseConvert.stringSnakeToCamel(
          this.dataType.fields[iterator]
        );
        fields.push(string);
      }

      data.map((value) => {
        for (const iterator in value) {
          if (!fields.includes(iterator)) {
            delete value[iterator];
          }
        }
        return value;
      });

      const result = data.map(Object.values);

      // eslint-disable-next-line new-cap
      const doc = new jsPDF("l");

      // Dynamic table title
      doc.setFont("helvetica", "bold");
      doc.setFontSize(28);
      doc.text(this.$t("user.title"), 149, 20, "center");

      // Data table
      doc.autoTable({
        head: [this.fieldsForPdf],
        body: result,
        startY: 30,
        // Default for all columns
        styles: { valign: "middle" },
        headStyles: { fillColor: [6, 187, 211] },
        // Override the default above for the text column
        columnStyles: { text: { cellWidth: "wrap" } },
      });

      // Output Table title and data table in new tab
      const output = doc.output("blob");
      data = window.URL.createObjectURL(output);
      window.open(data, "_blank");

      setTimeout(function () {
        // For Firefox it is necessary to delay revoking the ObjectURL
        window.URL.revokeObjectURL(data);
      }, 100);
    },
  },
};
</script>
