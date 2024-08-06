<div class="bg-gray-900 p-4 flex justify-between items-center">
    <h1 class="text-white text-2xl font-semibold">Kitchen Portal v1.3</h1>
    <div class="flex items-center space-x-4">
        <span class="text-gray-400">Cook: {{ $user->name }}</span>
        <span class="text-gray-400" x-data="{ currentTime: '{{ $currentTime }}' }" x-init="setInterval(() => { currentTime = new Date().toLocaleString(); }, 1000)" x-text="currentTime"></span>
    </div>
</div>
