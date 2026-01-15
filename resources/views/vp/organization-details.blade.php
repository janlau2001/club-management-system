<x-dashboard-layout>
    <x-slot name="title">{{ $club->name }}</x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">{{ $club->name }}</h1>
            <a href="{{ route('vp.dashboard') }}" class="text-green-600 hover:text-green-800">← Back to Dashboard</a>
        </div>

        <!-- Club Details -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Club Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Department</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $club->department }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Club Type</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $club->club_type }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <span class="mt-1 px-2 py-1 text-xs font-medium rounded-full {{ $club->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($club->status) }}
                    </span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Member Count</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $club->clubUsers->count() }} ({{ $club->clubUsers->where('role', 'officer')->count() }} officers, {{ $club->clubUsers->where('role', 'member')->count() }} members)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date Registered</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $club->date_registered ? $club->date_registered->format('M j, Y') : 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Adviser</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $club->adviser_name ?: 'Not assigned' }}</p>
                </div>
                @if($club->description)
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        @php
                            $descLength = strlen($club->description);
                            $isLongDesc = $descLength > 300;
                        @endphp
                        @if($isLongDesc)
                            <div class="mt-1">
                                <p id="desc-short" class="text-sm text-gray-900 break-words overflow-wrap-anywhere">
                                    {{ Str::limit($club->description, 300) }}
                                </p>
                                <p id="desc-full" class="text-sm text-gray-900 break-words overflow-wrap-anywhere hidden">
                                    {{ $club->description }}
                                </p>
                                <button onclick="toggleDescription()" id="desc-toggle" class="text-blue-600 hover:text-blue-700 text-sm font-medium mt-2 focus:outline-none">
                                    Read More
                                </button>
                            </div>
                            <script>
                                function toggleDescription() {
                                    const shortText = document.getElementById('desc-short');
                                    const fullText = document.getElementById('desc-full');
                                    const toggleBtn = document.getElementById('desc-toggle');
                                    
                                    if (shortText.classList.contains('hidden')) {
                                        shortText.classList.remove('hidden');
                                        fullText.classList.add('hidden');
                                        toggleBtn.textContent = 'Read More';
                                    } else {
                                        shortText.classList.add('hidden');
                                        fullText.classList.remove('hidden');
                                        toggleBtn.textContent = 'Read Less';
                                    }
                                }
                            </script>
                        @else
                            <p class="mt-1 text-sm text-gray-900 break-words overflow-wrap-anywhere">{{ $club->description }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Officers Section -->
        @php
            $officers = $club->clubUsers->where('role', 'officer');
        @endphp
        @if($officers->count() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Club Officers ({{ $officers->count() }})</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($officers as $officer)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900">{{ $officer->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $officer->position ?? 'Officer' }}</p>
                            <p class="text-sm text-gray-500">{{ $officer->email }}</p>
                            @if($officer->student_id)
                                <p class="text-sm text-gray-500">ID: {{ $officer->student_id }}</p>
                            @endif
                            <p class="text-sm text-gray-500">{{ $officer->department }} • {{ $officer->year_level }}</p>
                            <p class="text-sm text-gray-400">Joined: {{ $officer->joined_date ? $officer->joined_date->format('M j, Y') : 'N/A' }}</p>
                            @if($officer->isOnline())
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                    Online
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mt-2">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full mr-1"></span>
                                    Offline
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Members Section -->
        @php
            $members = $club->clubUsers->where('role', 'member');
        @endphp
        @if($members->count() > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Club Members ({{ $members->count() }})</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year Level</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($members as $member)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $member->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->student_id ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->department }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->year_level }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($member->isOnline())
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                                Online
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="w-2 h-2 bg-gray-400 rounded-full mr-1"></span>
                                                Offline
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->joined_date ? $member->joined_date->format('M j, Y') : 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-dashboard-layout>