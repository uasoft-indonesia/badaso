<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <vs-breadcrumb
          style="margin-bottom: 0"
          :items="breadcrumb"
        ></vs-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4">
        <div style="float: right">
          <vs-button color="primary" type="relief" to="add"
            ><vs-icon icon="add"></vs-icon> Add</vs-button
          >
          <vs-button color="danger" type="relief"
            ><vs-icon icon="delete_sweep"></vs-icon> Bulk Delete</vs-button
          >
        </div>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <!-- <div slot="header">
            <h3>Browse</h3>
          </div> -->
          <div>
            <vs-table
              multiple
              v-model="selected"
              pagination
              max-items="3"
              search
              :data="users"
              stripe
            >
              <template slot="header">
                <h3>Users</h3>
              </template>
              <template slot="thead">
                <vs-th sort-key="email"> Email </vs-th>
                <vs-th sort-key="username"> Name </vs-th>
                <vs-th sort-key="website"> Website </vs-th>
                <vs-th sort-key="id"> Nro </vs-th>
                <vs-th> Action </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].email">
                    {{ data[indextr].email }}
                  </vs-td>

                  <vs-td :data="data[indextr].username">
                    {{ data[indextr].username }}
                  </vs-td>

                  <vs-td :data="data[indextr].id">
                    {{ data[indextr].website }}
                  </vs-td>

                  <vs-td :data="data[indextr].id">
                    {{ data[indextr].id }}
                  </vs-td>
                  <vs-td style="width: 1%; white-space: nowrap">
                    <vs-button color="success" type="relief" @click.stop
                      ><vs-icon icon="visibility"></vs-icon
                    ></vs-button>
                    <vs-button color="warning" type="relief"
                      @click.stop
                      ><vs-icon icon="edit"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="danger"
                      type="relief"
                      @click.stop
                      @click="openConfirm()"
                      ><vs-icon icon="delete"></vs-icon
                    ></vs-button>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
export default {
  name: "Browse",
  data: () => ({
    breadcrumb: [
      {
        title: "Dashboard",
        url: "dashboard",
      },
      {
        title: "Link 1",
        url: "link-1",
      },
      {
        title: "Link 2",
        disabled: true,
      },
      {
        title: "Active",
        active: true,
      },
    ],
    selected: [],
    products: [
      {
        products: "Products",
        license: "License",
        support: "Support",
        technology: "Technology",
        tickets: "Tickets",
        sales: "Sales",
        earnings: "Earnings",
      },
      {
        products: "Products",
        license: "License",
        support: "Support",
        technology: "Technology",
        tickets: "Tickets",
        sales: "Sales",
        earnings: "Earnings",
      },
      {
        products: "Products",
        license: "License",
        support: "Support",
        technology: "Technology",
        tickets: "Tickets",
        sales: "Sales",
        earnings: "Earnings",
      },
      {
        products: "Products",
        license: "License",
        support: "Support",
        technology: "Technology",
        tickets: "Tickets",
        sales: "Sales",
        earnings: "Earnings",
      },
    ],
    users: [
      {
        id: 1,
        name: "Leanne Graham",
        username: "Bret",
        email: "Sincere@april.biz",
        website: "hildegard.org",
      },
      {
        id: 2,
        name: "Ervin Howell",
        username: "Antonette",
        email: "Shanna@melissa.tv",
        website: "anastasia.net",
      },
      {
        id: 3,
        name: "Clementine Bauch",
        username: "Samantha",
        email: "Nathan@yesenia.net",
        website: "ramiro.info",
      },
      {
        id: 4,
        name: "Patricia Lebsack",
        username: "Karianne",
        email: "Julianne.OConner@kory.org",
        website: "kale.biz",
      },
      {
        id: 5,
        name: "Chelsey Dietrich",
        username: "Kamren",
        email: "Lucio_Hettinger@annie.ca",
        website: "demarco.info",
      },
      {
        id: 6,
        name: "Mrs. Dennis Schulist",
        username: "Leopoldo_Corkery",
        email: "Karley_Dach@jasper.info",
        website: "ola.org",
      },
      {
        id: 7,
        name: "Kurtis Weissnat",
        username: "Elwyn.Skiles",
        email: "Telly.Hoeger@billy.biz",
        website: "elvis.io",
      },
      {
        id: 8,
        name: "Nicholas Runolfsdottir V",
        username: "Maxime_Nienow",
        email: "Sherwood@rosamond.me",
        website: "jacynthe.com",
      },
      {
        id: 9,
        name: "Glenna Reichert",
        username: "Delphine",
        email: "Chaim_McDermott@dana.io",
        website: "conrad.com",
      },
      {
        id: 10,
        name: "Clementina DuBuque",
        username: "Moriah.Stanton",
        email: "Rey.Padberg@karina.biz",
        website: "ambrose.net",
      },
    ],
  }),
  methods: {
    openConfirm() {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirm`,
        text:
          "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        accept: this.acceptAlert,
      });
    },
    acceptAlert(color) {
      this.$vs.notify({
        color: "danger",
        title: "Deleted image",
        text: "The selected image was successfully deleted",
      });
    },
  },
};
</script>