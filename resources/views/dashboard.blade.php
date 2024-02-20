<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="oCegUMbuezeOFiGESyun79GIrZUr81EVyBwOtSt6">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <link rel="preload" as="style" href="http://127.0.0.1:2000/build/assets/app-CB8Dc99X.css" />
    <link rel="modulepreload" href="http://127.0.0.1:2000/build/assets/app-9mbrzSRH.js" />
    <link rel="stylesheet" href="http://127.0.0.1:2000/build/assets/app-CB8Dc99X.css" />
    <script type="module" src="http://127.0.0.1:2000/build/assets/app-9mbrzSRH.js"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('admin.layouts.navigation')

        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard
                </h2>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            You&#039;re loggeawd in!
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
