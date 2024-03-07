<div class="m-auto w-1/2 mt-20 p-4 text-white border rounded-lg">
    <h1 class="text-3xl text-gray-900 text-center text-bold mb-2">Register User</h1>
    <div class="text-gray-800">
        <form wire:submit.prevent='submit'>
            {{ $this->form }}
        </form>
    </div>
</div>