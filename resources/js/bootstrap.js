import axios from 'axios';
import Swal from 'sweetalert2';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

window.Swal = Swal;

window.pesanUmumApp = function (icon, title, text) {
    return Swal.fire({
        icon: icon,
        title: title,
        text: text
    });
};

window.confirmQuest = function (icon, title, text, callback) {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
    }).then((result) => {
        if (result.value || result.isConfirmed) {
            callback();
        }
    });
};
