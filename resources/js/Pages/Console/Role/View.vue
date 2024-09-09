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

    const fetchPermissions = async () => {
        try {
            const response = await $.ajax({
                url: '/console/permissions',
                type: 'GET',
            });
            return response.data;
        } catch (error) {
            console.error('Error fetching permissions:', error);
            Swal.fire({
                            customClass: getSwalCustomClass(),
                            title: 'Error',
                            text: 'Failed to fetch permissions',
                            icon: 'error',
                        }, );
            return {};
        }
    };

    const showCreateRoleAlert = async () => {
        const permissionsByCategory = await fetchPermissions();
        let permissionCheckboxes = '';

        for (const category in permissionsByCategory) {
            if (permissionsByCategory.hasOwnProperty(category)) {
                permissionCheckboxes += `
                <div class="col-12">
                    <div class="list-group-item">
                        <h5>${category}</h5>`;
                permissionsByCategory[category].forEach(permission => {
                    permissionCheckboxes += `
                        <label class="form-check-label">
                            <input type="checkbox" name="permissions" value="${permission.name}" class="form-check-input">
                            ${permission.name}
                        </label>
                        <br>`;
                });
                permissionCheckboxes += `
                    </div>
                </div>`;
            }
        }

        Swal.fire({
            title: 'Create New Role',
            html: `
            <div class="form-group">
                <label for="add-name">Name</label>
                <input id="add-name" class="swal2-input" placeholder="Name">
            </div>
            <div class="form-group">
                <label for="add-permissions">List Permissions</label>
                <div class="list-group">
                    ${permissionCheckboxes}
                </div>
            </div>
        `,
            showCancelButton: true,
            confirmButtonText: 'Create',
            cancelButtonText: 'Cancel',
            customClass: getSwalCustomClass(),
            preConfirm: () => {
                const name = (document.getElementById('add-name') as HTMLInputElement).value;
                const selectedPermissions = Array.from(document.querySelectorAll(
                    'input[name="permissions"]:checked')).map(checkbox => checkbox.value);
                return {
                    name,
                    permissions: selectedPermissions
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
                $.ajax({
                    url: `/console/role/add`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    data: {
                        name: result.value.name,
                        permissions: result.value.permissions
                    },
                    success: function(response) {
                        $('#roleTable').DataTable().ajax.reload();
                        Swal.fire({
                            customClass: getSwalCustomClass(),
                            title: 'Success!',
                            text: 'Role added successfully',
                            icon: 'success',
                        }, );
                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding role:', error);
                        Swal.fire({
                            customClass: getSwalCustomClass(),
                            title: 'Error',
                            text: 'Failed to add role',
                            icon: 'error',
                        }, );
                    }
                });
            }
        });
    };

    const editRole = async (roleId: number) => {
        try {
            const response = await $.ajax({
                url: `/console/roles/edit/${roleId}`,
                type: 'GET',
            });

            const role = response.role;
            const allPermissions = response.permissions;
            const rolePermissions = role.permissions.map((perm: any) => perm.name);

            let permissionCheckboxes = '';

            for (const category in allPermissions) {
                if (allPermissions.hasOwnProperty(category)) {
                    permissionCheckboxes += `
                    <div class="col-12">
                        <div class="list-group-item">
                            <h5>${category}</h5>`;
                    allPermissions[category].forEach(permission => {
                        const checked = rolePermissions.includes(permission.name) ? 'checked' : '';
                        permissionCheckboxes += `
                            <label class="form-check-label">
                                <input type="checkbox" name="permissions" value="${permission.name}" class="form-check-input" ${checked}>
                                ${permission.name}
                            </label>
                            <br>`;
                    });
                    permissionCheckboxes += `
                        </div>
                    </div>`;
                }
            }

            Swal.fire({
                title: 'Edit Role',
                confirmButtonText: 'Save',
                customClass: getSwalCustomClass(),
                html: `
                <div class="form-group">
                    <label for="edit-name">Name</label>
                    <input id="edit-name" class="swal2-input" value="${role.name}" placeholder="Name">
                </div>
                <div class="form-group">
                    <label for="edit-permissions">Permissions</label>
                    <div class="list-group">
                        ${permissionCheckboxes}
                    </div>
                </div>
            `,
                focusConfirm: false,
                preConfirm: () => {
                    const name = (document.getElementById('edit-name') as HTMLInputElement).value;
                    const selectedPermissions = Array.from(document.querySelectorAll(
                        'input[name="permissions"]:checked')).map(checkbox => checkbox.value);
                    return {
                        name,
                        permissions: selectedPermissions
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

                    $.ajax({
                        url: `/console/roles/update/${roleId}`,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        data: {
                            name: result.value.name,
                            permissions: result.value.permissions,
                        },
                        success: function(response) {
                            $('#roleTable').DataTable().ajax.reload();
                            Swal.fire({
                                    customClass: getSwalCustomClass(),
                                    title: 'Success!',
                                    text: 'Role updated successfully',
                                    icon: 'success',
                                }, );
                        },
                        error: function(xhr, status, error) {
                            console.error('Error updating role:', error);
                            Swal.fire({
                                    customClass: getSwalCustomClass(),
                                    title: 'Error',
                                    text: 'Failed to update role',
                                    icon: 'error',
                                }, );
                        }
                    });
                }
            });
        } catch (error) {
            console.error('Error fetching role data:', error);
        }
    };

    const confirmDelete = (roleId: number) => {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this role!',
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
                    url: `/console/roles/destroy/${roleId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    success: function(response) {
                        $('#roleTable').DataTable().ajax.reload();
                        Swal.fire({
                            customClass: getSwalCustomClass(),
                            title: 'Sukses!',
                            text: 'Berhasil hapus role',
                            icon: 'success',
                        }, );
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting role:', error);
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
        const table = $('#roleTable').DataTable({
            pageLength: 5,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            ajax: {
                url: '/console/role/ajax',
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
                        const isSuperAdmin = row.name === 'super-admin';
                        return `
                        <button class="btn btn-primary btn-edit" data-id="${row.id}" ${isSuperAdmin ? 'disabled' : ''}>Edit</button> |
                        <button class="btn btn-danger btn-delete" data-id="${row.id}" ${isSuperAdmin ? 'disabled' : ''}>Delete</button>
                    `;
                    }
                }
            ]
        });

        $('#roleTable').on('click', '.btn-edit', function() {
            const roleId = $(this).data('id');
            if (!$(this).prop('disabled')) {
                editRole(roleId);
            }
        });

        $('#roleTable').on('click', '.btn-delete', function() {
            const roleId = $(this).data('id');
            confirmDelete(roleId);
        });
    });
</script>

<template>

    <Head title="Role" />

    <AuthenticatedLayout>
        <template #header>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight">All Roles</h2>
                <button class="btn-add" @click="showCreateRoleAlert">Add</button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <table id="roleTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <!-- <tbody>
                        <tr v-for="(role, index) in roles" :key="role.id">
                            <td class="border px-4 py-2">{{ index + 1 }}</td>
                            <td class="border px-4 py-2">{{ role . name }}</td>
                            <td class="border px-4 py-2">{{ role . email }}</td>
                            <td class="border px-4 py-2 text-left">{{ role . nohp }}</td>
                            <td class="border px-4 py-2">{{ formatDate(role . tanggal_lahir) }}</td>
                            <td class="border px-4 py-2">
                                <button @click="editRole(role.id)" class="btn btn-primary mr-2">Edit</button>
                                <button @click="confirmDelete(role.id)" class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                    </tbody> -->
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
