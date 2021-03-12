<template>
    <vs-popup fullscreen title="Licence Issues" :active.sync="licenceIssue" style="z-index: 26000">
      <vs-row>
        <vs-col>
        <p>
        Mohon maaf, Badaso belum bisa digunakan dikarenakan ada issue pada lisensi anda
      </p>
      <p>
        Berikut adalah beberapa masalah yang bisa terjadi pada lisensi:
      </p>
      <ul>
        <li>
            <strong>Lisensi Kosong</strong>
            <p>
                Anda belum memasukkan BADASO_DONATION_KEY pada .env. Harap isi dahulu sebelum bisa menggunakan badaso.
                Untuk petunjuk lebih lengkap, bisa di lihat disini.
            </p>
        </li>
        <li>
            <strong>Lisensi Invalid</strong>
            <p>
                BADASO_DONATION_KEY tidak diemukan. Harap pastikan sudah sama dengan yang anda dapatkan pada Badaso Dashboard.
                Untuk petunjuk lebih lengkap, bisa di lihat disini.
            </p>
        </li>
        <li>
            <strong>Masa Aktif Kadaluarsa</strong>
            <p>
                Masa aktif anda sudah habis. Mohon untuk menambahkan masa aktif anda pada Badaso Dashboard supaya licence anda dapat digunakan kembali.
                Untuk petunjuk lebih lengkap, bisa di lihat disini.
            </p>
        </li>
      </ul>
        </vs-col>
      </vs-row>
    </vs-popup>
</template>

<script>
export default {
  name: "BadasoLicenceBlocker",
  components: {},
  data: () => ({}),
  mounted() {
    this.$store.commit("SET_LICENCE_ISSUE", false);
  },
  computed: {
    licenceIssue: {
      get() {
        return this.$store.state.licenceIssue;
      }
    }
  },
};
</script>