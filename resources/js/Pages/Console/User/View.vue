<script setup lang="ts">
    import {ref,onMounted,watch} from 'vue';
    import AuthenticatedLayout from '@/Layouts/Console/AuthenticatedLayout.vue';
    import {Head,usePage,router} from '@inertiajs/vue3';
    import $ from 'jquery';
    import Swal from 'sweetalert2';
    import 'datatables.net-select-bs5/css/select.bootstrap5.css';
    import 'datatables.net-dt';
    import 'datatables.net-dt/css/dataTables.dataTables.css';
    import 'datatables.net-bs5/css/dataTables.bootstrap5.css';
    // import 'bootstrap/dist/css/bootstrap.min.css';
    import '../../../../css/mystyle.css';
    import {useColorMode} from '@vueuse/core';
    import {Card,CardContent,CardHeader,CardTitle,CardDescription} from '@/Components/ui/card';

    const mode = useColorMode();

    const getSwalCustomClass = () => {
        return mode.value === 'dark' ? 'swal2-dark' : 'swal2-light';
    };

    const {
        props
    } = usePage();

    function formatDate(dateString: string): string {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        const month = monthNames[date.getMonth()];
        const year = date.getFullYear();

        return `${day}-${month}-${year}`;
    }

    const showCreateUserAlert = () => {
        Swal.fire({
            title: 'Create New User',
            html: `
            <div class="form-group">
                <label class="label" for="add-name">Name</label>
                <input id="add-name" class="swal2-input" placeholder="Name">
            </div>
            <div class="form-group">
                <label class="label" for="add-name">Username</label>
                <input id="add-username" class="swal2-input" placeholder="username">
            </div>
            <div class="form-group">
                <label class="label" for="add-email">Email</label>
                <input id="add-email" class="swal2-input" placeholder="Email">
            </div>
            <div class="form-group">
                <label class="label" for="add-password">Password</label>
                <input id="add-password" class="swal2-input" type="password" placeholder="Password default 123">
            </div>
        `,
            showCancelButton: true,
            confirmButtonText: 'Create',
            cancelButtonText: 'Cancel',
            customClass: getSwalCustomClass(),
            preConfirm: () => {
                return {
                    name: (document.getElementById('add-name') as HTMLInputElement).value,
                    username: (document.getElementById('add-username') as HTMLInputElement).value,
                    email: (document.getElementById('add-email') as HTMLInputElement).value,
                    password: (document.getElementById('add-password') as HTMLInputElement).value,
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                $.ajax({
                    url: `/console/user/add`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    data: {
                        name: result.value.name,
                        username: result.value.username,
                        email: result.value.email,
                        password: result.value.password,
                    },
                    success: function(response) {
                        $('#userTable').DataTable().ajax.reload();
                        Swal.fire({
                            customClass: getSwalCustomClass(),
                            title: 'Success!',
                            text: 'User added successfully',
                            icon: 'success',
                        }, );
                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding user:', error);
                        Swal.fire({
                            customClass: getSwalCustomClass(),
                            title: 'Error',
                            text: 'Failed to add user',
                            icon: 'error',
                        }, );
                    }
                });
            }
        });
    };

    const editUser = (userId: number) => {
        $.ajax({
            url: `/console/users/edit/${userId}`,
            type: 'GET',
            success: function(response) {
                const user = response.data;
                Swal.fire({
                    title: 'Edit User',
                    confirmButtonText: "Save",
                    customClass: getSwalCustomClass(),
                    html: `
                    <div class="form-group">
                        <label class="label" for="edit-name">Name</label>
                        <input id="edit-name" class="swal2-input" value="${user.name}" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label class="label" for="edit-name">Username</label>
                        <input id="edit-username" class="swal2-input" value="${user.username}" placeholder="username">
                    </div>
                    <div class="form-group">
                        <label class="label" for="edit-email">Email</label>
                        <input id="edit-email" class="swal2-input" value="${user.email}" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label class="label" for="edit-password">Password</label>
                        <input id="edit-password" class="swal2-input" type="password" placeholder="Leave blank if you don't want to change the password">
                    </div>
                `,
                    focusConfirm: false,
                    preConfirm: () => {
                        const name = (document.getElementById(
                            'edit-name') as HTMLInputElement).value;
                        const username = (document.getElementById(
                            'edit-username') as HTMLInputElement).value;
                        const email = (document.getElementById(
                            'edit-email') as HTMLInputElement).value;
                        const password = (document.getElementById(
                            'edit-password') as HTMLInputElement).value;

                        return {
                            name,
                            username,
                            email,
                            password
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const csrfToken = document.head.querySelector('meta[name="csrf-token"]')
                            .content;

                        $.ajax({
                            url: `/console/users/update/${userId}`,
                            type: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            data: {
                                name: result.value.name,
                                username: result.value.username,
                                email: result.value.email,
                                password: result.value.password,
                            },
                            success: function(response) {
                                $('#userTable').DataTable().ajax.reload();
                                Swal.fire({
                                    customClass: getSwalCustomClass(),
                                    title: 'Success!',
                                    text: 'User updated successfully',
                                    icon: 'success',
                                }, );
                            },
                            error: function(xhr, status, error) {
                                console.error('Error updating user:', error);
                                Swal.fire({
                                    customClass: getSwalCustomClass(),
                                    title: 'Error',
                                    text: 'Failed to update user',
                                    icon: 'error',
                                }, );
                            }
                        });
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching user data:', error);
            }
        });
    };

    const confirmDelete = (userId: number) => {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this user!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel',
            confirmButtonColor: "#d33",
            reverseButtons: true,
            customClass: getSwalCustomClass(),
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

                $.ajax({
                    url: `/console/users/destroy/${userId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    success: function(response) {
                        $('#userTable').DataTable().ajax.reload();
                        // Swal.fire('Sukses!', 'Berhasil hapus user.', 'info');
                        Swal.fire({
                            customClass: getSwalCustomClass(),
                            title: 'Sukses!',
                            text: 'Berhasil hapus user',
                            icon: 'success',
                        }, );
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting user:', error);
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    customClass: getSwalCustomClass(),
                    title: 'Cancelled',
                    text: 'Your user is safe 😊',
                    icon: 'error',
                }, );
            }
        });
    }

    onMounted(() => {
        const table = $('#userTable').DataTable({
            pageLength: 5,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            ajax: {
                url: '/console/user/ajax',
                dataSrc: 'data',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        if (type === 'display') {
                            return '<center>' + (meta.row + 1) + '</center>';
                        }
                        return meta.row + 1;
                    }
                },
                {
                    data: 'name'
                },
                {
                    data: 'username'
                },
                {
                    data: 'email'
                },
                {
                    render: function(data, type, row, meta) {
                        return `
                        <button class="btn btn-primary btn-edit" data-id="${row.id}">Edit</button> |
                        <button class="btn btn-danger btn-delete" data-id="${row.id}">Delete</button>
                    `;
                    }
                }
            ]
        });

        $('#userTable').on('click', '.btn-edit', function() {
            const userId = $(this).data('id');
            editUser(userId);
        });

        $('#userTable').on('click', '.btn-delete', function() {
            const userId = $(this).data('id');
            confirmDelete(userId);
        });
    });
</script>

<template>

    <Head title="User" />

    <AuthenticatedLayout>
        <template #header>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight">All Users</h2>
                <button class="btn-add" @click="showCreateUserAlert">Add</button>
            </div>
        </template>

        <!-- <div class="w-full items-center"> -->
            <!-- <CardContent className="grid gap-12"> -->
                <Card>
                    <CardContent className="grid gap-8">
                <CardHeader class="">
                    <CardTitle class=""> Total Users </CardTitle>
                    <CardDescription>Card Description</CardDescription>
                </CardHeader>
                    <div className=" flex items-center space-x-4 rounded-md border p-4">
                <table id="userTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Username</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <!-- tbody -->
                </table>
            </div>
                    </CardContent>
                    </Card>
            <!-- </CardContent> -->
            <!-- </div> -->
    </AuthenticatedLayout>
</template>
