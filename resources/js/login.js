import './bootstrap';
import { createApp } from 'vue';

const loginApp = createApp({
    data() {
        return {
            username: '',
            password: '',
            isLoading: false
        };
    },
    mounted() {
        const txtUser = document.querySelector("#txtUsername");
        if (txtUser) txtUser.focus();
    },
    methods: {
        loginAtc() {
            const username = document.querySelector("#txtUsername")?.value || this.username;
            const password = document.querySelector("#txtPassword")?.value || this.password;

            if (!username || !password) {
                window.pesanUmumApp('warning', 'Peringatan', 'Harap isi username dan password');
                return;
            }

            const rProsesLogin = (window.server || '/') + "auth/login/proses";
            const rDashboard = (window.server || '/') + "dashboard";

            window.axios.post(rProsesLogin, { username, password }).then((res) => {
                const obj = res.data;
                if (obj.status === "NO_USER") {
                    window.pesanUmumApp('warning', 'User Tidak Ditemukan', 'Tidak ada user terdaftar dengan username tersebut.');
                } else if (obj.status === 'WRONG_PASSWORD') {
                    window.pesanUmumApp('warning', 'Autentikasi Gagal', 'Username atau Password salah.');
                } else {
                    window.location.assign(rDashboard);
                }
            }).catch(() => {
                window.pesanUmumApp('error', 'Error', 'Terjadi kesalahan pada sistem.');
            });
        }
    }
});

if (document.getElementById('divLogin')) {
    loginApp.mount('#divLogin');
}
