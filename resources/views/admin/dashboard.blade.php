<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-100 leading-tight flex items-center justify-between">
            <span class="flex items-center space-x-2">
                <span class="text-red-500">🏢</span>
                <span>{{ __('Global Command Center (Admin)') }}</span>
            </span>
            <span class="text-xs font-black uppercase text-red-400 bg-red-950/80 px-3.5 py-1.5 rounded-full border border-red-500/30 animate-pulse">
                🚨 Command Mode Active
            </span>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 relative z-10">
        
        @if (session('success'))
            <div class="bg-emerald-900/30 border border-emerald-500/30 p-4 rounded-2xl shadow-lg shadow-emerald-500/10 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-emerald-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-bold text-emerald-300">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-900/30 border border-red-500/30 p-4 rounded-2xl shadow-lg shadow-red-500/10 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-red-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-bold text-red-300">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($tab === 'home')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Broadcast & Analytics (Span 1) -->
            <div class="lg:col-span-1 space-y-8">
                
                <!-- Broadcast Card -->
                <div class="bg-slate-900/60 backdrop-blur-md overflow-hidden shadow-2xl rounded-3xl border border-slate-800 transition duration-300 hover:border-red-500/30">
                    <div class="p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-red-600/10 rounded-full blur-xl pointer-events-none"></div>

                        <h3 class="text-lg font-black text-white tracking-tight mb-4 border-b border-slate-800 pb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                            Emergency Broadcast
                        </h3>
                        <form action="{{ route('admin.alert') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="message" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Alert Message</label>
                                <textarea id="message" name="message" rows="4" class="block w-full border-slate-700 bg-slate-800/80 rounded-xl shadow-sm focus:ring-red-500 focus:border-red-500 text-sm font-semibold py-2.5 px-4 text-white placeholder-slate-500" required placeholder="Type emergency warning or evacuation notice here..."></textarea>
                            </div>
                            <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-red-500/20 text-xs font-extrabold uppercase tracking-widest text-white bg-gradient-to-r from-red-600 to-red-800 hover:from-red-500 hover:to-red-700 transition duration-150">
                                Push Alert to All Citizens
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Recent Alerts -->
                <div class="bg-slate-900/60 backdrop-blur-md shadow-2xl rounded-3xl border border-slate-800 overflow-hidden">
                    <div class="p-6 border-b border-slate-800 bg-slate-800/30">
                        <h3 class="text-base font-bold text-white">Recent Broadcasts</h3>
                    </div>
                    <div class="p-6 space-y-4 max-h-72 overflow-y-auto pr-2">
                        @forelse($alerts as $alert)
                            <div class="bg-slate-950/60 border border-slate-800 p-4 rounded-2xl text-xs font-semibold text-slate-300 relative group overflow-hidden">
                                <p class="leading-relaxed pr-6">{{ $alert->message }}</p>
                                <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-slate-900/60">
                                    <form action="{{ route('admin.alert.destroy', $alert) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] font-black uppercase text-red-500 hover:text-red-400 hover:underline transition duration-150 flex items-center cursor-pointer relative z-30">
                                            🗑️ Delete Alert
                                        </button>
                                    </form>
                                    <p class="text-[10px] text-slate-500 font-bold">
                                        {{ $alert->created_at->diffForHumans() }} by {{ $alert->admin->name }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-500 italic text-center py-6">No recent broadcasts recorded.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Incident Data (Span 2) -->
            <div class="lg:col-span-2">
                <div class="bg-slate-900/60 backdrop-blur-md shadow-2xl rounded-3xl border border-slate-800 overflow-hidden h-full">
                    <div class="p-6 border-b border-slate-800 bg-slate-800/30 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white">Global Incident Management Grid</h3>
                        <span class="text-xs font-bold text-slate-500">Real-Time Data Feed</span>
                    </div>
                    <div class="p-6">
                        @if($incidents->isEmpty())
                            <div class="text-center py-12">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">No incidents recorded in the system.</p>
                            </div>
                        @else
                            <div class="hidden lg:block overflow-x-auto rounded-2xl border border-slate-855 bg-slate-950/40">
                                <table class="min-w-full divide-y divide-slate-800">
                                    <thead class="bg-slate-900/80">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Time</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Type</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Victim Info</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Assigned Responder</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-850/60">
                                        @foreach($incidents as $incident)
                                            <tr class="hover:bg-slate-900/30 transition duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400 font-mono font-bold">{{ $incident->created_at->format('H:i:s M d') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-3 py-1 inline-flex text-xs font-extrabold rounded-full border
                                                        {{ $incident->type === 'Medical' ? 'bg-blue-900/30 text-blue-300 border-blue-500/30' : '' }}
                                                        {{ $incident->type === 'Fire' ? 'bg-red-900/30 text-red-300 border-red-500/30' : '' }}
                                                        {{ $incident->type === 'Flood' ? 'bg-cyan-900/30 text-cyan-300 border-cyan-500/30' : '' }}
                                                    ">
                                                        {{ $incident->type }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-300 font-bold">
                                                    {{ $incident->user->name }}<br>
                                                    <span class="text-[10px] text-slate-500 font-mono">{{ $incident->location }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($incident->status === 'Pending')
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-900/30 text-amber-400 border border-amber-500/30">
                                                            Pending
                                                        </span>
                                                    @elseif($incident->status === 'En Route')
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-900/30 text-blue-400 border border-blue-500/30 animate-pulse">
                                                            En Route
                                                        </span>
                                                    @elseif($incident->status === 'On Scene')
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-900/30 text-emerald-400 border border-emerald-500/30">
                                                            On Scene
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700">
                                                            Resolved
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-300 font-extrabold">
                                                    @php
                                                        $lastLog = $incident->responseLogs->last();
                                                    @endphp
                                                    @if($lastLog)
                                                        👤 {{ $lastLog->responder->name }}
                                                    @else
                                                        <span class="text-slate-600 font-medium italic">Unassigned</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Cards List (block lg:hidden) -->
                            <div class="block lg:hidden space-y-4 mt-4 text-left">
                                @foreach($incidents as $incident)
                                    <div class="bg-slate-950/60 backdrop-blur-md border border-slate-850 rounded-2xl p-5 space-y-4 hover:border-red-500/30 transition duration-150 relative overflow-hidden">
                                        <!-- Top row: Nature Badge & Status -->
                                        <div class="flex items-center justify-between border-b border-slate-850 pb-3">
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2.5 py-0.5 text-[10px] font-extrabold rounded-full border
                                                    {{ $incident->type === 'Medical' ? 'bg-blue-900/30 text-blue-300 border-blue-500/30' : '' }}
                                                    {{ $incident->type === 'Fire' ? 'bg-red-900/30 text-red-300 border-red-500/30' : '' }}
                                                    {{ $incident->type === 'Flood' ? 'bg-cyan-900/30 text-cyan-300 border-cyan-500/30' : '' }}
                                                ">
                                                    {{ $incident->type }}
                                                </span>
                                            </div>
                                            <div>
                                                @if($incident->status === 'Pending')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-900/30 text-amber-400 border border-amber-500/30">
                                                        Pending
                                                    </span>
                                                @elseif($incident->status === 'En Route')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-blue-900/30 text-blue-400 border border-blue-500/30 animate-pulse">
                                                        En Route
                                                    </span>
                                                @elseif($incident->status === 'On Scene')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-900/30 text-emerald-400 border border-emerald-500/30">
                                                        On Scene
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-800 text-slate-400 border border-slate-700">
                                                        Resolved
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Middle: Time & Location -->
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between text-slate-400 text-xs font-mono font-bold">
                                                <span>⏱️ {{ $incident->created_at->format('H:i:s M d') }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="font-extrabold text-white text-xs">👤 {{ $incident->user->name }}</span>
                                            </div>
                                            <div class="text-[10px] text-slate-400 font-bold font-mono bg-slate-900/50 p-2.5 rounded-xl border border-slate-850 flex items-center space-x-1">
                                                <span>📍 Location:</span>
                                                <span class="text-white">{{ $incident->location }}</span>
                                            </div>
                                        </div>

                                        <!-- Bottom: Assigned Responder -->
                                        <div class="pt-3 border-t border-slate-850 text-xs flex items-center justify-between">
                                            <span class="text-slate-500 font-bold">Assigned Rescuer:</span>
                                            <span class="font-extrabold text-slate-300">
                                                @php
                                                    $lastLog = $incident->responseLogs->last();
                                                @endphp
                                                @if($lastLog)
                                                    👤 {{ $lastLog->responder->name }}
                                                @else
                                                    <span class="text-slate-600 font-medium italic">Unassigned</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @elseif($tab === 'responders')
        <div x-data="{ open: false, name: '', email: '', phone: '', actionUrl: '' }">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Registration Card -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Add Responder Card -->
                    <div class="bg-slate-900/60 backdrop-blur-md overflow-hidden shadow-2xl rounded-3xl border border-slate-800 transition duration-300 hover:border-blue-500/30">
                        <div class="p-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-blue-600/10 rounded-full blur-xl pointer-events-none"></div>

                            <h3 class="text-lg font-black text-white tracking-tight mb-4 border-b border-slate-800 pb-3 flex items-center">
                                <span class="text-blue-500 mr-2 text-lg">🚒</span>
                                Add First Responder
                            </h3>
                            <form action="{{ route('admin.responder.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="resp_name" class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Full Name</label>
                                    <input type="text" id="resp_name" name="name" class="block w-full border-slate-700 bg-slate-800/80 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs font-semibold py-2 px-3 text-white placeholder-slate-500" required placeholder="Responder Name">
                                </div>
                                
                                <div>
                                    <label for="resp_email" class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Email Address</label>
                                    <input type="email" id="resp_email" name="email" class="block w-full border-slate-700 bg-slate-800/80 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs font-semibold py-2 px-3 text-white placeholder-slate-500" required placeholder="responder@guardian.net">
                                </div>

                                <div>
                                    <label for="resp_phone" class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Phone Number</label>
                                    <input type="text" id="resp_phone" name="phone" class="block w-full border-slate-700 bg-slate-800/80 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs font-semibold py-2 px-3 text-white placeholder-slate-500" placeholder="e.g. 555-0123">
                                </div>

                                <div>
                                    <label for="resp_password" class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Initial Password</label>
                                    <input type="password" id="resp_password" name="password" class="block w-full border-slate-700 bg-slate-800/80 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs font-semibold py-2 px-3 text-white placeholder-slate-500" required placeholder="••••••••">
                                </div>

                                <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/20 text-xs font-extrabold uppercase tracking-widest text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-500 hover:to-blue-700 transition duration-150">
                                    Register Responder
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Responder Roster -->
                <div class="lg:col-span-2">
                    <div class="bg-slate-900/60 backdrop-blur-md shadow-2xl rounded-3xl border border-slate-800 overflow-hidden h-full">
                        <div class="p-6 border-b border-slate-800 bg-slate-800/30 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <span class="text-blue-500 mr-2">📋</span>
                                Active Responder Roster
                            </h3>
                            <span class="text-[10px] font-black uppercase text-blue-400 bg-blue-900/30 px-3 py-1 rounded-full border border-blue-500/30">
                                {{ $responders->count() }} Registered
                            </span>
                        </div>
                        <div class="p-6">
                            @if($responders->isEmpty())
                                <div class="text-center py-12">
                                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">No active responders registered yet.</p>
                                </div>
                            @else
                                <div class="hidden lg:block overflow-x-auto rounded-2xl border border-slate-800 bg-slate-950/40">
                                    <table class="min-w-full divide-y divide-slate-800">
                                        <thead class="bg-slate-900/80">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Name</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Email</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Phone</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Registered Date</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-850/60">
                                            @foreach($responders as $resp)
                                                <tr class="hover:bg-slate-900/30 transition duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-white font-extrabold flex items-center space-x-2">
                                                        <span>👤</span>
                                                        <span>{{ $resp->name }}</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-300 font-semibold font-mono">{{ $resp->email }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-400 font-bold font-mono">{{ $resp->phone ?? 'N/A' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-mono">{{ $resp->created_at->format('M d, Y H:i') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-xs flex items-center space-x-4">
                                                        <!-- Edit Button triggers Alpine modal -->
                                                        <button @click="open = true; name = '{{ addslashes($resp->name) }}'; email = '{{ addslashes($resp->email) }}'; phone = '{{ addslashes($resp->phone ?? '') }}'; actionUrl = '{{ route('admin.responder.update', $resp) }}'" 
                                                                class="text-blue-500 hover:text-blue-400 hover:underline transition duration-150 font-bold uppercase tracking-wider text-[10px] cursor-pointer">
                                                            ✏️ Edit
                                                        </button>
                                                        
                                                        <!-- Delete Form with direct confirmation -->
                                                        <form action="{{ route('admin.responder.destroy', $resp) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete responder {{ addslashes($resp->name) }}? This action is permanent.');" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-400 hover:underline transition duration-150 font-bold uppercase tracking-wider text-[10px] cursor-pointer">
                                                                🗑️ Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Mobile Cards List (block lg:hidden) -->
                                <div class="block lg:hidden space-y-4 mt-4 text-left">
                                    @foreach($responders as $resp)
                                        <div class="bg-slate-950/60 backdrop-blur-md border border-slate-850 rounded-2xl p-5 space-y-4 hover:border-blue-500/30 transition duration-150 relative overflow-hidden">
                                            <!-- Top Row: Name & Status Icon -->
                                            <div class="flex items-center justify-between border-b border-slate-850 pb-3">
                                                <div class="flex items-center space-x-2 text-sm font-extrabold text-white">
                                                    <span>👤</span>
                                                    <span>{{ $resp->name }}</span>
                                                </div>
                                                <span class="text-[9px] font-black uppercase text-blue-400 bg-blue-900/10 px-2 py-0.5 rounded-full border border-blue-500/30">Active</span>
                                            </div>

                                            <!-- Details: Email, Phone, Registered Date -->
                                            <div class="space-y-1.5 text-xs">
                                                <p class="text-slate-400 font-bold">Email: <span class="text-slate-300 font-semibold font-mono">{{ $resp->email }}</span></p>
                                                <p class="text-slate-400 font-bold">Phone: <span class="text-slate-300 font-bold font-mono">{{ $resp->phone ?? 'N/A' }}</span></p>
                                                <p class="text-slate-500 font-medium font-mono text-[10px]">Registered: {{ $resp->created_at->format('M d, Y H:i') }}</p>
                                            </div>

                                            <!-- Actions Row -->
                                            <div class="pt-3 border-t border-slate-850 flex items-center justify-end space-x-4">
                                                <button @click="open = true; name = '{{ addslashes($resp->name) }}'; email = '{{ addslashes($resp->email) }}'; phone = '{{ addslashes($resp->phone ?? '') }}'; actionUrl = '{{ route('admin.responder.update', $resp) }}'" 
                                                        class="text-blue-500 hover:text-blue-400 hover:underline transition duration-150 font-bold uppercase tracking-wider text-[10px] cursor-pointer">
                                                    ✏️ Edit
                                                </button>
                                                <form action="{{ route('admin.responder.destroy', $resp) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete responder {{ addslashes($resp->name) }}? This action is permanent.');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-400 hover:underline transition duration-150 font-bold uppercase tracking-wider text-[10px] cursor-pointer">
                                                        🗑️ Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Responder Modal (Alpine.js) -->
            <div x-show="open" 
                 class="fixed inset-0 z-50 overflow-y-auto" 
                 style="display: none;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Backdrop shadow overlay -->
                    <div class="fixed inset-0 transition-opacity bg-slate-950/80 backdrop-blur-sm" @click="open = false"></div>

                    <!-- Centering trick -->
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <!-- Modal content -->
                    <div class="inline-block align-bottom bg-slate-900 border border-slate-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        
                        <div class="p-6 relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-32 h-32 bg-blue-600/10 rounded-full blur-xl pointer-events-none"></div>

                            <div class="flex items-center justify-between pb-3 border-b border-slate-800 mb-6">
                                <h3 class="text-lg font-black text-white flex items-center">
                                    <span class="text-blue-500 mr-2 text-lg">✏️</span>
                                    Edit First Responder
                                </h3>
                                <button @click="open = false" class="text-slate-500 hover:text-white transition duration-150 text-xl font-bold">&times;</button>
                            </div>

                            <form :action="actionUrl" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')
                                
                                <div>
                                    <label for="edit_name" class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Full Name</label>
                                    <input type="text" id="edit_name" name="name" x-model="name" class="block w-full border-slate-700 bg-slate-800/80 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs font-semibold py-2 px-3 text-white placeholder-slate-500" required>
                                </div>

                                <div>
                                    <label for="edit_email" class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Email Address</label>
                                    <input type="email" id="edit_email" name="email" x-model="email" class="block w-full border-slate-700 bg-slate-800/80 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs font-semibold py-2 px-3 text-white placeholder-slate-500" required>
                                </div>

                                <div>
                                    <label for="edit_phone" class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">Phone Number</label>
                                    <input type="text" id="edit_phone" name="phone" x-model="phone" class="block w-full border-slate-700 bg-slate-800/80 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs font-semibold py-2 px-3 text-white placeholder-slate-500">
                                </div>

                                <div>
                                    <label for="edit_password" class="block text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1.5">New Password (leave blank to keep current)</label>
                                    <input type="password" id="edit_password" name="password" class="block w-full border-slate-700 bg-slate-800/80 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-xs font-semibold py-2 px-3 text-white placeholder-slate-500" placeholder="••••••••">
                                </div>

                                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                                    <button type="button" @click="open = false" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-white transition duration-150">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-extrabold uppercase tracking-widest text-white bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-500 hover:to-blue-700 transition duration-150 shadow-lg shadow-blue-500/20">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
