export const label = "Indonesia";

export default {
  button: {
    close: "Tutup",
    submit: "Pilih",
  },
  vuelidate: {
    required: "* {0} harus diisi.",
    requiredIf: "* {0} harus diisi.",
    integer: "* {0} harus integer.",
    rowsRequired: "* Mohon field diisi terlebih dahulu.",
    maxLength: "* {field} harus memiliki paling banyak {length} huruf.",
    alphaNum: "* {0} bukan alfanumerik.",
    alphaNumAndUnderscoreValidator:
      "* {0} hanya alfanumerik dan underscore (_) diperbolehkan.",
    unique: "* {0} harus unik.",
    distinct: "* Hanya satu {0} dibolehkan.",
    requiredPrimary: "* Hanya {0} dibolehkan.",
  },
  login: {
    title: "Masuk",
    subtitle: "Selamat datang kembali, silakan masuk ke akun Anda.",
    field: {
      email: "Email",
      password: "Kata sandi",
    },
    rememberMe: "Ingat saya?",
    forgotPassword: "Lupa kata sandi",
    button: "Masuk",
    createAccount: {
      text: "Belum punya akun?",
      link: "Buat akun",
    },
  },

  register: {
    title: "Daftar",
    subtitle: "Silakan isi formulir di bawah ini.",
    field: {
      name: "Nama",
      username: "Username",
      phone: "No Telp/Hp",
      address: "Alamat",
      gender: "Jenis Kelamin",
      email: "Email",
      password: "Kata sandi",
      passwordConfirmation: "Konfirmasi kata sandi",
    },
    button: "Daftar",
    existingAccount: {
      text: "Sudah mempunyai akun?",
      link: "Masuk",
    },
    gender: {
      man: "Laki-laki",
      women: "Perempuan",
    },
  },

  forgotPassword: {
    title: "Lupa Password",
    subtitle: "Harap berikan email untuk mengirim surat atur ulang kata sandi.",
    field: {
      email: "Email",
    },
    button: "Kirim",
    createAccount: {
      text: "Belum punya akun?",
      link: "Buat akun",
    },
    message: {
      success:
        "Email telah dikirim ke alamat email yang Anda berikan. Silakan ikuti tautan di email untuk menyelesaikan permintaan pengaturan ulang kata sandi Anda.",
      error:
        "Terjadi kesalahan. Silakan periksa kembali email yang Anda berikan. Jika masalah terus berlanjut, harap hubungi kami untuk bantuan lebih lanjut.",
    },
  },

  resetPassword: {
    title: "Setel Ulang Kata Sandi",
    subtitle: "Harap berikan kata sandi baru.",
    field: {
      password: "Kata sandi baru",
      passwordConfirmation: "Konfirmasi kata sandi baru",
    },
    button: "Setel Ulang Kata Sandi",
    createAccount: {
      text: "Belum punya akun?",
      link: "Buat akun",
    },
    message: {
      success:
        "Atur ulang kata sandi berhasil. Anda dapat login dengan menggunakan kata sandi baru. Anda akan dialihkan ke halaman login.",
      error:
        "Terjadi kesalahan. Silakan periksa kembali kata sandi dan konfirmasi kata sandi yang Anda berikan. Jika masalah terus berlanjut, harap hubungi kami untuk bantuan lebih lanjut.",
    },
  },

  verifyEmail: {
    title: "Verifikasi Email",
    failed: "Verifikasi Email sedang diproses ....",
    button: "Verifikasi",
    request: "Kirim ulang",
    field: {
      token: "Token",
    },
    message: {
      inProgress: "Verifikasi Email sedang diproses  ....",
      success:
        "Email telah dikirim ke alamat email yang Anda berikan. Silakan ikuti tautan di email untuk menyelesaikan permintaan pengaturan ulang kata sandi Anda.",
      error: "Verifikasi Email gagal.",
    },
  },

  sidebar: {
    dashboard: "Beranda",
    mainMenu: "Menu Utama",
    configurationMenu: "Menu Konfigurasi",
  },

  myProfile: {
    title: "My Profile",
    username: "Username",
    logout: "Logout",
    profile: "Profile",
    email: "Email",
    password: "Password",
    oldPassword: "Current Password",
    newPassword: "New Password",
    newPasswordConfirmation: "New Password Confirmation",
    name: "Name",
    avatar: "Avatar",
    phone: "No Telp/Hp",
    address: "Alamat",
    gender: "Jenis Kelamin",
    additionalInfo: "Additional info(optional)",
    token: "Verification Code",
    buttons: {
      updateProfile: "Update Profile",
      updateEmail: "Update Email",
      verifyEmail: "Verify Email",
      changePassword: "Change Password",
    },
  },

  404: {
    title: "Opps, Maaf",
    subtitle: "Halaman yang Anda cari tidak ditemukan.",
    button: "Kembali ke Home",
  },

  action: {
    bulkDelete: "Hapus Massal",
    bulkRestore: "Pengembalian Massal",
    showTrash: "Tampilkan Tempat Sampah",
    add: "Tambah",
    edit: "Ubah",
    delete: {
      title: "Konfirmasi",
      text: "Apakah Anda yakin ingin menghapus?",
      accept: "Ya",
      cancel: "Tidak",
    },
    restore: {
      title: "Konfirmasi",
      text: "Apakah Anda yakin ingin mengembalikan ini?",
      accept: "Ya",
      cancel: "Tidak",
    },
    addItem: "Tambahkan Item",
    roles: "Wewenang",
    sort: "Menyortir",
    rollbackMigration: {
      title: "Rollback Migration",
      text: "Apakah Anda yakin?",
      accept: "Ya",
      cancel: "Tidak",
    },
    exportToExcel: "Export .xls",
    exportToPdf: "Export .pdf",
  },

  alert: {
    success: "Sukses",
    danger: "Bahaya",
    error: "Kesalahan",
  },

  activityLog: {
    title: "Log Aktivitas",
    warning: {
      notAllowed: "Anda tidak diizinkan untuk mengakses Log Aktivitas.",
    },
    header: {
      logName: "Nama Log",
      causerType: "Jenis Penyebab",
      causerId: "Id Penyebab",
      causerName: "Nama Penyebab",
      subjectType: "Jenis Subjek",
      subjectId: "Id Subjek",
      description: "Deskripsi",
      dateLogged: "Tanggal Dicatat",
      action: "Aksi",
    },
    footer: {
      descriptionTitle: "Baris yang ditampilkan",
      descriptionConnector: "dari",
      descriptionBody: "Halaman",
    },
    detail: {
      title: "Detail Log Aktivitas",
      causer: {
        title: "Detail Penyebab",
      },
      subject: {
        title: "Detail Subjek",
      },
      properties: {
        title: "Properti",
      },
    },
  },

  site: {
    action: "Aksi",
    deletedImage: {
      title: "Gambar yang telah dihapus",
      text: "Gambar yang dipilih telah dihapus.",
    },
    configUpdated: "Konfigurasi telah Diperbarui",
    add: {
      title: "Tambah Konfigurasi Situs",
      field: {
        displayName: {
          title: "Nama Tampilan",
          placeholder: "Contoh: Nama Tampilan",
        },
        key: {
          title: "Kunci",
          placeholder: "Contoh: contoh_kunci",
        },
        type: {
          title: "Tipe",
          placeholder: "Tipe",
        },
        group: {
          title: "Grup",
          placeholder: "Grup",
        },
        options: {
          title: "Opsi",
          description:
            "Opsi diperlukan untuk Checkbox, Radio, Select, Select-multiple. Contoh: ",
          example: `{"items": [{"label":"Ini label","value":"ini_nilai"}] }`,
        },
      },
      button: "Save",
    },
    edit: {
      multiple: "Simpan Konfigurasi",
    },
    maintenanceMode: "Pengaturan maintenance hanya readonly.",
  },

  crud: {
    title: "CRUD",
    data: {
      switchDataRecycle: "Tampilkan Data Recycle",
      switchDataNormal: "Tampilkan Data Normal",
    },
    help: {
      generatePermissions:
        "Akan menghasilkan permission untuk CRUD yang dibuat. Permission yang dihasilkan adalah: browse_{table_name}, read_{table_name}, edit_{table_name}, add_{table_name}, delete_{table_name} dan maintenance_{table_name}.",
      serverSide:
        "Atur pagination saat browse di server side atau client side. Jika Anda memiliki data kecil, matikan saja ke Off, atau sebaliknya.",
      createSoftDelete:
        "Ubah ini menjadi On jika Anda menginginkan fitur seperti recycle bin. Anda dapat memulihkan data yang dihapus. Akan membuat soft delete jika tabel didukung.",
      activeEventNotificationTitle:
        "Akan menampilkan notifikasi di sidebar kanan jika action event di bawah ini diatur. Harap konfigurasikan push notification firebase sebelum menggunakan fitur ini.",
      modelName:
        "Isi input ini jika Anda ingin overriding Model CRUD. Sebagai contoh: App\\Models\\User.",
      controllerName:
        "Isi input ini jika Anda ingin overriding Model CRUD. Sebagai contoh: App\\Http\\Controller\\HomeController. Anda dapat mengganti salah satu metode berikut: browse, all, read, edit, add, delete, restore, deleteMultiple, restoreMultiple, sort atau setMaintenanceState.",
    },
    warning: {
      notAllowed: "Anda tidak diizinkan untuk mengakses CRUD.",
      idNotAllowed: "Jangan ganti nama 'id' table dengan apapun",
    },
    header: {
      table: "Tabel",
      action: "Aksi",
    },
    body: {
      button: "Tambahkan CRUD ke tabel ini",
    },
    footer: {
      descriptionTitle: "Baris yang ditampilkan",
      descriptionConnector: "dari",
      descriptionBody: "Halaman",
    },
    add: {
      title: {
        table: "Tambahkan CRUD untuk {tableName}",
        field: "Tambahkan bidang CRUD untuk {tableName}",
      },
      field: {
        tableName: {
          title: "Nama Tabel",
          placeholder: "Nama Tabel",
        },
        generatePermissions: "Hasilkan Izin",
        createSoftDelete: "Buat Penghapusan Ringan",
        createSoftDeleteNote:
          "Catatan: jika anda membuat penghapusan lunak, secara otomatis kami membuat model dan migrasi untuk penghapusan lunak ",
        serverSide: "Sisi Server",
        displayNameSingular: {
          title: "Nama Tampilan(Tunggal)",
          placeholder: "Nama Tampilan(Tunggal)",
        },
        displayNamePlural: {
          title: "Nama Tampilan (Jamak)",
          placeholder: "Nama Tampilan (Jamak)",
        },
        urlSlug: {
          title: "URL Slug (harus unik)",
          placeholder: "URL Slug (harus unik)",
        },
        icon: {
          title: "Ikon",
          placeholder: "Ikon",
        },
        modelName: {
          title: "Nama Model",
          placeholder: "Nama Model",
        },
        controllerName: {
          title: "Nama Controller",
          placeholder: "Nama Controller",
        },
        orderColumn: {
          title: "Kolom Urutan",
          placeholder: "Kolom Urutan",
        },
        orderDisplayColumn: {
          title: "Kolom Urutan Tampilan",
          placeholder: "Kolom Urutan Tampilan",
          description:
            "<p class='text-muted'>Kolom Urutan akan diisi dengan angka untuk mengurutkan data jika field ini sudah diset</p>",
        },
        orderDirection: {
          title: "Arah Urutan",
          value: {
            ascending: "Naik",
            descending: "Turun",
          },
          placeholder: "Arah Urutan",
        },
        defaultServerSideSearchField: {
          title: "Bidang Pencarian Sisi Server Default",
          placeholder: "Bidang Pencarian Sisi Server Default",
        },
        description: {
          title: "Deskripsi",
          placeholder: "Deskripsi",
        },
      },
      header: {
        field: "Bidang",
        visibility: "Visibilitas",
        inputType: "Tipe Masukan",
        displayName: "Nama Tampilan",
        optionalDetails: "Detail Opsional",
      },
      body: {
        type: "Tipe:",
        required: {
          title: "Wajib:",
          yes: "Ya",
          no: "Tidak",
        },
        browse: "Jelajahi",
        read: "Baca",
        edit: "Ubah",
        add: "Tambah",
        delete: "Hapus",
        displayName: "Nama Tampilan",
        setOtherRelation: "Set Relasi Lainnya",
        setRelation: "Set Relasi",
        relationType: "Tipe Relasi",
        destinationTable: "Tabel Tujuan",
        destinationTableColumn: "Kolom Tujuan",
        destinationTableDisplayColumn: "Kolom Tujuan Untuk Ditampilkan",
        saveRelation: "Simpan",
        cancelRelation: "Batal",
      },
      button: "Simpan",
    },
    edit: {
      title: {
        table: "Ubah CRUD untuk {tableName}",
        field: "Ubah bidang CRUD untuk {tableName}",
      },
      field: {
        tableName: {
          title: "Nama Tabel",
          placeholder: "Nama Tabel",
        },
        generatePermissions: "Hasilkan Izin",
        serverSide: "Sisi Server",
        displayNameSingular: {
          title: "Nama Tampilan(Tunggal)",
          placeholder: "Nama Tampilan(Tunggal)",
        },
        displayNamePlural: {
          title: "Nama Tampilan (Jamak)",
          placeholder: "Nama Tampilan (Jamak)",
        },
        urlSlug: {
          title: "URL Slug (harus unik)(read-only)",
          placeholder: "URL Slug (harus unik)(read-only)",
        },
        icon: {
          title: "Ikon",
          placeholder: "Ikon",
        },
        modelName: {
          title: "Nama Model",
          placeholder: "Nama Model",
        },
        controllerName: {
          title: "Nama Controller",
          placeholder: "Nama Controller",
        },
        orderColumn: {
          title: "Kolom Urutan",
          placeholder: "Kolom Urutan",
        },
        orderDisplayColumn: {
          title: "Kolom Urutan Tampilan",
          placeholder: "Kolom Urutan Tampilan",
          description:
            "<p class='text-muted'>Kolom Urutan akan diisi dengan angka untuk mengurutkan data jika field ini sudah diset</p>",
        },
        orderDirection: {
          title: "Arah Urutan",
          value: {
            ascending: "Naik",
            descending: "Turun",
          },
          placeholder: "Arah Urutan",
        },
        defaultServerSideSearchField: {
          title: "Bidang Pencarian Sisi Server Default",
          placeholder: "Bidang Pencarian Sisi Server Default",
        },
        activeEventNotification: {
          title: "Pemberitahuan Peristiwa Aktif",
          label: {
            onCreate: "Saat Buat",
            onRead: "Saat Baca",
            onUpdate: "Saat Update",
            onDelete: "Saat Delete",
            onCreateTitle: "Judul Pesan Saat Menambahkan Data",
            onCreateMessage: "Pesan Saan Menambahkan Data",
            onReadTitle: "Judul Pesan Saat Membaca Data",
            onReadMessage: "Pesan Saan Membaca Data",
            onUpdateTitle: "Judul Pesan Saat Mengupdate Data",
            onUpdateMessage: "Pesan Saan Mengupdate Data",
            onDeleteTitle: "Judul Pesan Saat Hapus Data",
            onDeleteMessage: "Pesan Saan Hapus Data",
          },
        },
        description: {
          title: "Deskripsi",
          placeholder: "Deskripsi",
        },
      },
      header: {
        field: "Bidang",
        visibility: "Visibilitas",
        inputType: "Tipe Masukan",
        displayName: "Nama Tampilan",
        optionalDetails: "Detail Opsional",
      },
      body: {
        type: "Tipe:",
        required: {
          title: "Wajib:",
          yes: "Ya",
          no: "Tidak",
        },
        browse: "Jelajahi",
        read: "Baca",
        edit: "Ubah",
        add: "Tambah",
        delete: "Hapus",
        displayName: "Nama Tampilan",
        setRelation: "Set Relasi",
        setOtherRelation: "Set Relasi Lainnya",
        relationType: "Tipe Relasi",
        destinationTable: "Tabel Tujuan",
        destinationTableColumn: "Kolom Tujuan",
        destinationTableDisplayColumn: "Kolom Tujuan Untuk Ditampilkan",
        saveRelation: "Simpan",
        cancelRelation: "Batal",
      },
      button: "Simpan",
    },
  },

  menu: {
    title: "Menu",
    options: {
      showHeader: "Tampilkan Menu Header",
      expand: "Expand",
    },
    warning: {
      notAllowedToBrowse: "Anda tidak diizinkan untuk mengakses Menu",
      notAllowedToAdd: "Anda tidak diizinkan untuk menambahkan Menu",
      notAllowedToEdit: "Anda tidak diizinkan untuk mengubah Menu",
    },
    help: {
      key: "Anda dapat mengatur key ini menjadi menu default di file .env. Juga, Anda dapat mendaftarkan menu baru di .env dengan nilai input.",
    },
    header: {
      key: "Kunci",
      displayName: "Nama Tampilan",
      action: "Aksi",
    },
    footer: {
      descriptionTitle: "Baris yang ditampilkan",
      descriptionConnector: "dari",
      descriptionBody: "Halaman",
    },
    add: {
      title: "Tambah Menu",
      field: {
        key: {
          title: "Kunci",
          placeholder: "kunci_menu",
        },
        displayName: {
          title: "Nama Tampilan",
          placeholder: "Nama Tampilan",
        },
        icon: {
          title: "Icon",
          placeholder: "Icon",
        },
      },
      button: "Simpan",
    },
    edit: {
      title: "Ubah Menu",
      field: {
        key: {
          title: "Kunci",
          placeholder: "kunci_menu",
        },
        displayName: {
          title: "Nama Tampilan",
          placeholder: "Nama Tampilan",
        },
      },
      button: "Simpan",
    },
    builder: {
      title: "Item Menu",
      popup: {
        add: {
          title: "Tambah Item Menu",
          field: {
            title: "Judul",
            url: "Url",
            target: {
              title: "Target",
              value: {
                thisTab: "Tab Ini",
                newTab: "Tab Baru",
              },
            },
            icon: {
              title: "Ikon",
              description:
                "Gunakan&nbsp;<a href='https://material.io/resources/icons/?style=baseline' target='_blank'>ikon material design</a>",
            },
          },
          button: {
            add: "Tambah",
            cancel: "Batal",
          },
        },
        edit: {
          title: "Ubah Item Menu",
          field: {
            title: "Judul",
            url: "Url",
            target: {
              title: "Target",
              value: {
                thisTab: "Tab Ini",
                newTab: "Tab Baru",
              },
            },
            icon: {
              title: "Ikon",
              description:
                "Gunakan&nbsp;<a href='https://material.io/resources/icons/?style=baseline' target='_blank'>ikon material design</a>",
            },
          },
          button: {
            edit: "Ubah",
            cancel: "Batal",
          },
        },
      },
    },
    permission: {
      title: "Izin",
      header: {
        key: "Kunci",
        description: "Deskripsi",
      },
      button: "Tetapkan izin yang dipilih untuk wewenang",
      success: {
        title: "Sukses",
        text: "Izin telah ditetapkan",
      },
    },
  },

  user: {
    title: "Pengguna",
    header: {
      name: "Nama",
      email: "Email",
      action: "Aksi",
    },
    footer: {
      descriptionTitle: "Baris yang ditampilkan",
      descriptionConnector: "dari",
      descriptionBody: "Halaman",
    },
    add: {
      title: "Tambah Pengguna",
      field: {
        name: {
          title: "Nama",
          placeholder: "Nama",
        },
        email: {
          title: "Email",
          placeholder: "Email",
        },
        username: {
          title: "Username",
          placeholder: "Username",
        },
        phone: {
          title: "No Telp/Hp",
          placeholder: "No Telp/Hp",
        },
        address: {
          title: "Alamat",
          placeholder: "Alamat",
        },
        gender: {
          title: "Jenis Kelamin",
          placeholder: "Jenis Kelamin",
        },
        password: {
          title: "Kata sandi",
          placeholder: "Kata sandi",
        },
        emailVerified: {
          title: "Verifikasi",
          placeholder: "Verifikasi",
        },
        avatar: {
          title: "Avatar",
          placeholder: "Avatar",
        },
        additionalInfo: {
          title: "Info Tambahan (JSON)",
          placeholder: "Info Tambahan (JSON)",
          invalid: "Info Tambahan tidak valid",
        },
      },
      button: "Simpan",
    },
    edit: {
      title: "Ubah Pengguna",
      field: {
        name: {
          title: "Nama",
          placeholder: "Nama",
        },
        username: {
          title: "Username",
          placeholder: "Username",
        },
        phone: {
          title: "No Telp/Hp",
          placeholder: "No Telp/Hp",
        },
        address: {
          title: "Alamat",
          placeholder: "Alamat",
        },
        gender: {
          title: "Jenis Kelamin",
          placeholder: "Jenis Kelamin",
        },
        email: {
          title: "Email",
          placeholder: "Email",
        },
        password: {
          title: "Kata sandi",
          placeholder: "Biarkan kosong jika tidak diubah",
        },
        emailVerified: {
          title: "Verifikasi",
          placeholder: "Verifikasi",
        },
        avatar: {
          title: "Avatar",
          placeholder: "Avatar Baru",
        },
        additionalInfo: {
          title: "Info Tambahan (JSON)",
          placeholder: "Info Tambahan (JSON)",
          invalid: "Info Tambahan tidak valid",
        },
      },
      button: "Simpan",
    },
    detail: {
      title: "Detail Pengguna",
      avatar: "Avatar",
      name: "Nama",
      username: "Username",
      phone: "No Telp/Hp",
      address: "Alamat",
      email: "Email",
      gender: "Jenis Kelamin",
      additionalInfo: "Info Tambahan",
      emailVerified: "Email Diverifikasi",
    },
    roles: {
      title: "Wewenang",
      header: {
        name: "Nama",
        description: "Deskripsi",
        action: "Aksi",
      },
      button: "Tetapkan wewenang yang dipilih untuk pengguna",
      success: {
        title: "Success",
        text: "Wewenang telah ditetapkan",
      },
    },
    gender: {
      man: "Laki-laki",
      women: "Perempuan",
    },
  },

  role: {
    title: "Wewenang",
    warning: {
      notAllowedToBrowse: "Anda tidak diizinkan untuk mengakses Wewenang",
      notAllowedToAdd: "Anda tidak diizinkan untuk menambah Wewenang",
      notAllowedToEdit: "Anda tidak diizinkan untuk mengubah Wewenang",
      notAllowedToBrowsePermission:
        "Anda tidak diizinkan untuk mengakses Izin Wewenang",
    },
    header: {
      name: "Nama",
      displayName: "Nama Tampilan",
      description: "Deskripsi",
      action: "Aksi",
    },
    footer: {
      descriptionTitle: "Baris yang ditampilkan",
      descriptionConnector: "dari",
      descriptionBody: "Halaman",
    },
    add: {
      title: "Tambah Wewenang",
      field: {
        name: {
          title: "Nama",
          placeholder: "Nama",
        },
        displayName: {
          title: "Nama Tampilan",
          placeholder: "Nama Tampilan",
        },
        description: {
          title: "Deskripsi",
          placeholder: "Deskripsi",
        },
      },
      button: "Simpan",
    },
    edit: {
      title: "Ubah Wewenang",
      field: {
        name: {
          title: "Nama",
          placeholder: "Nama",
        },
        displayName: {
          title: "Nama Tampilan",
          placeholder: "Nama Tampilan",
        },
        description: {
          title: "Deskripsi",
          placeholder: "Deskripsi",
        },
      },
      button: "Simpan",
    },
    detail: {
      title: "Detail Wewenang",
      name: "Nama",
      displayName: "Nama Tampilan",
      description: "Deskripsi",
      button: {
        edit: "Ubah",
        permission: "Izin",
      },
    },
    permission: {
      title: "Izin",
      header: {
        key: "Kunci",
        description: "Deskripsi",
      },
      button: "Tetapkan izin yang dipilih untuk wewenang",
      success: {
        title: "Sukses",
        text: "Izin telah ditetapkan",
      },
    },
  },

  permission: {
    title: "Izin",
    warning: {
      notAllowedToBrowse: "Anda tidak diizinkan untuk mengakses Izin",
      notAllowedToAdd: "Anda tidak diizinkan untuk menambahkan Izin",
      notAllowedToEdit: "Anda tidak diizinkan untuk mengubah Izin",
      notAllowedToRead: "Anda tidak diizinkan untuk mengakses Izin",
    },
    header: {
      key: "Kunci",
      description: "Deskripsi",
      tableName: "Nama Tabel",
      alwaysAllow: "Selalu diizinkan",
      isPublic: "Publik",
      action: "Aksi",
      rolesCanSeeAllData: "Role yang dapat melihat semua data",
      fieldIdentifyRelatedUser: "Kolom relasi untuk identifikasi data user",
    },
    help: {
      alwaysAllow:
        "Setelah permission dibuat, itu akan ditetapkan ke setiap role yang dibuat setelah permission",
      isPublic: "Permission akan tersedia untuk umum",
    },
    footer: {
      descriptionTitle: "Baris yang ditampilkan",
      descriptionConnector: "dari",
      descriptionBody: "Halaman",
    },
    add: {
      title: "Tambah Izin",
      field: {
        key: {
          title: "Kunci",
          placeholder: "Kunci",
        },
        alwaysAllow: "Selalu diizinkan",
        isPublic: "Publik",
        description: {
          title: "Deskripsi",
          placeholder: "Deskripsi",
        },
        tableName: {
          title: "Nama Tabel",
          placeholder: "Nama Tabel",
        },
      },
      button: "Simpan",
    },
    edit: {
      title: "Ubah Izin",
      field: {
        key: {
          title: "Kunci",
          placeholder: "Kunci",
        },
        alwaysAllow: "Selalu diizinkan",
        isPublic: "Public",
        description: {
          title: "Deskripsi",
          placeholder: "Deskripsi",
        },
        tableName: {
          title: "Nama Tabel",
          placeholder: "Nama Tabel",
        },
        rolesCanSeeAllData: {
          title: "Role dapat melihat semua data",
          placeholder: "Role dapat melihat semua data",
        },
        fieldIdentifyRelatedUser: {
          title: "Kolom relasi untuk identifikasi data user",
          placeholder: "Kolom relasi untuk identifikasi data user",
        },
      },
      button: "Simpan",
    },
    detail: {
      title: "Detail Izin",
      key: "Kunci",
      description: "Deskripsi",
      tableName: "Nama Tabel",
      alwaysAllow: {
        title: "Selalu diizinkan",
        yes: "Ya",
        no: "Tidak",
      },
      isPublic: {
        title: "Publik",
        yes: "Ya",
        no: "Tidak",
      },
      button: "Ubah",
      rolesCanSeeAllData: "Role yang dapat melihat semua data",
      fieldIdentifyRelatedUser: "Kolom relasi untuk identifikasi data user",
    },
  },

  crudGenerated: {
    warning: {
      notAllowedToBrowse: "Anda tidak diizinkan untuk mengakses {tableName}",
      notAllowedToAdd: "Anda tidak diizinkan untuk menambahkan {tableName}",
      notAllowedToEdit: "Anda tidak diizinkan untuk mengubah {tableName}",
      notAllowedToRead: "Anda tidak diizinkan untuk melihat {tableName}",
    },
    header: {
      action: "Aksi",
    },
    footer: {
      descriptionTitle: "Baris yang ditampilkan",
      descriptionConnector: "dari",
      descriptionBody: "Halaman",
    },
    add: {
      title: "Tambah {tableName}",
      button: "Simpan",
    },
    edit: {
      title: "Ubah {tableName}",
      button: "Perbarui",
    },
    detail: {
      title: "Detail {tableName}",
      button: "Ubah",
    },
    sort: {
      title: "Menyortir {tableName}",
    },
    maintenanceDialog: {
      title: "Pengaturan",
      switch: "Mode Maintenance",
      button: "Simpan",
    },
  },
  keyIssue: {
    title: "License Issues",
    message:
      "Mohon maaf, Badaso belum bisa digunakan dikarenakan ada issue pada lisensi anda",
    listTitle:
      "Berikut adalah beberapa masalah yang bisa terjadi pada lisensi:",
    licenseEmpty: "Lisensi Kosong",
    licenseEmptyDescription:
      "Anda belum memasukkan BADASO_LICENSE_KEY pada .env. Harap isi dahulu sebelum bisa menggunakan badaso. Untuk petunjuk lebih lengkap, bisa di lihat disini.",
    licenseInvalid: "Lisensi Invalid",
    licenseInvalidDescription:
      "BADASO_LICENSE_KEY tidak diemukan. Harap pastikan sudah sama dengan yang anda dapatkan pada Badaso Dashboard. Untuk petunjuk lebih lengkap, bisa di lihat disini.",
    licenseUsersExpired: "Masa Aktif Kadaluarsa",
    licenseUsersExpiredDescription:
      "Masa aktif anda sudah habis. Mohon untuk menambahkan masa aktif anda pada Badaso Dashboard supaya license anda dapat digunakan kembali. Untuk petunjuk lebih lengkap, bisa di lihat disini.",
  },
  authorizationIssue: {
    title: "Sesi telah kadaluarsa",
    subtitle: "Maaf, belum bisa melanjutkan aktivitas karena",
    message: "Otorisasi gagal, token telah kadaluarsa atau kosong",
  },
  database: {
    browse: {
      title: "Basis Data",
      addButton: "Tambah Tabel",
      alterButton: "Ubah Tabel",
      rollbackButton: "Rollback Migration",
      dropButton: "Hapus Tabel",
      goBackButton: "Kembali",
      deleteMigrationButton: "Hapus File Migration",
      migrateButton: "Migrate",
      warning: {
        title: "Migration Tidak Sinkron",
        notAllowed:
          "Sebelum Anda dapat menggunakan Database Management, Anda harus memigrasi file yang belum dimigrasi atau Anda dapat menghapus file migrasi. Berikut ini daftar dari file migrasi yang belum dimigrasi:",
        empty:
          "Kamu harus menghapus generated CRUD ini terlebih dahulu di CRUD Management.",
      },
      fieldNotSupport: {
        title: "Kesalahan Basis Data",
        text: "Terdapat tipe data yang tidak didukung pada tabel, silahkan lihat tipe data yang didukung di dokumentasi Badaso",
        tableList: "Daftar Tabel Bermasalah :",
        button: {
          tableList: "Table list yang tidak mendukung :",
          visitDocs: "Kunjungi Dokumentasi",
        },
      },
    },
    add: {
      title: "Tambah Tabel",
      field: {
        table: "Nama Tabel",
      },
      row: {
        title: "Tambah Field Tabel",
        subtitle: "Mohon baca artikel {0} sebelum kamu membuat migrasi.",
        field: {
          timestamp: "Timestamp",
          tableName: "Nama Table",
          fieldName: "Nama Field",
          fieldType: "Tipe Field",
          fieldLength: "Panjang Field/Nilai Field",
          fieldDefault: "Default",
          asDefined: "Nilau Default",
          fieldNull: "Boleh Kosong",
          fieldIndex: "Index",
          fieldAttribute: "Unsigned",
          fieldIncrement: "Auto Increment",
          add: "Add",
        },
        drop: "Yakin ingin menghapus field ini?",
      },
      error: {
        fieldName: "Field name dibutuhkan.",
        fieldType: "Field type dibutuhkan.",
        tableName: "Table name dibutuhkan.",
        fieldLength: "Field length dibutuhkan.",
      },
      footer: {
        descriptionTitle: "Baris yang ditampilkan",
        descriptionConnector: "dari",
        descriptionBody: "Halaman",
      },
      button: "Simpan",
    },
    edit: {
      title: "Alter Tabel",
      field: {
        table: "Nama Tabel",
      },
      row: {
        title: "Alter Field Tabel",
        field: {
          timestamp: "Timestamp",
          tableName: "Nama Table",
          fieldName: "Nama Field",
          fieldType: "Tipe Field",
          fieldLength: "Panjang Field",
          fieldDefault: "Default",
          asDefined: "Nilau Default",
          fieldNull: "Boleh Kosong",
          fieldIndex: "Index",
          fieldAttribute: "Unsigned",
          fieldIncrement: "Auto Increment",
          action: "Aksi",
          add: "Add",
        },
        drop: "Apakah Anda yakin ingin menghapus field ini?",
      },
      warning: {
        title: "PENTING",
        content:
          'Hanya jenis kolom berikut yang dapat "diubah": Big Integer, BLOB, Boolean, Date, Datetime, Decimal, Float, Integer, JSON, Long Text, Medium Text, Set, Small Integer, Varchar, Text dan Time.',
        crud: "Pastikan tabel belum dibuat dengan CRUD Management jika ingin mengedit atau menghapus tabel.",
        fieldAttUnsigned:
          "Batasan kunci asing salah dibentuk. {0} untuk mengunjungi dokumentasi",
        visitDocs: "Klik Disini",
      },
      error: {
        fieldName: "Field name dibutuhkan.",
        fieldType: "Field type dibutuhkan.",
        tableName: "Table name dibutuhkan.",
        fieldLength: "Field length dibutuhkan.",
      },
      footer: {
        descriptionTitle: "Baris yang ditampilkan",
        descriptionConnector: "dari",
        descriptionBody: "Halaman",
      },
      button: "Simpan",
    },
    rollback: {
      title: "Rollback",
      label: "Masukan step rollback",
      checkbox: "Hapus File Migrasi?",
      invalid: "Silakan pilih migrasi yang ingin di-rollback.",
    },
    warning: {
      docs: "Mohon baca artikel {0} sebelum kamu membuat migrasi.",
      exists: "Field {0} sudah ada.",
      invalid:
        "Request tidak valid. Mohon periksa kembali field atau nama tabel jika valid atau tidak.",
    },
    migration: {
      header: {
        migration: "Nama Migrasi",
      },
      button: {
        rollback: "Rollback Migration",
      },
    },
  },
  fileManager: {
    title: "Pengolahan File",
    warning: {
      notAllowedToBrowse: "Anda tidak diizinkan untuk mengakses file manager.",
    },
    URL: {
      label: "Tempel alamat gambar disini",
      placeholder: "URL",
      descriptionText:
        "Jika URL benar, akan melihat pratinjau gambar di sini. Gambar besar mungkin membutuhkan waktu beberapa menit untuk muncul. Hanya menerima PNG dan JPEG.",
      invalid: "Gambar tidak valid",
    },
  },
  imageManager: {
    title: "Pengolahan Image",
    warning: {
      notAllowedToBrowse:
        "Anda tidak diizinkan untuk mengakses gambar manager.",
    },
  },
  firebase: {
    title: "Firebase",
    feature: "Fitur",
    features: {
      firebaseCloudMessage: "Firebase Pesan Awan",
    },
    form: {
      apiKey: "Kunci API",
      authDomain: "Auth Domain",
      projectId: "Id Proyek",
      storageBucket: "Penyimpanan Bucket",
      messagingSenderId: "Id Pengirim Pesan",
      appId: "Id App",
      measureId: "Id Measurement",
      serverKey: "Kunci Server",
    },
  },
  logViewer: {
    title: "Log Viewer",
    warning: {
      notAllowedToBrowse: "You're not allowed to browse log viewer",
    },
  },
  apidocs: {
    title: "API Dokumentasi",
    warning: {
      notAllowedToBrowse:
        "Anda tidak diizinkan untuk menjelajahi api dokumentasi.",
    },
  },
  notification: {
    notification: "Pemberitahuan",
    detailMessage: "Detail Pesan",
  },
  noInternetAccess:
    "Data tidak dapat dimuat karena internet Anda tidak terhubung. Tolong sambungkan internet lagi!",
  offlineFeature: {
    dataPending: "Data Tertunda...",
    dataUpdatePending: "Data Update Tertunda...",
    dataPendingAdd: {
      title: "Data Tertunda",
    },
    dataPendingEdit: {
      title: "Menampilkan Data Tertunda",
    },
    crudGenerator: {
      deleteDataPending: "Delete Data Tertunda",
    },
  },
  softDelete: {
    crudGenerator: {
      restore: "Mengembalikan",
    },
  },
};
