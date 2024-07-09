@if(!empty($socialAccounts))
    @foreach($socialAccounts as $socialAccount)
        <li>
            <a href="#" class="block hover:bg-gray-50">
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="truncate text-sm font-medium text-indigo-600">{{ $socialAccount['name'] ?? '' }}</div>
                    </div>
                    <div class="mt-2 flex justify-between">
                        <div class="sm:flex">
                            <div class="flex items-center text-sm text-gray-500">
                                @if(!empty($socialAccount['avatar']))
                                    <img src="{{ $socialAccount['avatar'] ?? '' }}" alt="{{ $socialAccount['name'] ?? '' }}" class="mr-1.5 h-5 w-5 object-center object-cover flex-none rounded-full">
                                @else
                                    <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" x-description="Heroicon name: mini/users" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M7 8a3 3 0 100-6 3 3 0 000 6zM14.5 9a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM1.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 017 18a9.953 9.953 0 01-5.385-1.572zM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 00-1.588-3.755 4.502 4.502 0 015.874 2.636.818.818 0 01-.36.98A7.465 7.465 0 0114.5 16z"></path>
                                    </svg>
                                @endif
                                {{ $socialAccount['email'] ?? '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </li>
    @endforeach
@endif
