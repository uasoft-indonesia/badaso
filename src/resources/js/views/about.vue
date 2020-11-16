<template>
  <div>
    <vs-row justify="space-between">
      <vs-col w="6">
        <ul class="breadcrumb">
          <li class="breadcrumb-item">test</li>
          <li class="breadcrumb-item">test1</li>
        </ul>
      </vs-col>
      <vs-col w="6">
        <vs-button relief class="ml-auto">
          <i class="bx bx-plus"></i>
        </vs-button>
      </vs-col>
    </vs-row>
    <vs-row w="12">
      <vs-col w="12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title d-flex">Data</h3>
            <p class="card-subtitle">Lorem ipsum</p>
          </div>
          <div class="card-body">
            <vs-table>
              <template #thead>
                <vs-tr>
                  <vs-th> Name </vs-th>
                  <vs-th> Email </vs-th>
                  <vs-th> Id </vs-th>
                  <vs-th> Action </vs-th>
                </vs-tr>
              </template>
              <template #tbody>
                <vs-tr :key="i" v-for="(tr, i) in users" :data="tr">
                  <vs-td>
                    {{ tr.name }}
                  </vs-td>
                  <vs-td>
                    {{ tr.email }}
                  </vs-td>
                  <vs-td>
                    {{ tr.id }}
                  </vs-td>
                  <td style="width: 1%; white-space: nowrap">
                    <vs-button style="display: inline" success relief>
                      <span class="material-icons">remove_red_eye</span>
                    </vs-button>
                    <vs-button style="display: inline" warn relief>
                      <i class="bx bx-pencil"></i>
                    </vs-button>
                    <vs-button style="display: inline" danger relief @click="active2=!active2">
                      <i class="bx bx-trash"></i>
                    </vs-button>
                    <vs-dialog width="550px" not-center v-model="active2">
                      <template #header>
                        <h4 class="not-margin">Welcome to <b>Vuesax</b></h4>
                      </template>

                      <div class="con-content">
                        <p>
                          Vuesax is a relatively new framework with a refreshing
                          design and in the latest trends, vuesax based on vuejs
                          which means that we go hand in hand with one of the
                          most popular javascript frameworks in the world and
                          with a huge community with which you will have all the
                          help and documentation to create and make your project
                        </p>
                      </div>

                      <template #footer>
                        <div class="con-footer" style="display: flex; justify-content: flex-end">
                          <vs-button style="display: inline" @click="active2 = false" transparent>
                            Ok
                          </vs-button>
                          <vs-button style="display: inline" @click="active2 = false" dark transparent>
                            Cancel
                          </vs-button>
                        </div>
                      </template>
                    </vs-dialog>
                  </td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </div>
      </vs-col>
    </vs-row>
  </div>
</template>

 <script>
export default {
  data: () => ({
    active2: false,
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
};
</script>