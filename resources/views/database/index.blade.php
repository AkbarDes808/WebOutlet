<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Database Control</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            WISH MANAGEMENT
        </h1>

        <p class="text-gray-500 mt-1">
            Database Control
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border">

        <div class="px-6 py-5 border-b">
            <h2 class="text-xl font-bold">
                Database: {{ config('database.connections.pgsql.database') }}
            </h2>
        </div>

        <div class="p-6">

            <h3 class="text-lg font-semibold mb-4">
                Tables
            </h3>

            <div class="grid gap-3">

                @foreach($tables as $item)

                    <a
                        href="{{ route('database.table', $item->table_name) }}"
                        class="flex items-center justify-between
                               px-5 py-4
                               border rounded-lg
                               hover:bg-blue-50
                               hover:border-blue-400
                               transition"
                    >

                        <div class="flex items-center gap-3">

                            <span class="text-gray-500">
                                🗄️
                            </span>

                            <span class="font-semibold text-blue-600">
                                {{ $item->table_name }}
                            </span>

                        </div>

                        <span class="text-gray-400">
                            →
                        </span>

                    </a>

                @endforeach

            </div>

        </div>

    </div>

</div>

</body>
</html>