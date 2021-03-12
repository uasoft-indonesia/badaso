export default {
  "login": {
    "title": "Masuk",
    "subtitle": "Selamat datang kembali, silakan masuk ke akun Anda.",
    "field": {
      "email": "Email",
      "password": "Kata sandi",
    },
    "rememberMe": "Ingat saya?",
    "forgotPassword": "Lupa kata sandi",
    "button": "Masuk",
    "createAccount": {
      "text": "Belum punya akun?",
      "link": "Buat akun"
    }
  },

  "register": {
    "title": "Daftar",
    "subtitle": "Silakan isi formulir di bawah ini.",
    "field": {
      "name": "Nama",
      "email": "Email",
      "password": "Kata sandi",
      "passwordConfirmation": "Konfirmasi kata sandi"
    },
    "button": "Daftar",
    "existingAccount": {
      "text": "Sudah mempunyai akun?",
      "link": "Masuk"
    }
  },

  "forgotPassword": {
    "title": "Lupa Password",
    "subtitle": "Harap berikan email untuk mengirim surat atur ulang kata sandi.",
    "field": {
      "email": "Email"
    },
    "button": "Kirim",
    "createAccount": {
      "text": "Belum punya akun?",
      "link": "Buat akun"
    },
    "message": {
      "success": "Email telah dikirim ke alamat email yang Anda berikan. Silakan ikuti tautan di email untuk menyelesaikan permintaan pengaturan ulang kata sandi Anda.",
      "error": "Terjadi kesalahan. Silakan periksa kembali email yang Anda berikan. Jika masalah terus berlanjut, harap hubungi kami untuk bantuan lebih lanjut."
    }
  },

  "resetPassword": {
    "title": "Setel Ulang Kata Sandi",
    "subtitle": "Harap berikan kata sandi baru.",
    "field": {
      "password": "Kata sandi baru",
      "passwordConfirmation": "Konfirmasi kata sandi baru"
    },
    "button": "Setel Ulang Kata Sandi",
    "createAccount": {
      "text": "Belum punya akun?",
      "link": "Buat akun"
    },
    "message": {
      "success": "Atur ulang kata sandi berhasil. Anda dapat login dengan menggunakan kata sandi baru. Anda akan dialihkan ke halaman login.",
      "error": "Terjadi kesalahan. Silakan periksa kembali kata sandi dan konfirmasi kata sandi yang Anda berikan. Jika masalah terus berlanjut, harap hubungi kami untuk bantuan lebih lanjut."
    }
  },

  "verifyEmail": {
    "title": "Verifikasi Email",
    "failed": "Verifikasi Email sedang diproses ....",
    "button": "Verifikasi",
    "request": "Kirim ulang",
    "field": {
      "token": "Token",
    },
    "message": {
      "inProgress": "Verifikasi Email sedang diproses  ....",
      "success": "Email telah dikirim ke alamat email yang Anda berikan. Silakan ikuti tautan di email untuk menyelesaikan permintaan pengaturan ulang kata sandi Anda.",
      "error": "Verifikasi Email gagal."
    }
  },

  "sidebar": {
    "dashboard": "Beranda",
    "mainMenu": "Menu Utama",
    "configurationMenu": "Menu Konfigurasi"
  },

  "myProfile": {
    "title": "My Profile",
    "logout": "Logout",
    "profile": "Profile",
    "email": "Email",
    "password": "Password",
    "oldPassword": "Current Password",
    "newPassword": "New Password",
    "newPasswordConfirmation": "New Password Confirmation",
    "name": "Name",
    "avatar": "Avatar",
    "additionalInfo": "Additional info",
    "token": "Verification Code",
    "buttons": {
      "updateProfile": "Update Profile",
      "updateEmail": "Update Email",
      "verifyEmail": "Verify Email",
      "changePassword": "Change Password"
    }
  },

  "404": {
    "title": "Opps, Maaf",
    "subtitle": "Halaman yang Anda cari tidak ditemukan.",
    "button": "Kembali ke Home"
  },

  "action": {
    "bulkDelete": "Hapus Massal",
    "add": "Tambah",
    "delete": {
      "title": "Konfirmasi",
      "text": "Apakah Anda yakin ingin menghapus?",
      "accept": "Ya",
      "cancel": "Tidak"
    },
    "addItem": "Tambahkan Item",
    "roles": "Wewenang",
    "sort": "Menyortir"
  },

  "alert": {
    "success": "Sukses",
    "danger": "Bahaya"
  },

  "activityLog": {
    "title": "Log Aktivitas",
    "warning": {
      "notAllowed": "Anda tidak diizinkan untuk mengakses Log Aktivitas."
    },
    "header": {
      "logName": "Nama Log",
      "causerType": "Jenis Penyebab",
      "causerId": "Id Penyebab",
      "subjectType": "Jenis Subjek",
      "subjectId": "Id Subjek",
      "description": "Deskripsi",
      "dateLogged": "Tanggal Dicatat",
      "action": "Aksi",
    }, 
    "footer": {
      "descriptionTitle": "Baris yang ditampilkan",
      "descriptionConnector": "dari",
      "descriptionBody": "Halaman"
    },
    "detail": {
      "title": "Detail Log Aktivitas",
      "causer": {
        "title": "Detail Penyebab"
      },
      "subject": {
        "title": "Detail Subjek"
      },
      "properties": {
        "title": "Properti"
      }
    }
  },

  "site": {
    "action": "Aksi",
    "deletedImage": {
      "title": "Gambar yang telah dihapus",
      "text": "Gambar yang dipilih telah dihapus."
    },
    "configUpdated": "Konfigurasi telah Diperbarui",
    "add": {
      "title": "Tambah Konfigurasi Situs",
      "field": {
        "displayName": {
          "title": "Nama Tampilan",
          "placeholder": "Contoh: Nama Tampilan"
        },
        "key": {
          "title": "Kunci",
          "placeholder": "Contoh: contoh_kunci"
        },
        "type": {
          "title": "Tipe",
          "placeholder": "Tipe"
        },
        "group": {
          "title": "Grup",
          "placeholder": "Grup"
        },
        "options": {
          "title": "Opsi",
          "description": "Opsi diperlukan untuk Checkbox, Radio, Select, Select-multiple. Contoh: ",
          "example": `{"items": [{"label":"Ini label","value":"ini_nilai"}] }`
        },
      },
      "button": "Save"
    },
    "edit":  {
      "multiple": "Simpan Konfigurasi"
    }
  },

  "crud": {
    "title": "CRUD",
    "warning": {
      "notAllowed": "Anda tidak diizinkan untuk mengakses CRUD."
    },
    "header": {
      "table": "Tabel",
      "action": "Aksi"
    },
    "body": {
      "button": "Tambahkan CRUD ke tabel ini"
    },
    "footer": {
      "descriptionTitle": "Baris yang ditampilkan",
      "descriptionConnector": "dari",
      "descriptionBody": "Halaman"
    },
    "add": {
      "title": {
        "table": "Tambahkan CRUD untuk {tableName}",
        "field": "Tambahkan bidang CRUD untuk {tableName}"
      },
      "field": {
        "tableName": {
          "title": "Nama Tabel",
          "placeholder": "Nama Tabel"
        },
        "generatePermissions": "Hasilkan Izin",
        "serverSide": "Sisi Server",
        "displayNameSingular": {
          "title": "Nama Tampilan(Tunggal)",
          "placeholder": "Nama Tampilan(Tunggal)"
        },
        "displayNamePlural": {
          "title": "Nama Tampilan (Jamak)",
          "placeholder": "Nama Tampilan (Jamak)"
        },
        "urlSlug": {
          "title": "URL Slug (harus unik)",
          "placeholder": "URL Slug (harus unik)"
        },
        "icon": {
          "title": "Ikon",
          "placeholder": "Ikon"
        },
        "modelName": {
          "title": "Nama Model",
          "placeholder": "Nama Model"
        },
        "controllerName": {
          "title": "Nama Controller",
          "placeholder": "Nama Controller"
        },
        "orderColumn": {
          "title": "Kolom Urutan",
          "placeholder": "Kolom Urutan"
        },
        "orderDisplayColumn": {
          "title": "Kolom Urutan Tampilan",
          "placeholder": "Kolom Urutan Tampilan",
          "description": "<p class='text-muted'>Kolom Urutan akan diisi dengan angka untuk mengurutkan data jika field ini sudah diset</p>"
        },
        "orderDirection": {
          "title": "Arah Urutan",
          "value": {
            "ascending": "Naik",
            "descending": "Turun"
          },
          "placeholder": "Arah Urutan"
        },
        "defaultServerSideSearchField": {
          "title": "Bidang Pencarian Sisi Server Default",
          "placeholder": "Bidang Pencarian Sisi Server Default"
        },
        "description": {
          "title": "Deskripsi",
          "placeholder": "Deskripsi"
        },
      },
      "header": {
        "field": "Bidang",
        "visibility": "Visibilitas",
        "inputType": "Tipe Masukan",
        "displayName": "Nama Tampilan",
        "optionalDetails": "Detail Opsional",
      },
      "body": {
        "type": "Tipe:",
        "required": {
          "title": "Wajib:",
          "yes": "Ya",
          "no": "Tidak"
        },
        "browse": "Jelajahi",
        "read": "Baca",
        "edit": "Ubah",
        "add": "Tambah",
        "delete": "Hapus",
        "displayName": "Nama Tampilan",
        "setRelation": "Set Relasi",
        "relationType": "Tipe Relasi",
        "destinationTable": "Tabel Tujuan",
        "destinationTableColumn": "Kolom Tujuan",
        "destinationTableDisplayColumn": "Kolom Tujuan Untuk Ditampilkan",
        "saveRelation": "Simpan",
        "cancelRelation": "Batal",
      },
      "button": "Simpan"
    },
    "edit": {
      "title": {
        "table": "Ubah CRUD untuk {tableName}",
        "field": "Ubah bidang CRUD untuk {tableName}"
      },
      "field": {
        "tableName": {
          "title": "Nama Tabel",
          "placeholder": "Nama Tabel"
        },
        "generatePermissions": "Hasilkan Izin",
        "serverSide": "Sisi Server",
        "displayNameSingular": {
          "title": "Nama Tampilan(Tunggal)",
          "placeholder": "Nama Tampilan(Tunggal)"
        },
        "displayNamePlural": {
          "title": "Nama Tampilan (Jamak)",
          "placeholder": "Nama Tampilan (Jamak)"
        },
        "urlSlug": {
          "title": "URL Slug (harus unik)(read-only)",
          "placeholder": "URL Slug (harus unik)(read-only)"
        },
        "icon": {
          "title": "Ikon",
          "placeholder": "Ikon"
        },
        "modelName": {
          "title": "Nama Model",
          "placeholder": "Nama Model"
        },
        "controllerName": {
          "title": "Nama Controller",
          "placeholder": "Nama Controller"
        },
        "orderColumn": {
          "title": "Kolom Urutan",
          "placeholder": "Kolom Urutan"
        },
        "orderDisplayColumn": {
          "title": "Kolom Urutan Tampilan",
          "placeholder": "Kolom Urutan Tampilan",
          "description": "<p class='text-muted'>Kolom Urutan akan diisi dengan angka untuk mengurutkan data jika field ini sudah diset</p>"
        },
        "orderDirection": {
          "title": "Arah Urutan",
          "value": {
            "ascending": "Naik",
            "descending": "Turun"
          },
          "placeholder": "Arah Urutan"
        },
        "defaultServerSideSearchField": {
          "title": "Bidang Pencarian Sisi Server Default",
          "placeholder": "Bidang Pencarian Sisi Server Default"
        },
        "description": {
          "title": "Deskripsi",
          "placeholder": "Deskripsi"
        },
      },
      "header": {
        "field": "Bidang",
        "visibility": "Visibilitas",
        "inputType": "Tipe Masukan",
        "displayName": "Nama Tampilan",
        "optionalDetails": "Detail Opsional",
      },
      "body": {
        "type": "Tipe:",
        "required": {
          "title": "Wajib:",
          "yes": "Ya",
          "no": "Tidak"
        },
        "browse": "Jelajahi",
        "read": "Baca",
        "edit": "Ubah",
        "add": "Tambah",
        "delete": "Hapus",
        "displayName": "Nama Tampilan",
        "setRelation": "Set Relasi",
        "relationType": "Tipe Relasi",
        "destinationTable": "Tabel Tujuan",
        "destinationTableColumn": "Kolom Tujuan",
        "destinationTableDisplayColumn": "Kolom Tujuan Untuk Ditampilkan",
        "saveRelation": "Simpan",
        "cancelRelation": "Batal",
      },
      "button": "Simpan"
    }
  },

  "menu": {
    "title": "Menu",
    "warning": {
      "notAllowedToBrowse": "Anda tidak diizinkan untuk mengakses Menu",
      "notAllowedToAdd": "Anda tidak diizinkan untuk menambahkan Menu",
      "notAllowedToEdit": "Anda tidak diizinkan untuk mengubah Menu",
    },
    "header": {
      "key": "Kunci",
      "displayName": "Nama Tampilan",
      "action": "Aksi"
    },
    "footer": {
      "descriptionTitle": "Baris yang ditampilkan",
      "descriptionConnector": "dari",
      "descriptionBody": "Halaman"
    },
    "add": {
      "title": "Tambah Menu",
      "field": {
        "key": {
          "title": "Kunci",
          "placeholder": "kunci_menu"
        },
        "displayName": {
          "title": "Nama Tampilan",
          "placeholder": "Nama Tampilan"
        },
        "icon": {
          "title": "Icon",
          "placeholder": "Icon"
        }
      },
      "button": "Simpan"
    },
    "edit": {
      "title": "Ubah Menu",
      "field": {
        "key": {
          "title": "Kunci",
          "placeholder": "kunci_menu"
        },
        "displayName": {
          "title": "Nama Tampilan",
          "placeholder": "Nama Tampilan"
        }
      },
      "button": "Simpan"
    },
    "builder": {
      "title": "Item Menu",
      "popup": {
        "add": {
          "title": "Tambah Item Menu",
          "field": {
            "title": "Judul",
            "url": "Url",
            "target": {
              "title": "Target",
              "value": {
                "thisTab": "Tab Ini",
                "newTab": "Tab Baru"
              }
            },
            "icon": {
              "title": "Ikon",
              "description": "Gunakan&nbsp;<a href='https://material.io/resources/icons/?style=baseline' target='_blank'>ikon material design</a>"
            },
          },
          "button": {
            "add": "Tambah",
            "cancel": "Batal"
          }
        },
        "edit": {
          "title": "Ubah Item Menu",
          "field": {
            "title": "Judul",
            "url": "Url",
            "target": {
              "title": "Target",
              "value": {
                "thisTab": "Tab Ini",
                "newTab": "Tab Baru"
              }
            },
            "icon": {
              "title": "Ikon",
              "description": "Gunakan&nbsp;<a href='https://material.io/resources/icons/?style=baseline' target='_blank'>ikon material design</a>"
            },
          },
          "button": {
            "edit": "Ubah",
            "cancel": "Batal"
          }
        }
      }
    }
  },

  "user": {
    "title": "Pengguna",
    "header": {
      "name": "Nama",
      "email": "Email",
      "action": "Aksi"
    },
    "footer": {
      "descriptionTitle": "Baris yang ditampilkan",
      "descriptionConnector": "dari",
      "descriptionBody": "Halaman"
    },
    "add": {
      "title": "Tambah Pengguna",
      "field": {
        "name": {
          "title": "Nama",
          "placeholder": "Nama"
        },
        "email": {
          "title": "Email",
          "placeholder": "Email"
        },
        "password": {
          "title": "Kata sandi",
          "placeholder": "Kata sandi"
        },
        "avatar": {
          "title": "Avatar",
          "placeholder": "Avatar"
        },
        "additionalInfo": {
          "title": "Info Tambahan (JSON)",
          "placeholder": "Info Tambahan (JSON)",
          "invalid": "Info Tambahan tidak valid"
        },
      },
      "button": "Simpan"
    },
    "edit": {
      "title": "Ubah Pengguna",
      "field": {
        "name": {
          "title": "Nama",
          "placeholder": "Nama"
        },
        "email": {
          "title": "Email",
          "placeholder": "Email"
        },
        "password": {
          "title": "Kata sandi",
          "placeholder": "Biarkan kosong jika tidak diubah"
        },
        "avatar": {
          "title": "Avatar",
          "placeholder": "Avatar Baru"
        },
        "additionalInfo": {
          "title": "Info Tambahan (JSON)",
          "placeholder": "Info Tambahan (JSON)",
          "invalid": "Info Tambahan tidak valid"
        },
      },
      "button": "Simpan"
    },
    "detail": {
      "title": "Detail Pengguna",
      "avatar": "Avatar",
      "name": "Nama",
      "email": "Email",
      "additionalInfo": "Info Tambahan",
    },
    "roles": {
      "title": "Wewenang",
      "header": {
        "name": "Nama",
        "description": "Deskripsi",
        "action": "Aksi"
      },
      "button": "Tetapkan wewenang yang dipilih untuk pengguna",
      "success": {
        "title": "Success",
        "text": 'Wewenang telah ditetapkan',
      }
    }
  },

  "role": {
    "title": "Wewenang",
    "warning": {
      "notAllowedToBrowse": "Anda tidak diizinkan untuk mengakses Wewenang",
      "notAllowedToAdd": "Anda tidak diizinkan untuk menambah Wewenang",
      "notAllowedToEdit": "Anda tidak diizinkan untuk mengubah Wewenang",
      "notAllowedToBrowsePermission": "Anda tidak diizinkan untuk mengakses Izin Wewenang",
    },
    "header": {
      "name": "Nama",
      "displayName": "Nama Tampilan",
      "description": "Deskripsi",
      "action": "Aksi"
    },
    "footer": {
      "descriptionTitle": "Baris yang ditampilkan",
      "descriptionConnector": "dari",
      "descriptionBody": "Halaman"
    },
    "add": {
      "title": "Tambah Wewenang",
      "field": {
        "name": {
          "title": "Nama",
          "placeholder": "Nama"
        },
        "displayName": {
          "title": "Nama Tampilan",
          "placeholder": "Nama Tampilan"
        },
        "description": {
          "title": "Deskripsi",
          "placeholder": "Deskripsi"
        },
      },
      "button": "Simpan"
    },
    "edit": {
      "title": "Ubah Wewenang",
      "field": {
        "name": {
          "title": "Nama",
          "placeholder": "Nama"
        },
        "displayName": {
          "title": "Nama Tampilan",
          "placeholder": "Nama Tampilan"
        },
        "description": {
          "title": "Deskripsi",
          "placeholder": "Deskripsi"
        },
      },
      "button": "Simpan"
    },
    "detail": {
      "title": "Detail Wewenang",
      "name": "Nama",
      "displayName": "Nama Tampilan",
      "description": "Deskripsi", 
      "button": {
        "edit": "Ubah",
        "permission": "Izin"
      }
    },
    "permission": {
      "title": "Izin",
      "header": {
        "key": "Kunci",
        "description": "Deskripsi"
      },
      "button": "Tetapkan izin yang dipilih untuk wewenang",
      "success": {
        "title": "Sukses",
        "text": "Izin telah ditetapkan",
      }
    }
  },

  "permission": {
    "title": "Izin",
    "warning": {
      "notAllowedToBrowse": "Anda tidak diizinkan untuk mengakses Izin",
      "notAllowedToAdd": "Anda tidak diizinkan untuk menambahkan Izin",
      "notAllowedToEdit": "Anda tidak diizinkan untuk mengubah Izin",
      "notAllowedToRead": "Anda tidak diizinkan untuk mengakses Izin",
    },
    "header": {
      "key": "Kunci",
      "description": "Deskripsi",
      "tableName": "Nama Tabel",
      "alwaysAllow": "Selalu diizinkan",
      "action": "Aksi",
    },
    "footer": {
      "descriptionTitle": "Baris yang ditampilkan",
      "descriptionConnector": "dari",
      "descriptionBody": "Halaman"
    },
    "add": {
      "title": "Tambah Izin",
      "field": {
        "key": {
          "title": "Kunci",
          "placeholder": "Kunci"
        },
        "alwaysAllow": "Selalu diizinkan",
        "isPublic": "Publik",
        "description": {
          "title": "Deskripsi",
          "placeholder": "Deskripsi"
        },
        "tableName": {
          "title": "Nama Tabel",
          "placeholder": "Nama Tabel",
        }
      },
      "button": "Simpan"
    },
    "edit": {
      "title": "Ubah Izin",
      "field": {
        "key": {
          "title": "Kunci",
          "placeholder": "Kunci"
        },
        "alwaysAllow": "Selalu diizinkan",
        "isPublic": "Public",
        "description": {
          "title": "Deskripsi",
          "placeholder": "Deskripsi"
        },
        "tableName": {
          "title": "Nama Tabel",
          "placeholder": "Nama Tabel",
        }
      },
      "button": "Simpan"
    },
    "detail": {
      "title": "Detail Izin",
      "key": "Kunci",
      "description": "Deskripsi",
      "tableName": "Nama Tabel",
      "alwaysAllow": {
        "title": "Selalu diizinkan",
        "yes": "Ya",
        "no": "Tidak",
      },
      "isPublic": {
        "title": "Publik",
        "yes": "Ya",
        "no": "Tidak",
      },
      "button": "Ubah"
    }
  },

  "crudGenerated": {
    "header": {
      "action": "Aksi"
    },
    "footer": {
      "descriptionTitle": "Baris yang ditampilkan",
      "descriptionConnector": "dari",
      "descriptionBody": "Halaman"
    },
    "add": {
      "title": "Tambah {tableName}",
      "button": "Simpan"
    },
    "edit": {
      "title": "Ubah {tableName}",
      "button": "Simpan"
    },
    "detail": {
      "title": "Detail {tableName}",
      "button": "Ubah"
    },
    "sort": {
      "title": "Menyortir {tableName}",
    }
  }
}