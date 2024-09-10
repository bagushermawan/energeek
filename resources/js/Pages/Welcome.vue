<script setup lang="ts">
    import {
        Head,
        Link
    } from '@inertiajs/vue3';
    import 'bootstrap/dist/css/bootstrap.min.css';
    import '../../css/mystyle.css';
    import {
        ref,
        onMounted
    } from 'vue';
    import axios from 'axios';
    import Swal from 'sweetalert2';

    defineProps < {
        canLogin ? : boolean;
        canRegister ? : boolean;
        laravelVersion: string;
        phpVersion: string;
    } > ();

    const categories = ref < Array < {
        id: number;name: string
    } >> ([]);
    const todos = ref < Array < {
        description: string;category: number | null
    } >> ([]);
    const globalErrorMessage = ref('');
    const apiUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000';

    const getCategories = async () => {
        try {
            const response = await axios.get(`${apiUrl}/api/categories`);
            categories.value = response.data;

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

    const initializeTodos = () => {
        todos.value = Array(3).fill(null).map(() => ({
            description: '',
            category: categories.value.length > 0 ? categories.value[0].id : null
        }));
    };

    const addTodo = () => {
        todos.value.push({
            description: '',
            category: categories.value.length > 0 ? categories.value[0].id : null
        });
    };

    const removeTodo = (index: number) => {
        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: 'To do yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#F64E60',
            reverseButtons: false
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    todos.value.splice(index, 1);

                    Swal.fire({
                        title: 'Deleted!',
                        text: 'To Do berhasil dihapus.',
                        icon: 'success'
                    });
                } catch (error) {
                    console.error('Error deleting todo:', error);

                    Swal.fire({
                        title: 'Error!',
                        text: 'There was an error deleting the To Do.',
                        icon: 'error'
                    });
                }
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'Your To Do is safe 😊',
                    icon: 'info'
                });
            }
        });
    };

    onMounted(async () => {
        await getCategories();
        initializeTodos();
    });

    const errors = ref({
        name: '',
        username: '',
        email: '',
        todos: Array(todos.value.length).fill({
            description: ''
        }),
    });

    const validateForm = () => {
        errors.value.name = '';
        errors.value.username = '';
        errors.value.email = '';
        todos.value.forEach((_, index) => {
            errors.value.todos[index] = {
                description: ''
            };
        });

        globalErrorMessage.value = '';

        let isValid = true;

        const name = document.getElementById('name')?.value;
        if (!name) {
            errors.value.name = 'Nama wajib diisi';
            isValid = false;
        }

        const username = document.getElementById('username')?.value;
        if (!username) {
            errors.value.username = 'Username wajib diisi';
            isValid = false;
        }

        const email = document.getElementById('email')?.value;
        if (!email) {
            errors.value.email = 'Email wajib diisi';
            isValid = false;
        } else if (!/\S+@\S+\.\S+/.test(email)) {
            errors.value.email = 'Format email tidak valid';
            isValid = false;
        }

        todos.value.forEach((todo, index) => {
            if (!todo.description) {
                errors.value.todos[index].description = '*';
                isValid = false;
                if (!isValid) {
                    globalErrorMessage.value =
                        'To Do Yang bertanda <span class="text-danger">*</span> wajib diisi';
                }
            }
        });



        return isValid;
    };

    const saveData = async () => {
        if (!validateForm()) {
            return;
        }
        try {
            const userData = {
                name: document.getElementById('name')?.value,
                username: document.getElementById('username')?.value,
                email: document.getElementById('email')?.value,
            };

            const userResponse = await axios.post(`${apiUrl}/api/users`, userData);
            const userId = userResponse.data.id;

            const tasksData = todos.value.map(todo => ({
                description: todo.description,
                category_id: todo.category,
                user_id: userId,
                created_by: userId
            }));

            await axios.post(`${apiUrl}/api/tasks`, tasksData);

            Swal.fire({
                title: 'Success!',
                text: 'Data has been saved.',
                icon: 'success'
            });

            document.getElementById('name') !.value = '';
            document.getElementById('username') !.value = '';
            document.getElementById('email') !.value = '';

            todos.value = [];
            initializeTodos();


        } catch (error) {
            console.error('Error saving data:', error);
            Swal.fire({
                title: 'Error!',
                text: 'There was a problem saving the data.',
                icon: 'error'
            });
        }
    };
</script>

<template>

    <Head title="Welcome" />

    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <!-- <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-end">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
            Dashboard</Link>

            <template v-else>
                <Link :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Log in</Link>

                <Link v-if="canRegister" :href="route('register')"
                    class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Register</Link>
            </template>
        </div> -->

        <div class="container">
            <div class="flex justify-center">
                <img src="/energeeklogo.png" alt="" class="src">
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form>
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"
                        style="background-color:white;padding-top: 2rem;padding-bottom: 2rem;padding-left:1rem;padding-right:1rem;margin: 2rem;border-radius:10px;">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="name" placeholder="Nama">
                                <span v-if="errors.name" class="text-danger">{{ errors . name }}</span>
                            </div>
                            <div class="col">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" placeholder="Username">
                                <span v-if="errors.username" class="text-danger">{{ errors . username }}</span>
                            </div>
                            <div class="col">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Email">
                                <span v-if="errors.email" class="text-danger">{{ errors . email }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="todo-list" class="form-label">
                                <h3>To Do List</h3>
                            </label>
                        </div>
                        <div class="col text-end">
                            <button type="button" class="btn btn-pink" @click="addTodo">
                                <h5>+ Tambah To Do</h5>
                            </button>
                        </div>
                    </div>

                    <div ref="todoContainer">
                        <div v-for="(todo, index) in todos" :key="index" class="row mb-3">
                            <div class="col">
                                <label :for="'description' + index" class="form-label">Judul To Do</label>
                                <span v-if="errors.todos[index] && errors.todos[index].description" class="text-danger">
                                    {{ errors . todos[index] . description }}
                                </span>
                                <input type="text" class="form-control" :id="'description' + index"
                                    v-model="todo.description" placeholder="Judul To Do">

                            </div>
                            <div class="col-md-auto">
                                <label :for="'category-' + index" class="form-label">Kategori</label>
                                <select class="form-select" :id="'category-' + index" v-model="todo.category">
                                    <option value="" disabled>Pilih kategori</option>
                                    <option v-for="category in categories" :key="category.id"
                                        :value="category.id">
                                        {{ category . name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-auto" style="display:grid;align-items: end;">
                                <button type="button" class="btn btn-danger" @click="removeTodo(index)">
                                    <img src="/trash.png" alt="Trash" class="src">
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div v-if="globalErrorMessage" class="alert alert-warning" role="alert"
                            v-html="globalErrorMessage">
                        </div>
                        <button type="button" class="btn btn-success" @click="saveData">
                            SIMPAN
                        </button>
                    </div>
                </form>
            </div>

            <div class="flex justify-center mt-16 px-6 sm:items-center sm:justify-between">
                <div class="text-center text-sm sm:text-start">&nbsp;</div>

                <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-end sm:ms-0">
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .bg-dots-darker {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
    }

    @media (prefers-color-scheme: dark) {
        .dark\:bg-dots-lighter {
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
        }
    }
</style>
