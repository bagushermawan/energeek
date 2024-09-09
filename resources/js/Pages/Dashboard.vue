<script setup lang="ts">
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import {
        Head, usePage
    } from '@inertiajs/vue3';
    import { ref } from 'vue';

    function formatDate(dateString: string): string {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        const month = monthNames[date.getMonth()];
        const year = date.getFullYear();

        return `${day} ${month} ${year}`;
    }
    import '../../css/dashboard.css';

    const page = usePage();
    const flashMessage = page.props?.flash?.status;

    function getRole(roles) {
    if (roles && roles.length > 0) {
        return roles.map(role => role.name).join(', '); // Menggabungkan nama role jika ada lebih dari satu
    }
    return 'Role not found';
    }

const showTestToast = () => {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 30000,
        timerProgressBar: true,
        customClass: {
            timerProgressBar: 'custom-timer-progress-bar'
        },
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
            toast.addEventListener('click', () => {
                Swal.close();
            });
        }
    });

    Toast.fire({
        icon: "info",
        title: "This is a test toast!"
    });
};
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <!-- <div class="p-6">
          <div class="card">
            <div class="card-header">
              <div class="card-title">
                <h4>Informasi Data Anda</h4>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-borderless">
                <tbody>
                  <tr>
                    <td class="fw-bold" style="width: 200px;">Nama:</td>
                    <td>{{ $page.props.auth.user.name }}</td>
                  </tr>
                  <tr>
                    <td class="fw-bold">Email:</td>
                    <td>{{ $page.props.auth.user.email }}</td>
                  </tr>
                  <tr>
                    <td class="fw-bold">No HP:</td>
                    <td>{{ $page.props.auth.user.nohp }}</td>
                  </tr>
                  <tr>
                    <td class="fw-bold">Tanggal Lahir:</td>
                    <td>{{ formatDate($page.props.auth.user.tanggal_lahir) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div> -->

            <div class="col-xl-6 col-md-12">
                <div class="card user-card-full">
                    <div class="row m-l-0 m-r-0">
                        <div class="col-sm-4 bg-c-lite-green user-profile">
                            <div class="card-block text-center text-white">
                                <div class="m-b-25">
                                    <img src="https://img.icons8.com/bubbles/100/000000/user.png" class="img-radius"
                                        alt="User-Profile-Image">
                                </div>
                                <h1 class="f-w-600">{{ $page.props.auth.user.name }}</h1>
                                <p>({{ getRole($page.props.auth.user.roles) }})</p>
                                <i class=" mdi mdi-square-edit-outline feather icon-edit m-t-10 f-16"></i>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="card-block">
                                <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Information</h6>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p class="m-b-10 f-w-600">Email</p>
                                        <h6 class="text-muted f-w-400">{{ $page.props.auth.user.email }}</h6>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="m-b-10 f-w-600">Phone</p>
                                        <h6 class="text-muted f-w-400">{{ $page.props.auth.user.nohp }}</h6>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="m-b-10 f-w-600">Tanggal Lahir</p>
                                        <h6 class="text-muted f-w-400">{{ formatDate($page.props.auth.user.tanggal_lahir) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


      </div>
    </div>
  </div>


    </AuthenticatedLayout>
</template>
