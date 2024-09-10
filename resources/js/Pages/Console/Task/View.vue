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
    import axios from 'axios';
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

    const showCreateTaskAlert = () => {
        Swal.fire({
            title: 'Create New Task',
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
                    url: `/console/task/add`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    data: {
                        name: result.value.name,
                    },
                    success: function(response) {
                        $('#taskTable').DataTable().ajax.reload();
                        Swal.fire({
                            customClass: getSwalCustomClass(),
                            title: 'Success!',
                            text: 'Task added successfully',
                            icon: 'success',
                        }, );
                    },
                    error: function(xhr, status, error) {
                        console.error('Error adding task:', error);
                        Swal.fire({
                            customClass: getSwalCustomClass(),
                            title: 'Error',
                            text: 'Failed to add task',
                            icon: 'error',
                        }, );
                    }
                });
            }
        });
    };

    const categories = ref < Array < {
        id: number;name: string
    } >> ([]);
    const todos = ref < Array < {
        category: number | null
    } >> ([]);
    const apiUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000';
    const getCategories = async () => {
        try {
            const response = await axios.get(`${apiUrl}/api/categories`);
            categories.value = response.data;

            // Update `todo.category` jika belum diisi
            if (categories.value.length > 0 && todos.value.length > 0) {
                todos.value.forEach(todo => {
                    if (!todo.category) {
                        todo.category = categories.value[0].id;
                    }
                });
            }
        } catch (error) {
            console.error('Failed to fetch categories:', error);
        }
    };

    const editTask = (taskId: number) => {
    $.ajax({
        url: `/console/tasks/edit/${taskId}`,
        type: 'GET',
        success: function(response) {
            const task = response.data;
            if (typeof task !== 'object' || Array.isArray(task)) {
                console.error('Unexpected data format for task:', task);
                return;
            }

            if (!Array.isArray(categories.value)) {
                console.error('Categories data should be an array:', categories.value);
                return;
            }

            const categoryOptions = categories.value.map(category => `
                <option value="${category.id}" ${category.id === task.category_id ? 'selected' : ''}>
                    ${category.name}
                </option>
            `).join('');

            Swal.fire({
                title: 'Edit Task',
                confirmButtonText: "Save",
                html: `
                    <div class="form-group">
                        <label for="edit-Description">Description</label>
                        <input id="edit-Description" class="swal2-input" value="${task.description}" placeholder="Description">
                    </div>
                    <div class="form-group">
                        <label for="edit-Category">Category</label>
                        <select id="edit-Category" class="swal2-input">
                            <option value="" disabled>Pilih kategori</option>
                            ${categoryOptions}
                        </select>
                    </div>
                `,
                focusConfirm: false,
                preConfirm: () => {
                    const description = (document.getElementById('edit-Description') as HTMLInputElement).value;
                    const category_id = (document.getElementById('edit-Category') as HTMLSelectElement).value;
                    const updated_by = 1; // Assuming you have a way to get this user ID

                    if (!description || !category_id) {
                        Swal.showValidationMessage('Please fill out all fields');
                        return false;
                    }

                    return {
                        description,
                        category_id,
                        updated_by
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

                    $.ajax({
                        url: `/console/tasks/update/${taskId}`,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        data: result.value,
                        success: function(response) {
                            $('#taskTable').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Success!',
                                text: 'Task updated successfully',
                                icon: 'success',
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error updating task:', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'Failed to update task',
                                icon: 'error',
                            });
                        }
                    });
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching task data:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to fetch task data',
                icon: 'error',
            });
        }
    });
};



    const confirmDelete = (taskId: number) => {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this task!',
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
                    url: `/console/tasks/destroy/${taskId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    success: function(response) {
                        $('#taskTable').DataTable().ajax.reload();
                        Swal.fire({
                            customClass: getSwalCustomClass(),
                            title: 'Sukses!',
                            text: 'Berhasil hapus task',
                            icon: 'success',
                        }, );
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting task:', error);
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

    onMounted(async () => {
        await getCategories();

        const table = $('#taskTable').DataTable({
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            ajax: {
                url: '/console/tasks/ajax',
                dataSrc: 'data',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return type === 'display' ? `<center>${meta.row + 1}</center>` :
                            meta.row + 1;
                    }
                },
                {
                    data: 'user.name',
                    render: function(data, type, row, meta) {
                        return row.user ? row.user.name : '';
                    }
                },
                {
                    data: 'user.username',
                    render: function(data, type, row, meta) {
                        return row.user ? row.user.username : '';
                    }
                },
                {
                    data: 'description'
                },
                {
                    data: 'category.name',
                    render: function(data, type, row, meta) {
                        return row.category ? row.category.name : '';
                    }
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

        $('#taskTable').on('click', '.btn-edit', function() {
            const taskId = $(this).data('id');
            editTask(taskId);
        });

        $('#taskTable').on('click', '.btn-delete', function() {
            const taskId = $(this).data('id');
            confirmDelete(taskId);
        });
    });
</script>

<template>

    <Head title="Task" />

    <AuthenticatedLayout>
        <template #header>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight">All Tasks</h2>
                <button class="btn-add" @click="showCreateTaskAlert">Add</button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <table id="taskTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">username</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Category</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <!-- <tbody>
                        <tr v-for="(task, index) in tasks" :key="task.id">
                            <td class="border px-4 py-2">{{ index + 1 }}</td>
                            <td class="border px-4 py-2">{{ task . name }}</td>
                            <td class="border px-4 py-2">{{ task . email }}</td>
                            <td class="border px-4 py-2 text-left">{{ task . nohp }}</td>
                            <td class="border px-4 py-2">{{ formatDate(task . tanggal_lahir) }}</td>
                            <td class="border px-4 py-2">
                                <button @click="editTask(task.id)" class="btn btn-primary mr-2">Edit</button>
                                <button @click="confirmDelete(task.id)" class="btn btn-danger">Delete</button>
                            </td>
                        </tr>
                    </tbody> -->
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
