<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-sm p-6 bg-white rounded-2xl shadow-lg">
        <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
        <form action="" method="POST" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="text" name="email" id="email"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm 
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required>
            </div>
            <div>
                <button type="submit"
                    class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg shadow-md 
                               hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500">
                    Login
                </button>
            </div>
        </form>
    </div>
</body>

</html>