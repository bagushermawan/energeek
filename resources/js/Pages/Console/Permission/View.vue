<script setup lang="ts">
    import {
        ref,
        onMounted
    } from 'vue';
    import AuthenticatedLayout from '@/Layouts/Console/AuthenticatedLayout.vue';
    import {
        Head,
        usePage,
        router
    } from '@inertiajs/vue3';
    import $ from 'jquery';
    import Swal from 'sweetalert2';
    import 'datatables.net-select-bs5/css/select.bootstrap5.css';
    import 'datatables.net-dt';
    import 'datatables.net-dt/css/dataTables.dataTables.css';
    import 'datatables.net-bs5/css/dataTables.bootstrap5.css';
    // import 'bootstrap/dist/css/bootstrap.min.css';
    import '../../../../css/mystyle.css';
    import {
        useColorMode
    } from '@vueuse/core';

    const mode = useColorMode();

    const getSwalCustomClass = () => {
        return mode.value === 'dark' ? 'swal2-dark' : 'swal2-light';
    };

    const {
        props
    } = usePage();

    const showCreatePermissionAlert = () => {
        Swal.fire({
            title: 'Create New Permission',
            html: `
            <div class="form-group">
                <label for="add-name">Name</label>
                <input id="add-name" class="swal2-input" placeholder="Name">
            </div>
        `,
            showCancelButton: true,
            confirmButtonText: 'Create',
            cancelButtonText: 'Cancel',
            customClass: getSwalCustomClass(),
            preConfirm: () => {
                return {
                    name: (document.getElementById('add-name') as HTMLInputElement).value,
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                $.ajax({
                    url: `/console/permission/add`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    data: {
                        name: result.value.name,
                    },
                    success: function(response) {
                        $('#permissionTable').DataTable().ajax.reload();
                        Swal.fire({
                            customClass: getSwalCustomClass(),
                            title: 'Success!',
                            text: 'Permission added successfully',
                            icon: 'success',
                        }, );
                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding permission:', error);
                        Swal.fire({
                            customClass: getSwalCustomClass(),
                            title: 'Error',
                            text: 'Failed to add permission',
                            icon: 'error',
                        }, );
                    }
                });
            }
        });
    };

    const editPermission = (permissionId: number) => {
        $.ajax({
            url: `/console/permissions/edit/${permissionId}`,
            type: 'GET',
            success: function(response) {
                const permission = response.data;
                Swal.fire({
                    title: 'Edit Permission',
                    confirmButtonText: "Save",
                    html: `
                    <div class="form-group">
                        <label for="edit-name">Name</label>
                        <input id="edit-name" class="swal2-input" value="${permission.name}" placeholder="Name">
                    </div>
                `,
                    focusConfirm: false,
                    preConfirm: () => {
                        const name = (document.getElementById(
                            'edit-name') as HTMLInputElement).value;
                        return {
                            name,
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const csrfToken = document.head.querySelector('meta[name="csrf-token"]')
                            .content;

                        $.ajax({
                            url: `/console/permissions/update/${permissionId}`,
                            type: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            data: {
                                name: result.value.name,
                                email: result.value.email,
                                nohp: result.value.nohp,
                                tanggal_lahir: result.value.tanggal_lahir,
                                password: result.value.password,
                            },
                            success: function(response) {
                                $('#permissionTable').DataTable().ajax.reload();
                                Swal.fire({
                                    customClass: getSwalCustomClass(),
                                    title: 'Success!',
                                    text: 'Permission updated successfully',
                                    icon: 'success',
                                }, );
                            },
                            error: function(xhr, status, error) {
                                console.error('Error updating permission:', error);
                                Swal.fire({
                                    customClass: getSwalCustomClass(),
                                    title: 'Error',
                                    text: 'Failed to update permission',
                                    icon: 'error',
                                }, );
                            }
                        });
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching permission data:', error);
            }
        });
    };

    const confirmDelete = (permissionId: number) => {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this permission!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel',
            confirmButtonColor: "#d33",
            customClass: getSwalCustomClass(),
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

                $.ajax({
                    url: `/console/permissions/destroy/${permissionId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    success: function(response) {
                        $('#permissionTable').DataTable().ajax.reload();
                        Swal.fire({
                            customClass: getSwalCustomClass(),
                            title: 'Sukses!',
                            text: 'Berhasil hapus permission',
                            icon: 'success',
                        }, );
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting permission:', error);
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
        const table = $('#permissionTable').DataTable({
            pageLength: 5,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            ajax: {
                url: '/console/permission/ajax',
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
                    render: function(data, type, row, meta) {
                        return `
                        <button class="btn btn-primary btn-edit" data-id="${row.id}">Edit</button> |
                        <button class="btn btn-danger btn-delete" data-id="${row.id}">Delete</button>
                    `;
                    }
                }
            ]
        });

        $('#permissionTable').on('click', '.btn-edit', function() {
            const permissionId = $(this).data('id');
                editPermission(permissionId);
        });

        $('#permissionTable').on('click', '.btn-delete', function() {
            const permissionId = $(this).data('id');
            confirmDelete(permissionId);
        });
    });
</script>

<template>

    <Head title="Permission" />

    <AuthenticatedLayout>
        <template #header>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight">All Permissions</h2>
                <button class="btn-add" @click="showCreatePermissionAlert">Add</button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <table id="permissionTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <!-- <tbody>
                        <tr v-for="(permission, index) in permissions" :key="permission.id">
                            <td class="border px-4 py-2">{{ index + 1 }}</td>
                            <td class="border px-4 py-2">{{ permission . name }}</td>
                            <td class="border px-4 py-2">{{ permission . email }}</td>
                            <td class="border px-4 py-2 text-left">{{ permission . nohp }}</td>
                            <td class="border px-4 py-2">{{ formatDate(permission . tanggal_lahir) }}</td>
                            <td class="border px-4 py-2">
                                <button @click="editPermission(permission.id)" class="btn btn-primary mr-2">Edit</button>
                                <button @click="confirmDelete(permission.id)" class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                    </tbody> -->
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
