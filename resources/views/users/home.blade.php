<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
@php
    $user = auth()->user();
@endphp
<div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
    <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
        <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
            <div class="ml-4 mt-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img class="h-12 w-12 rounded-full"
                             src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                             alt="">
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500">
                            <a href="#">{{ $user->email }}</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="ml-4 mt-4 flex ">
                <button type="button"
                        class="sync-profile-btn relative ml-3 flex flex-row gap-3 whitespace-nowrap items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                         class="flex-none w-5 h-5">
                        <path
                            d="M5.46257 4.43262C7.21556 2.91688 9.5007 2 12 2C17.5228 2 22 6.47715 22 12C22 14.1361 21.3302 16.1158 20.1892 17.7406L17 12H20C20 7.58172 16.4183 4 12 4C9.84982 4 7.89777 4.84827 6.46023 6.22842L5.46257 4.43262ZM18.5374 19.5674C16.7844 21.0831 14.4993 22 12 22C6.47715 22 2 17.5228 2 12C2 9.86386 2.66979 7.88416 3.8108 6.25944L7 12H4C4 16.4183 7.58172 20 12 20C14.1502 20 16.1022 19.1517 17.5398 17.7716L18.5374 19.5674Z"></path>
                    </svg>
                    <span>Sync Profile</span>
                </button>
                <a href="{{ route('logout') }}"  class="relative ml-3 whitespace-nowrap flex flex-row gap-3 whitespace-nowrap items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M4 18H6V20H18V4H6V6H4V3C4 2.44772 4.44772 2 5 2H19C19.5523 2 20 2.44772 20 3V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V18ZM6 11H13V13H6V16L1 12L6 8V11Z"></path></svg>
                    <span>Logout</span>
                </a>
            </div>
        </div>


    </div>
    <ul role="list" class="divide-y divide-gray-200 user-socials">
    </ul>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('.sync-profile-btn').on('click', function (evt) {
            evt.preventDefault();
            $.ajax({
                url: '{{ route("social.detail") }}',
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    format: 'json'
                },
                error: function() {
                    console.log('error')
                },
                success: function(data) {
                    console.log(data)
                    $('.user-socials').html(data)
                },
            });
        })
    });
</script>
</body>
</html>
